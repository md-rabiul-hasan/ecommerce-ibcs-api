<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderActionRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class OrderDeliveryController extends Controller
{
     /**
     * Order Delivery
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  order_tracking_no string required. Every order tracking number is unique Example: 62003816439d2
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Order Delivery Successfully",
     *      "data": {
     *          "order_tracking_no": "62003816439d2"
     *      }
     *   }
     * @response 400{
     *       "success": false,
     *       "status": 400,
     *       "message": "In this order all product doesn't available in stock"
     *   }
     * 
     */
    public function delivery(OrderActionRequest $request){
        $order_tracking_no = $request->input('order_tracking_no');        
        try{
            $order = Order::where('order_tracking_no', $order_tracking_no)->first();
            if($this->checkStockAvailable($order->id) === true){ // check stock before delivery
                $this->updateOrderDetails($order->id); // update stock & delivery
                $this->orderHistory($order_tracking_no, "Order Delivery Complete"); // insert order log
                return $this->successApiResponse(200, 'Order Delivery Successfully', ["order_tracking_no" => $order_tracking_no]);
            }else{
                return $this->failedApiResponse(400, "In this order all product doesn't available in stock");
            }            
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }


    public function updateOrderDetails($order_id){
        $order_details = OrderDetail::select(['id', 'product_id', 'qty'])->where('order_id', $order_id)->where('status', 0)->get();
        foreach($order_details as $order_detail){
            $product_id      = $order_detail->product_id;
            $order_detail_id = $order_detail->id;

            // decrease stock product
            $product      = Product::findOrFail($product_id);
            Product::where('id', $product_id)->update([
                "qty" => $product->qty - $order_detail->qty
            ]);

            // update to order product delivery status
            OrderDetail::where('id', $order_detail_id)->update([
                "status" => 1
            ]);
        }
        
        // order delivery mark
        Order::where('id', $order_id)->update([
            "status" => 3,
            "date"   => date('Y-m-d')
        ]); 
    }

    public function checkStockAvailable($order_id){
        $order_details = OrderDetail::select(['product_id', 'qty'])->where('order_id', $order_id)->where('status', 0)->get();
        foreach($order_details as $order_detail){
            $product = Product::where('id', $order_detail->product_id)->where('qty', '>=', $order_detail->qty)->first();
            if(!$product){
                return false; // product doesn't available in stock
            }
        }
        return true; // product available in stock
    }
}

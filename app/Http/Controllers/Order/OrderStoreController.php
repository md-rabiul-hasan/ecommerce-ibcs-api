<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderStoreController extends Controller
{
    /**
     * Order Reqeust
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  amount decimal required. Example: 1000.00
     * @bodyParam  shipping_address text required. Example: IBCS-Primax
     * @bodyParam  items array required. Example:  [{  "product_id" : "2", "qty": 2, "price": 10.00 }]
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *       "message": "Order Confirmed Successfully",
     *       "data": {
     *           "order_tracking_no": "62003816439d2"
     *      }
     *  }
     * 
     */
    public function store(OrderStoreRequest $request){
        $unique_order_tracking_no = uniqid();
        $order                    = new Order();
        $order->amount            = $request->input('amount');
        $order->shipping_address  = $request->input('shipping_address');
        $order->order_tracking_no = $unique_order_tracking_no;
        $order->date              = date('Y-m-d');
        $order->user_id           = Auth::user()->id;

        try{
            $order->save();
            $this->insertOrderDetails($order->id, $request->input('items')); // insert order details
            $this->orderHistory($order->order_tracking_no, "Order Confirmed"); // insert order log
            $this->sendOrderNotificationQueue($order->order_tracking_no); // send order notification in queue for admin notify
            return $this->successApiResponse(200, 'Order Confirmed Successfully', ["order_tracking_no" => $order->order_tracking_no]);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }

    private function insertOrderDetails($order_id, $items){        
        for($i=0; $i < count($items); $i++){
            $order_details             = new OrderDetail();
            $order_details->product_id = $items[$i]['product_id'];
            $order_details->order_id   = $order_id;
            $order_details->price      = $items[$i]['price'];
            $order_details->qty        = $items[$i]['qty'];
            $order_details->save();
        }
    }

    private function sendOrderNotificationQueue($order_tracking_no){
        $data = [
            'name'              => "Rabiul Hasan",
            'email'             => "rabiul.fci@gmail.com",
            'subject'           => "New Order Confirmed",
            'message'           => "One new order coming in your e-commerce.Please see details",
            'order_tracking_no' => $order_tracking_no
        ];
        $sendEmailJob = new \App\Jobs\OrderNotificationJob($data);
        dispatch($sendEmailJob);
    }
}

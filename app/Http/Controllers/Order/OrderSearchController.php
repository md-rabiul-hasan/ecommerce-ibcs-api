<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderActionRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class OrderSearchController extends Controller
{
     /**
     * Order Search
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  order_tracking_no string required. Every order tracking number is unique Example: 62003816439d2
     * @response 200{
     *       "success": true,
     *       "status": 200,
     *       "message": "Order Searching Result",
     *      "data": {
     *          "id": 7,
     *          "amount": "100.00",
     *          "shipping_address": "Fokirapool, Dhaka, Bangladesh",
     *          "order_tracking_no": "6200220e07f43",
     *          "date": "2022-02-06",
     *          "status": 3
     *      }
     *  }
     */
    public function search(OrderActionRequest $request){
        $order_tracking_no = $request->input('order_tracking_no');  
        try{
            $orders = Order::select(['id','amount','shipping_address','order_tracking_no','date','status'])->where('order_tracking_no',$order_tracking_no)->first();
            if($orders){
                return $this->successApiResponse(200, 'Order Searching Result', $orders);
            }else{
                return $this->failedApiResponse(400, "Order not found");
            }            
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        } 
    }
}

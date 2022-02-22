<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderActionRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class OrderRejectedController extends Controller
{
     /**
     * Order Rejected
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  order_tracking_no string required. Every order tracking number is unique Example: 62003816439d2
     * @response 200{
     *       "success": true,
     *      "status": 200,
     *      "message": "Order Rejected Successfully",
     *      "data": {
     *          "order_tracking_no": "62003816439d2"
     *      }
     *  }
     * 
     */
    public function rejected(OrderActionRequest $request){
        $order_tracking_no = $request->input('order_tracking_no');        
        try{
            Order::where('order_tracking_no', $order_tracking_no)->update([
                "status" => 2, // rejected order status
            ]);
            $this->orderHistory($order_tracking_no, "Order Rejected");
            return $this->successApiResponse(200, 'Order Rejected Successfully', ["order_tracking_no" => $order_tracking_no]);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }
}

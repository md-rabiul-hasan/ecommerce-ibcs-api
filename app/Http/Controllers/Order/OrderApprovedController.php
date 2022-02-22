<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderActionRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;

class OrderApprovedController extends Controller
{
    /**
     * Order Approved
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  order_tracking_no string required. Every order tracking number is unique Example: 62003816439d2
     * @response 200{
     *       "success": true,
     *       "status": 200,
     *       "message": "Order Approved Successfully",
     *       "data": {
     *           "order_tracking_no": "62003816439d2"
     *       }
     *   }
     * 
     */

    public function approved(OrderActionRequest $request){
        $order_tracking_no = $request->input('order_tracking_no');        
        try{
            Order::where('order_tracking_no', $order_tracking_no)->update([
                "status" => 1, // approved order status
            ]);
            $this->orderHistory($order_tracking_no, "Order Approved");
            return $this->successApiResponse(200, 'Order Approved Successfully', ["order_tracking_no" => $order_tracking_no]);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }
}

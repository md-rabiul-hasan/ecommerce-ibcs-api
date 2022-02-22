<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderActionRequest;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    /**
     * Order History
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  order_tracking_no string required. Every order tracking number is unique Example: 62003816439d2
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Order Delivery Successfully",
     *      "data": [
     *           {
     *               "message": "Order Confirmed",
     *               "user_name": "Rabiul Hasan",
     *               "date_time": "2022-02-06 21:25:34"
     *          },
     *          {
     *              "message": "Order Approved",
     *              "user_name": "Rabiul Hasan",
     *               "date_time": "2022-02-06 21:26:24"
     *           },
     *          {
     *               "message": "Order Delivery Complete",
     *               "user_name": "Rabiul Hasan",
     *               "date_time": "2022-02-06 21:26:37"
     *           },
     *      ]
     *   }
     */
    public function history(OrderActionRequest $request){
        $order_tracking_no = $request->input('order_tracking_no');  
        try{
            $histories = DB::table('order_histories')
                        ->select([
                            'order_histories.message',
                            'users.name as user_name',
                            'order_histories.date_time',
                        ])
                        ->leftJoin('users', 'order_histories.user_id', '=', 'users.id')
                        ->where('order_histories.order_tracking_no', $order_tracking_no)
                        ->get();           
            return $this->successApiResponse(200, 'Order Delivery Successfully', $histories);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Customer role id return this function
    */
    public function getCustomerRoleId(){
        return 2;
    }

    /**
     * Formatted and return api success API response
    */
    public function successApiResponse($status, $message, $data = ''){
        if(!empty($data)){
            $response = [
                "success" => true,
                "status"  => $status,
                "message" => $message,
                "data"    => $data
            ];
        }else{
            $response = [
                "success" => true,
                "status"  => $status,
                "message" => $message
            ];
        }
        
        return response()->json($response);
    }

    /**
     * Formatted and return api failed API response
    */
    public function failedApiResponse($status, $message){
        $response = [
            "success" => false,
            "status"  => $status,
            "message" => $message
        ];
        return response()->json($response);
    }

    /**
     * This method storing all transaction history
    */
    protected function orderHistory($order_tracking_no, $message){
        $order_history                    = new OrderHistory();
        $order_history->order_tracking_no = $order_tracking_no;
        $order_history->message           = $message;
        $order_history->date_time         = date('Y-m-d H:i:s');
        $order_history->user_id           = Auth::user()->id;
        $order_history->save();
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegistrationRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Customer Registration
     *
     * @bodyParam  name string required. Customer name Example: Rabiul Hasan
     * @bodyParam  email string required. Email must be unique and valid Example: mdrabiulhasan.me@gmail.com
     * @bodyParam  password string required. Example: 123456
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
     * "success": true,
     * "status": 200,
     * "message": "Customer Registration Successfully",
     * "data": {
     *     "name": "Rabiul Hasan",
     *     "email": "mdrabiulhasan.me@gmail.com",
     *     "role_id": 2,
     *     "updated_at": "2022-02-06T20:26:58.000000Z",
     *     "created_at": "2022-02-06T20:26:58.000000Z",
     *     "id": 5
     *   }
     * }
     * @response 400{
	 * 		"success": false,
	 *		"status": 400,
	 *		"message": "Your email address already exists"
	 *	}
     * 
     */
    public function registration(CustomerRegistrationRequest $request){
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $this->getCustomerRoleId();
        $user->password = Hash::make($request->input('password'));
        try{
            $user->save();
            return $this->successApiResponse(200, 'Customer Registration Successfully', $user);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }
}

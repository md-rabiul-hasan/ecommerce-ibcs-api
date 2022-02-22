<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
     /**
     * Logout
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
     *       "success": true,
     *      "status": 200,
     *      "message": "Logout successfully"
     *  }
     * @response 403{
     *      "status": 403,
     *      "success": false,
     *      "message": "un-authenticated user"
     *  }
     * 
     */
    public function logout(){
        auth()->logout();
        return $this->successApiResponse(200, "Logout successfully");
    }
}

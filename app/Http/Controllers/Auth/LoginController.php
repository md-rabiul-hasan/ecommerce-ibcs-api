<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
     /**
     * Login
     *
     * @bodyParam  email string required. Email for login Example: mdrabiulhasan.me@gmail.com
     * @bodyParam  password string required. Passwrod for login Example: 123456
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
	 * 		"success": true,
	 *		"status": 200,
	 *		"message": "Login Successfully",
	 *		"data": {
	 *			"access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0NDE3OTU4OCwiZXhwIjoxNjQ0MTgzMTg4LCJuYmYiOjE2NDQxNzk1ODgsImp0aSI6InhIZE50d2tZMjRqZVF3SkEiLCJzdWIiOjUsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.md_7MxsZztunX1H3TYlrdh8HTWnsX7qXLRwRGl6rZFo",
	 *			"token_type": "bearer",
	 *			"expires_in": 3600
	 *		}
	 *	}
     * @response 401{
     *      "success": false,
     *      "status": 401,
     *      "message": "Your email or password was incorrect"
     * }
     * 
     */
    public function login(LoginFormRequest $request){
        $credentials = [
            "email"    => $request->input('email'),
            "password" => $request->input('password')
        ];
        if (!$token = auth()->attempt($credentials)) {
            return $this->failedApiResponse(401, "Your email or password was incorrect");
        }

        return $this->successApiResponse(200, "Login Successfully", $this->respondWithToken($token));

    }

     /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }


}

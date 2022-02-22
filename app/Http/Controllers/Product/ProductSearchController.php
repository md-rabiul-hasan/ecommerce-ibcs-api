<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    /**
     * Product Searching
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Product Searching Result",
     *      "data": [
     *          {
     *              "id": 2,
     *              "name": "Computer",
     *              "description": "Laptop core i3",
     *              "price": "60000.00",
     *              "qty": 1,
     *              "images": "product/33XB8eHBgORRpF0cW3WyoCEm8ELD793zuFIpOuri.jpg",
     *              "entry_date": "2022-02-06"
     *          }
     *      ]
     *  }
     * 
     */
    public function search($product_name){
        try{
            $products = Product::select(['id','name','description','price','qty','images','entry_date'])->where('name','LIKE','%'.$product_name.'%')->get();
            return $this->successApiResponse(200, 'Product Searching Result', $products);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }         
    }
}

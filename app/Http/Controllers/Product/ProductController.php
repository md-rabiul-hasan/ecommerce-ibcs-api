<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductEditRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     /**
     * Product Store
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  name string required. Product name Example: Mac-Book Pro
     * @bodyParam  description text required. Product Description Example: Apple Mac-Book Pro 13'
     * @bodyParam  price decimal required. Product Price Example: 100.00
     * @bodyParam  qty integer required. Product quantity Example: 3
     * @bodyParam  images files required. Product image only image accept Example: photo.png
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Product Store Successfully"
     *  }
     * @response 400{
     *       "success": false,
     *       "status": 400,
     *       "message": "The images must be an image."
     *   }
     * 
     */
    public function store(ProductStoreRequest $request){
        //store file into document folder
        $images = $request->file('images')->store('product', 'public');
        $product              = new Product();
        $product->name        = $request->input('name');
        $product->description = $request->input('description');
        $product->price       = $request->input('price');
        $product->qty         = $request->input('qty');
        $product->images      = $images;
        $product->entry_date  = date('Y-m-d');
        try{
            $product->save();
            return $this->successApiResponse(200, 'Product Store Successfully');
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }



    /**
     * Product Show
     * 
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
     *       "success": true,
     *       "status": 200,
     *       "message": "Product Fetching Successfully",
     *       "data": {
     *           "id": 3,
     *           "name": "Mobile",
     *          "description": "Redmi",
     *          "price": "10000.00",
     *          "qty": 3,
     *          "images": "product/ZNVLUKAAu7aNWug4GufltLYPJkT6F256kFlBJ7K5.jpg",
     *          "entry_date": "2022-02-06",
     *          "created_at": "2022-02-06T17:36:12.000000Z",
     *          "updated_at": "2022-02-06T19:32:25.000000Z"
     *       }
     *  }
     * 
     */
    public function show(Product $product){
        try{
            return $this->successApiResponse(200, 'Product Fetching Successfully', $product);
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }  
    }



    /**
     * Product Update
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @bodyParam  name string required. Product name Example: Mac-Book Pro
     * @bodyParam  description text required. Product Description Example: Apple Mac-Book Pro 13'
     * @bodyParam  price decimal required. Product Price Example: 100.00
     * @bodyParam  qty integer required. Product quantity Example: 3
     * @bodyParam  images files required. Product image only image accept Example: photo.png
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Product Updated Successfully"
     *  }
     * 
     */
    public function update(ProductEditRequest $request, Product $product){
        if($request->hasFile('images')){
            $images = $request->file('images')->store('product', 'public');
        }else{
            $images = $product->images;
        }

        $product->name        = $request->input('name');
        $product->description = $request->input('description');
        $product->price       = $request->input('price');
        $product->qty         = $request->input('qty');
        $product->images      = $images;
        try{
            $product->save();
            return $this->successApiResponse(200, 'Product Updated Successfully');
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }

    /**
     * Product Delete
     * @authenticated
     * @header Authorization bearer your-token
     * @responseField success The success of this API response is (`true` or `false`).
     * 
     * @response 200{
     *      "success": true,
     *      "status": 200,
     *      "message": "Product Delete Successfully"
     *  }
     * 
     * @response 404{
     *      "status": 404,
     *      "success": false,
     *      "message": "your item not found"
     *  }
     * 
     */
    public function delete(Product $product){
        try{
            $product->delete();
            return $this->successApiResponse(200, 'Product Delete Successfully');
        }catch(Exception $e){
            return $this->failedApiResponse(500, $e->getMessage());
        }
    }


}

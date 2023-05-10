<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::paginate(10);
    }

    /**
     * Show the specified resource in storage.
     */
    public function get(string $id)
    {
        $product = Product::where('id',$id)->first();
        if ($product) {
        	return response()->json([
        		'message' => 'product found',
        		'status' => true,
        		'data' => $product
        	], 200);
        }

        return response()->json([
    		'message' => 'product not found',
    		'status' => false
    	], 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
		   'name'   => 'required',
		   'price' => 'required|integer',
		   'stock' => 'required|integer'
		]);

		if ($validator->fails()) {
		   return response()->json($validator->errors(), 400);
		}

		$product = Product::create([
		    'name'     => $request->name,
		    'price'   => $request->price,
		    'stock'   => $request->stock,
		    'description'   => $request->description ?? ''
		]);

		if ($product) {
        	return response()->json([
        		'message' => 'product successfully save',
        		'status' => true,
        		'data' => $product
        	], 200);
        }

        return response()->json([
    		'message' => 'product failed save',
    		'status' => false
    	], 409);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
		   'name'   => 'required',
		   'price' => 'required|integer',
		   'stock' => 'required|integer'
		]);

		if ($validator->fails()) {
		   return response()->json($validator->errors(), 400);
		}

		$product = Product::where('id',$id)->first();
		if ($product) {
			$update = Product::where('id',$id)->update([
			    'name'     => $request->name,
			    'price'   => $request->price,
			    'stock'   => $request->stock,
			    'description'   => $request->description ?? ''
			]);

			if ($update) {
				return response()->json([
	        		'message' => 'product successfully update',
	        		'status' => true,
	        		'data' => Product::where('id',$id)->first()
	        	], 200);
			}

			return response()->json([
	    		'message' => 'product failed update',
	    		'status' => false
	    	], 409);
		}

        return response()->json([
    		'message' => 'product not found',
    		'status' => false
    	], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id',$id)->first();
		if ($product) {
			$product->delete();
			return response()->json([
        		'message' => "product id $id deleted",
        		'status' => true
        	], 200);
		}

		return response()->json([
    		'message' => 'product not found',
    		'status' => false
    	], 404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $searchStatus = in_array($request->status,['active','inactive']) ? $request->status : null;
            $searchGlobal = $request->search ? $request->search : null;
            $page_size = $request->page_size ? $request->page_size : 10;

            $product = Product::when($searchStatus != null , function($filterStatus) use ($searchStatus){
                $filterStatus->where('prod_status',$searchStatus);
            })->when($searchGlobal != null , function($filterGlobal) use ($searchGlobal){
                $filterGlobal->where(function($globalSearch) use ($searchGlobal){
                    $globalSearch->where('prod_code','ilike','%'. $searchGlobal .'%')->orWhere('prod_name','ilike','%'. $searchGlobal .'%')
                    ->orWhere('prod_description','ilike','%'. $searchGlobal .'%');
                });
            })->paginate($page_size)->withQueryString();

            return response()->json($product,200);
        } catch (\Throwable $e){
            return response()->json(['status' => 'error' , 'message' => $e->getMessage()],500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'prod_code' => 'required|unique:product,prod_code',
                'prod_name' => 'required|numeric',
                'prod_price' => 'required',
                'prod_description'=> 'required',
                'prod_status' => [Rule::in(['active','inactive'])]
            ]);

            $product = new Product();
            $product->prod_code = $request->prod_code;
            $product->prod_name = $request->prod_name;
            $product->prod_price = $request->prod_price;
            $product->prod_description = $request->prod_description;
            if ($request->prod_status){
                $product->prod_status = $request->prod_status;
            }
            $product->creator = auth()->user()->name;
            $product->editor = auth()->user()->name;
            $product->save();

            return response()->json($product,201);
        } catch (\Throwable $e){
            return response()->json(['status' => 'error' , 'message' => $e->getMessage()],500);
        }
    }

    public function show($code)
    {
        try {
            $product = Product::where('prod_code' , $code)->first();
            if ($product){
                return response()->json($product,200);
            } else {
                return response()->json(['status' => 'error' , 'message' => 'Product not found'],404);
            }
        } catch (\Throwable $e){
            return response()->json(['status' => 'error' , 'message' => $e->getMessage()],500);
        }
    }

    public function update($code, Request $request)
    {
        try {
            $product = Product::where('prod_code' , $code)->first();
            if ($product){
                $request->validate([
                    'prod_name' => 'required',
                    'prod_price' => 'required|numeric',
                    'prod_description'=> 'required',
                    'prod_status' => ['required',Rule::in(['active','inactive'])]
                ]);

                $product->prod_name = $request->prod_name;
                $product->prod_price = $request->prod_price;
                $product->prod_description = $request->product_description;
                $product->prod_status = $request->prod_status;
                $product->editor = auth()->user()->name;
                $product->save();
                return response()->json($product,201);
            } else {
                return response()->json(['status' => 'error' , 'message' => 'Product not found'],404);
            }
        } catch (\Throwable $e){
            return response()->json(['status' => 'error' , 'message' => $e->getMessage()],500);
        }
    }

    public function destroy($code)
    {
        try {
            $product = Product::where('prod_code' , $code)->first();
            if ($product){
                $product->delete();
                return response()->json(['status' => 'success' , 'message' => 'Product has been deleted!'],201);
            } else {
                return response()->json(['status' => 'error' , 'message' => 'Product not found'],404);
            }
        } catch (\Throwable $e){
            return response()->json(['status' => 'error' , 'message' => $e->getMessage()],500);
        }
    }
}


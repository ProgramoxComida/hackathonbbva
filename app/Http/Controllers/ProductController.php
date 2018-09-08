<?php

namespace App\Http\Controllers;

use App\Merchant;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\MerchantCollection as MerchantCollectionResource;


class ProductController extends Controller
{
    public function list(Request $request) {
        try {

            $products = Product::all();
            return response()->json(["status" => true, "data" => $products]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

    public function listByMerchant(Request $request,$id) {
        try {

            $products = Product::where('merchant_id',$id)->get();
            return response()->json(["status" => true, "data" => $products]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

    public function add(Request $request) {
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = new Product();
            $product->name = $name;
            $product->price = $price;
            $product->merchant_id = $merchant_id;

            //Upload Image

            $path = $request->file("picture")->store("products");
            $product->imagen = $path;

            $product->save();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Agregado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }


    public function update(Request $request,$id){
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = Product::find($id);
            $product->name = $name;
            $product->price = $price;
            $product->merchant_id = $merchant_id;

            //Upload Image
            //Y si no manda imagen?
            if($request->file("picture")!=null){
                $path = $request->file("picture")->store("products");
                $product->imagen = $path;
            }

            $product->save();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Agregado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }


    public function delete(Request $request,$id){
        try {
            $data = $request->all();
            extract($data);

            DB::beginTransaction();
            $product = Product::find($id);
            $product->delete();
            DB::commit();
            return response()->json(["status" => true, "message" => "Producto Agregado"]);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return response()->json(["status" => false, "error" => "Ocurrio un error interno, vuelva a internar o comuniquese a sistemas"], 500);
        }
    }

}

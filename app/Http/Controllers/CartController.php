<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
  public function index()
    {
        $cart = Cart::all();
        return response()->json($cart);
    }
  public function add(Request $request)
  {
    // find product in cart
    $product_in_cart = Cart::where('product', $request->product)->first();

    if(!$product_in_cart)
    {
    $product_in_cart = Cart::where('product', $request->product)->get();
    // add new cart
    $product_info = Product::where('product', $request->product)->get(['unit','price']);

    foreach($product_info as $record){
        $unit = $record->unit;
        $price = $record->price;
    }

        $qty = $request->qty;
        $total = $qty * $price;

        $request->request->add(['unit' => $unit]); //add request
        $request->request->add(['price' => $price]); //add request
        $request->request->add(['qty' => $qty]); //add request
        $request->request->add(['total' => $total]); //add request
        $data = $request->all();
        $cart = Cart::create($data);
        
        return response()->json($cart);

    }else{

        $product_in_cart = Cart::where('product', $request->product)->get();
        //update cart
        $product_info = Product::where('product', $request->product)->get(['unit','price']);

        foreach($product_info as $record){
            $unit = $record->unit;
            $price = $record->price;
        }

        foreach($product_in_cart as $record){
            $cart_id = $record->id;
            $cart_qty = $record->qty;
            $cart_total = $record->total;
        }

        $last_qty = $cart_qty;
        $qty = $request->qty;
        $update_qty=$last_qty+$qty;
        $last_total = $cart_total;
        $total = $qty * $price;
        $update_total = $last_total + $total;

        $request->request->add(['unit' => $unit]); //add request
        $request->request->add(['price' => $price]); //add request
        $request->request->add(['qty' => $update_qty]); //add request
        $request->request->add(['total' => $update_total]); //add request
        $data = $request->all();

        $cart =  Cart::whereId($cart_id)->update($data); 
        $cart =  Cart::whereId($cart_id)->get();
        
        return response()->json($cart);
    }
  
    

  }

  public function show($id)
    {
        $cart = Cart::find($id);
        return response()->json($cart);
    }
 
  public function remove(Request $request)
    {
        $product_in_cart = Cart::where('product', $request->product)->first();
        
        if (!$product_in_cart) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $product_in_cart = Cart::where('product', $request->product)->get();
         //update cart
         $product_info = Product::where('product', $request->product)->get(['unit','price']);

         foreach($product_info as $record){
             $unit = $record->unit;
             $price = $record->price;
         }
 
         foreach($product_in_cart as $record){
             $cart_id = $record->id;
             $cart_qty = $record->qty;
             $cart_total = $record->total;
         }
 
         $last_qty = $cart_qty;
         $qty = $request->qty;
         $update_qty=$last_qty-$qty;
         $last_total = $cart_total;
         $total = $qty * $price;
         $update_total = $last_total - $total;

         if ($update_qty>0){

            $request->request->add(['unit' => $unit]); //add request
            $request->request->add(['price' => $price]); //add request
            $request->request->add(['qty' => $update_qty]); //add request
            $request->request->add(['total' => $update_total]); //add request
            $data = $request->all();
    
            $cart =  Cart::whereId($cart_id)->update($data); 
            $cart =  Cart::whereId($cart_id)->get();
         }else{
            //remove cart
            $cart =  Cart::whereId($cart_id)->delete();

            return response()->json(['message' => 'Data deleted successfully'], 200);
         }
 
    
        
        return response()->json($cart);
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);
        
        if (!$cart) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }

} 


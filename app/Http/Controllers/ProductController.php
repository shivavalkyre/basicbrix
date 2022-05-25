<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
  public function index(Request $request)
    {
        $pagination =isset($request->pagination)? intval($request->pagination) : 10;

        $product = Product::orderBy('product','ASC')->orderBy('price','ASC')->paginate($pagination);
        return response()->json($product);
        
    }
  public function create(Request $request)
  {
    $data = $request->all();
    $product = Product::create($data);

    return response()->json($product);
  }

  public function show($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }
 
  public function update(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        
        $this->validate($request, [
            "product" => "required",
            "unit" => "required",
            "qty" => "required",
            "price" => "required"
        ]);

        $data = $request->all();
        $product->fill($data);
        $product->save();

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }

} 


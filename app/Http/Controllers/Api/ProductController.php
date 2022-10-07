<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){
        $products = Product::paginate(10,[ 'name','description','type']);
        return response()->json(
            $products
        );
    }

    public function store(Request $request){
        $file = null;
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',
            'description' => 'required|max:250',
            'file' => 'image|max:500000'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(
                $errors
            );
         }
         else{
            if ($request->hasFile('file')) {
                $image = Storage::disk('public')->put('images/', $request->file);
                $image = basename($image);
                $file = 'images/'. $image;
            }

            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'file' => $file
            ]);
            return response()->json(

            "name => $product->name
            description => $product->description,
            type => $product->type",
            );
         }

    }
    public function show($id){
      $product = Product::find($id);
      return response()->json(
        "name => $product->name,
        description => $product->description,
        type => $product->type,
        image =>storage/app/public/ $product->file",
    );
    }
}

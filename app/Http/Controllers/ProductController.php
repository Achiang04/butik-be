<?php

namespace App\Http\Controllers;

use App\Models\Product;
use \Cviebrock\EloquentSluggable\Services\SlugService; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return $products;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            // 'image' => 'image|file|max:1024',
            'category_id' => 'required',
            'stock' => 'numeric',
            'price' => 'numeric',
        ]);

        // if($request->file('image')){
        //     $validateData['image'] = $request->file('image')->store('post-images');
        // }

        $validateData['user_id'] = $request->user()->id;
        $validateData['slug'] = SlugService::createSlug(Product::class, 'slug', $validateData['name']);
        $validateData['image'] = '';

        Product::create($validateData);
        
        return [
            'message' => 'Products has been uploaded'
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'name' => 'required',
            // 'image' => 'image|file|max:1024',
            'stock' => 'numeric',
            'price' => 'numeric',
        ];

        $validateData = $request->validate($rules);
        $validateData['user_id'] = $request->user()->id;
        $validateData['slug'] = SlugService::createSlug(Product::class, 'slug', $validateData['name']);
        $validateData['image'] = '';

        // if($request->file('image')){
        //     $validateData['image'] = $request->file('image')->store('post-images');
        // }

        if($request->file('image')){
            if ($product->image) {
                Storage::delete($product->image);
            }
            $validateData['image'] = $request->file('image')->store('product-images');
        }

        Product::where('id', $product->id)
               ->update($validateData);
        
        return [
            'message' => 'Products has been updated'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }

        Product::destroy($product->id);

        return [
            'message' => 'Product has been deleted'
        ];
    }
}

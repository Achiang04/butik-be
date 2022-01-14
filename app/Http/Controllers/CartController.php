<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $carts = Cart::where('user_id', $request->user()->id);
        return [
            'carts' => $carts
        ];
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
            'product_slug' => 'required'
        ]); 

        $product = Product::where('slug', $validateData['product_slug'])->first(); 

        $carts = Cart::where('user_id', $request->user()->id)
                     ->where('product_id', $product->id)
                     ->first(); 

        if ($carts) {  
            Cart::where('id', $carts->id)
                ->update([
                    'qty' => ++$carts->qty
                ]);
        } else { 
            Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'qty' => 1
            ]);
        }
        
        return [
            'message' => 'Product has been added to cart'
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validateData = $request->validate([
            'product_slug' => 'required'
        ]); 
        
        $product = Product::where('slug', $validateData['product_slug'])->first(); 

        $cart = Cart::where('user_id', $request->user()->id)
                    ->where('product_id', $product->id)
                    ->first();

        Cart::destroy($cart->id);

        return [
            'message' => 'Cart has been deleted'
        ];
    }

    public function clear(Request $request)
    {
        Cart::where('user_id',$request->user()->id)->delete();

        return [
            'message' => 'Cart has been cleared'
        ];
        
    }

    public function check(Request $request)
    {
        Cart::where($request->id)->update([
            'checked' => true
        ]);

        return [
            'message' => 'Cart has been checked'
        ];

    }

    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', $request->user()->id)
            ->where('selected', true)
            ->get();

        foreach ($carts as $cart) {
            Order::create([
                'user_id' => $request->user()->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty
            ]);
        };

        return [
            'message' => 'Cart has been checked'
        ];

    }

}

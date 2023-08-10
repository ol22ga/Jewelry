<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $order = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'новый')
            ->firstOrNew(['user_id'=>Auth::id()], ['status'=>'новый']);

        $carts = Cart::query()
            ->where('order_id', $order->id)
            ->with('product', 'order')
            ->get();

        $carts_all = Cart::query()
            ->with('product', 'shop')
            ->get();

        return response()->json([
            'carts' => $carts,
            'order' => $order,
            'carts_all' => $carts_all
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $product = Product::query()->where('id', $request->id)->first();

        $order = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'новый')
            ->firstOrCreate(['user_id'=>Auth::id()], ['status'=>'новый']);

        $cart = Cart::query()
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->firstOrCreate(['order_id'=>$order->id], ['product_id'=>$product->id]);

        $product_and_shop = ProductShop::query()
            ->where('product_id', $product->id)
            ->where('count', '>', $cart->count)
            ->get();


        if ($cart->count) {
            if (count($product_and_shop) !== 0) {
                $cart->count += 1;
                $cart->price += $product->price;
                $order->price += $product->price;

                $order->save();
                $cart->save();

                return response()->json('Товар добавлен в корзину', 200);
            } else {
                return response()->json('Такое кол-ва товара недоступно для заказа', 400);
            }
        } else {
            $cart->count = 1;
            $cart->price = $product->price;
            $order->price += $cart->price;

            $order->save();
            $cart->save();
            return response()->json('Товар добавлен в корзину', 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        $order = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'новый')
            ->first();

        $carts = Cart::query()
            ->where('order_id', $order->id)
            ->with('product')
            ->get();

        $cart_with_shops = Cart::crossJoin('product_shops', 'product_shops.product_id', '=', 'carts.product_id')
            ->crossJoin('shops', 'shops.id', '=', 'product_shops.shop_id')
            ->select('carts.*', 'product_shops.shop_id', 'shops.title')
            ->get();

        return response()->json([
            'cart_with_shops' => $cart_with_shops
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart, Request $request)
    {
        $product = Product::query()->where('id', $request->id)->first();

        $order = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'новый')
            ->first();

        $cart = Cart::query()
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->first();

        $product_and_shop = ProductShop::query()
            ->where('product_id', $request->id)
            ->where('count', '>', $cart->count)
            ->get();

        if ($cart->count > 1) {
            $cart->count -= 1;
            $cart->price -= $product->price;
            $order->price -= $product->price;

            $order->update();
            $cart->update();
        } else {
            $order->price -= $product->price;

            $order->update();
            $cart->delete();
        }
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
    public function destroy(Cart $cart, Request $request)
    {
        $order = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', 'новый')
            ->first();

        $cart = Cart::query()
            ->where('id', $request->id)
            ->first();

        $order->price -= $cart->price;

        $order->update();
        $cart->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderPart;
use App\Models\Product;
use App\Models\ProductShop;
use App\Models\Shop;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders_user = Order::query()
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'новый')
            ->with('user')
            ->with('carts.product')
            ->get();

        $orders_all = Order::query()
            ->where('status', '!=', 'новый')
            ->with('user')
            ->with('carts.product')
            ->get();

        return response()->json([
            'orders_user' => $orders_user,
            'orders_all' => $orders_all
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'password'=>['required']
        ],[
            'password.required'=>'Обязательное поле'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = User::query()
            ->where('id', Auth::id())
            ->first();

        if ($user->password === md5($request->password)) {
            $order = Order::query()
                ->where('user_id', Auth::id())
                ->where('status', 'новый')
                ->first();

            $carts = Cart::query()
                ->where('order_id', $order->id)
                ->with('product')
                ->get();

            foreach ($carts as $key => $value) {
                $str = (string)('shop_id_' . $value->id);

                $value->shop_id = $request->$str;
                $value->update();
            }

            $order->status = 'в обработке';
            $order->update();
            return redirect()->route('CatalogPage');
        } else {
            return response()->json('Пароль неверный', 403);
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order, Request $request)
    {
        $order = Order::query()
            ->where('id', $request->id)
            ->first();

        $order->status = 'отклонен';
        $order->comment = $request->comment;

        $order->update();

        return response()->json('Заказ отклонен', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order = Order::query()
            ->where('id', $request->id)
            ->first();

        $carts = Cart::query()
            ->where('order_id', $order->id)
            ->with('shop')
            ->get();

        foreach ($carts as $cart) {
            $product = Product::query()
                ->where('id', $cart->product_id)
                ->first();

            $shop = ProductShop::query()
                ->where('shop_id', $cart->shop_id)
                ->where('product_id', $cart->product_id)
                ->first();

            if ($cart->count <= $shop->count) {
                $shop->count -= $cart->count;
                $shop->update();
            } else {
                return response()->json('В магазине не хватает товара "' . $product->title . '". Невозможно одобрить заказ. ', 400);
            }

            $product->purchase += $cart->count;
            $product->update();

            $order->status = 'одобрен';
            $order->update();

            return response()->json('Заказ одобрен', 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}

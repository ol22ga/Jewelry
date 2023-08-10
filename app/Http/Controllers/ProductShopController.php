<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products_and_shops = ProductShop::with('product', 'shop')->get();
        return response()->json([
            'all' => $products_and_shops
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductShop  $productShop
     * @return \Illuminate\Http\Response
     */
    public function show(ProductShop $productShop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductShop  $productShop
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductShop $productShop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductShop  $productShop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductShop $productShop)
    {
        $validate = Validator::make($request->all(), [
            'count' => ['required', 'numeric', 'between:0,500']
        ],[
            'count.required' => 'Обязательное поле',
            'price.numeric' => 'Тип данных - числовой',
            'price.between' => 'Разрешенный диапазон цены от 0 до 999999'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $check = ProductShop::query()->where('product_id', $request->product_id)->where('shop_id', $request->shop_id)->first();

        if ($check == null) {
            $product_and_shop = new ProductShop();

            $product_and_shop->product_id = $request->product_id;
            $product_and_shop->shop_id = $request->shop_id;
            $product_and_shop->count = $request->count;

            $product_and_shop->save();
        } else {
            $product_and_shop = $check;

            $product_and_shop->product_id = $request->product_id;
            $product_and_shop->shop_id = $request->shop_id;
            $product_and_shop->count = $request->count;

            $product_and_shop->update();
        }

        return redirect()->route('ProductPage');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductShop  $productShop
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductShop $productShop)
    {
        //
    }
}

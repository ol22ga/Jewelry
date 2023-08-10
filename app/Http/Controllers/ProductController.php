<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::query()
            ->with('brand', 'category_type', 'category_gender', 'category_metal', 'category_jewel')
            ->withSum('product_shops', 'count')
            ->withCount('product_shops')
            ->get();

        $products_with_shops = Product::crossJoin('product_shops', 'product_shops.product_id', '=', 'products.id')
            ->select('products.*', 'product_shops.shop_id')
            ->with('brand', 'category_type', 'category_gender', 'category_metal', 'category_jewel')
            ->withSum('product_shops', 'count')
            ->withCount('product_shops')
            ->get();

        return response()->json([
            'all' => $products,
            'products_with_shops' => $products_with_shops,
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
            'title' => ['required'],
            'img' => ['required', 'mimes:png,jpg,jpeg,bmb'],
            'weight' => ['required', 'regex:/^((0|[1-9]\d*)(.\d+)?)|-$/'],
            'description' => ['required', 'regex:/[А-Яа-яЁёA-Za-z0-9]/u'],
            'price' => ['required', 'numeric', 'between:1,999999'],

        ],[
            'title.required' => 'Обязательное поле',
            'img.required' => 'Обязательное поле',
            'img.mimes' => 'Разрешенные форматы: png,jpg,jpeg,bmb',
            'weight.required' => 'Обязательное поле',
            'weight.regex' => 'Поле может содержать только цифры и точку',
            'description.required' => 'Обязательное поле',
            'description.regex' => 'Поле может содержать только кириллицу, латиницу и цифры',
            'price.required' => 'Обязательное поле',
            'price.numeric' => 'Тип данных - числовой',
            'price.between' => 'Разрешенный диапазон цены от 1 до 999999',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $path_img = '';
        if ($request->file()) {
            $request->file('img')->store('/public/img/products');
            $path_img = $request->file('img')->store('/img/products');
        }

        $product = new Product();
        $product->category_type_id = $request->category_type_id;
        $product->brand_id = $request->brand_id;
        $product->title = $request->title;
        $product->category_gender_id = $request->category_gender_id;
        $product->category_metal_id = $request->category_metal_id;
        $product->category_jewel_id = $request->category_jewel_id;
        $product->weight = $request->weight;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->img = '/public/storage/' . $path_img;

        $product->save();
        return redirect()->route('ProductPage');
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, Request $request)
    {
        $product = Product::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(),[
            'title' => ['required'],
            'img' => ['mimes:png,jpg,jpeg,bmb'],
            'weight' => ['required', 'regex:/^((0|[1-9]\d*)(.\d+)?)|-$/'],
            'description' => ['required', 'regex:/[А-Яа-яЁёA-Za-z0-9]/u'],
            'price' => ['required', 'numeric', 'between:1,999999'],
        ],[
            'title.required' => 'Обязательное поле',
            'img.mimes' => 'Разрешенные форматы: png,jpg,jpeg,bmb',
            'weight.required' => 'Обязательное поле',
            'weight.regex' => 'Поле может содержать только цифры и точку',
            'description.required' => 'Обязательное поле',
            'description.regex' => 'Поле может содержать только кириллицу, латиницу и цифры',
            'price.required' => 'Обязательное поле',
            'price.numeric' => 'Тип данных - дата',
            'price.between' => 'Разрешенный диапазон цены от 1 до 999999',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $product->category_type_id = $request->category_type_id;
        $product->brand_id = $request->brand_id;
        $product->title = $request->title;
        $product->category_gender_id = $request->category_gender_id;
        $product->category_metal_id = $request->category_metal_id;
        $product->category_jewel_id = $request->category_jewel_id;
        $product->weight = $request->weight;
        $product->description = $request->description;
        $product->price = $request->price;

        $path_img = '';
        if ($request->file()) {
            $request->file('img')->store('/public/img/products');
            $path_img = $request->file('img')->store('/img/products');
            $product->img = '/public/storage/' . $path_img;
        }

        $product->update();
        return redirect()->route('ProductPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Request $request)
    {
        $product = Product::query()->where('id', $request->id)->delete();
        return redirect()->route('ProductPage');
    }
}

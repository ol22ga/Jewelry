<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Shop::all();
        return response()->json([
            'all' => $all
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z0-9]/u', 'unique:shops'],
            'address' => ['required'],
            'time_start' => ['required'],
            'time_end' => ['required'],
            'days' => ['required', 'regex:/[А-Яа-яЁё]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу, латиницу и цифры',
            'title.unique' => 'Магазин уже существует',
            'address.required' => 'Обязательное поле',
            'time_start.required' => 'Обязательное поле',
            'time_end.required' => 'Обязательное поле',
            'days.required' => 'Обязательное поле',
            'days.regex' => 'Поле может содержать только кириллицу'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $shop = new Shop();
        $shop->title = $request->title;
        $shop->address = $request->address;
        $shop->time_start = $request->time_start;
        $shop->time_end = $request->time_end;
        $shop->days = $request->days;
        $shop->save();

        return redirect()->route('ShopPage');
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
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(Shop $shop, Request $request)
    {
        $shop = Shop::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(), [
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z0-9]/u'],
            'address' => ['required'],
            'time_start' => ['required'],
            'time_end' => ['required'],
            'days' => ['required', 'regex:/[А-Яа-яЁё]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу, латиницу и цифры',
            'address.required' => 'Обязательное поле',
            'time_start.required' => 'Обязательное поле',
            'time_end.required' => 'Обязательное поле',
            'days.required' => 'Обязательное поле',
            'days.regex' => 'Поле может содержать только кириллицу'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        if (mb_strtolower($shop->title) !== mb_strtolower($request->title)) {
            $validate = Validator::make($request->all(), [
                'title' => ['unique:shops'],
            ],[
                'title.unique' => 'Магазин уже существует'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }
        }

        $shop->title = $request->title;
        $shop->address = $request->address;
        $shop->time_start = $request->time_start;
        $shop->time_end = $request->time_end;
        $shop->days = $request->days;
        $shop->update();

        return redirect()->route('ShopPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shop $shop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $shop, Request $request)
    {
        $shop = Shop::query()->where('id', $request->id)->delete();
        return redirect()->route('ShopPage');
    }
}

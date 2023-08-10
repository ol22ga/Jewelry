<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = Brand::all();
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
        $validate = Validator::make($request->all(),[
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u', 'unique:brands'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'title.unique' => 'Бренд уже существует',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $brand = new Brand();
        $brand->title = $request->title;
        $brand->save();

        return redirect()->route('BrandPage');
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
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand, Request $request)
    {
        $brand = Brand::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(),[
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

         if (mb_strtolower($brand->title) !== mb_strtolower($request->title)) {
             $validate = Validator::make($request->all(), [
                 'title' => ['unique:brands'],
             ],[
                 'title.unique' => 'Бренд уже существует'
             ]);

             if ($validate->fails()) {
                 return response()->json($validate->errors(), 400);
             }
         }

        $brand->title = $request->title;
        $brand->update();

        return redirect()->route('BrandPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand, Request $request)
    {
        $brand = Brand::query()->where('id', $request->id)->delete();
        return redirect()->route('BrandPage');
    }
}

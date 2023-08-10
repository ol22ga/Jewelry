<?php

namespace App\Http\Controllers;

use App\Models\CategoryJewel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryJewelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = CategoryJewel::all();
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
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u', 'unique:category_jewels'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'title.unique' => 'Драгоценность уже существует',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $jewel = new CategoryJewel();
        $jewel->title = $request->title;
        $jewel->save();

        return redirect()->route('JewelPage');
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
     * @param  \App\Models\CategoryJewel  $categoryJewel
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryJewel $categoryJewel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryJewel  $categoryJewel
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryJewel $categoryJewel, Request $request)
    {
        $jewel = CategoryJewel::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(), [
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        if (mb_strtolower($jewel->title) !== mb_strtolower($request->title)) {
            $validate = Validator::make($request->all(), [
                'title' => ['unique:category_jewels'],
            ],[
                'title.unique' => 'Драгоценность уже существует'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }
        }

        $jewel->title = $request->title;
        $jewel->update();

        return redirect()->route('JewelPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryJewel  $categoryJewel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryJewel $categoryJewel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryJewel  $categoryJewel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryJewel $categoryJewel, Request $request)
    {
        $jewel = CategoryJewel::query()->where('id', $request->id)->delete();
        return redirect()->route('JewelPage');
    }
}

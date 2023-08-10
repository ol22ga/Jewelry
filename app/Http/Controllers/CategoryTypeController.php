<?php

namespace App\Http\Controllers;

use App\Models\CategoryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = CategoryType::all();
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
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u', 'unique:category_types'],
            'img' => ['required', 'mimes:png,jpg,jpeg,bmb'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'title.unique' => 'Тип уже существует',
            'img.required' => 'Обязательное поле',
            'img.mimes' => 'Разрешенные форматы: png,jpg,jpeg,bmb',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $path_img = '';
        if ($request->file()) {
            $request->file('img')->store('/public/img/types');
            $path_img = $request->file('img')->store('/img/types');
        }

        $type = new CategoryType();
        $type->title = $request->title;
        $type->img = '/public/storage/' . $path_img;
        $type->save();

        return redirect()->route('TypePage');
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
     * @param  \App\Models\CategoryType  $categoryType
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryType $categoryType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryType  $categoryType
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryType $categoryType, Request $request)
    {
        $type = CategoryType::query()->where('id', $request->id)->first();

        if (mb_strtolower($type->title) !== mb_strtolower($request->title)) {
            $validate_1 = Validator::make($request->all(), [
                'title' => ['unique:category_types'],
            ],[
                'title.unique' => 'Тип уже существует',
            ]);

            if ($validate_1->fails()) {
                return response()->json($validate_1->errors(), 400);
            }
        }

        $validate_2 = Validator::make($request->all(), [
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u'],
            'img' => ['mimes:png,jpg,jpeg,bmb'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'img.mimes' => 'Разрешенные форматы: png,jpg,jpeg,bmb',
        ]);

        if ($validate_2->fails()) {
            return response()->json($validate_2->errors(), 400);
        }

        $type->title = $request->title;

        $path_img = '';
        if ($request->file()) {
            $request->file('img')->store('/public/img/types');
            $path_img = $request->file('img')->store('/img/types');
            $type->img = '/public/storage/' . $path_img;
        }

        $type->update();

        return redirect()->route('TypePage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryType  $categoryType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryType $categoryType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryType  $categoryType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryType $categoryType, Request $request)
    {
        $type = CategoryType::query()->where('id', $request->id)->delete();
        return redirect()->route('TypePage');

    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CategoryMetal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryMetalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = CategoryMetal::all();
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
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u', 'unique:category_metals'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'title.unique' => 'Металл уже существует',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $metal = new CategoryMetal();
        $metal->title = $request->title;
        $metal->save();

        return redirect()->route('MetalPage');
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
     * @param  \App\Models\CategoryMetal  $categoryMetal
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryMetal $categoryMetal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryMetal  $categoryMetal
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryMetal $categoryMetal, Request $request)
    {
        $metal = CategoryMetal::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(), [
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        if (mb_strtolower($metal->title) !== mb_strtolower($request->title)) {
            $validate = Validator::make($request->all(), [
                'title' => ['unique:category_metals'],
            ],[
                'title.unique' => 'Металл уже существует'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }
        }

        $metal->title = $request->title;
        $metal->update();

        return redirect()->route('MetalPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryMetal  $categoryMetal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryMetal $categoryMetal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryMetal  $categoryMetal
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryMetal $categoryMetal, Request $request)
    {
        $metal = CategoryMetal::query()->where('id', $request->id)->delete();
        return redirect()->route('MetalPage');
    }
}

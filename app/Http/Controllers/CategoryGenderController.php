<?php

namespace App\Http\Controllers;

use App\Models\CategoryGender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryGenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = CategoryGender::all();
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
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u', 'unique:category_genders'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
            'title.unique' => 'Пол уже существует',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $gender = new CategoryGender();
        $gender->title = $request->title;
        $gender->save();

        return redirect()->route('GenderPage');
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
     * @param  \App\Models\CategoryGender  $categoryGender
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryGender $categoryGender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoryGender  $categoryGender
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryGender $categoryGender, Request $request)
    {
        $gender = CategoryGender::query()->where('id', $request->id)->first();

        $validate = Validator::make($request->all(),[
            'title' => ['required', 'regex:/[А-Яа-яЁёA-Za-z]/u'],
        ],[
            'title.required' => 'Обязательное поле',
            'title.regex' => 'Поле может содержать только кириллицу и латиницу',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        if (mb_strtolower($gender->title) !== mb_strtolower($request->title)) {
            $validate = Validator::make($request->all(), [
                'title' => ['unique:category_genders'],
            ],[
                'title.unique' => 'Пол уже существует'
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }
        }

        $gender->title = $request->title;
        $gender->update();

        return redirect()->route('GenderPage');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryGender  $categoryGender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryGender $categoryGender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryGender  $categoryGender
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryGender $categoryGender, Request $request)
    {
        $gender = CategoryGender::query()->where('id', $request->id)->delete();
        return redirect()->route('GenderPage');
    }
}

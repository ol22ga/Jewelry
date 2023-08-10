<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function Registration(Request $request) {
        $validate = Validator::make($request->all(), [
            'fio' => ['required', 'regex:/[А-Яа-яЁё-]/u'],
            'birthday' => ['required', 'date'],
            'phone' => ['required', 'regex:/[0-9]{11}/u',  'numeric'],
            'email' => ['required', 'email:frs', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'rules'=> ['required']
        ],[
            'fio.required' => 'Обязательное поле',
            'fio.regex' => 'Может содержать только кириллицу, пробел и тире',
            'birthday.required' => 'Обязательное поле',
            'birthday.date' => 'Тип данных - дата',
            'phone.required' => 'Обязательное поле',
            'phone.numeric' => 'Тип данных - числовой',
            'phone.regex' => 'Разрешенный формат: 7(8)1234567890',
            'email.required'=>'Обязательное поле',
            'email.email'=>'Поле должно содержать адрес электронной почты',
            'email.unique'=>'Пользователь с указанным адресом электронной почты уже зарегистрирован',
            'password.required'=>'Обязательное поле',
            'password.min'=>'Минимальная длина пароля 6 симоволов',
            'password.confirmed'=>'Пароли не совпадают',
            'rules.required'=>'Поставьте галочку для согласие обработки персональных данных',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = new User();
        $user->fio = $request->fio;
        $user->birthday = $request->birthday;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = md5($request->password);

        $user->save();
        return redirect()->route('login');
    }



    public function Authorization(Request $request) {
        $validate = Validator::make($request->all(), [
            'email'=>['required'],
            'password'=>['required']
        ],[
            'email.required'=>'Обязательное поле',
            'password.required'=>'Обязательное поле'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $user = User::query()
            ->where('email', $request->email)
            ->where('password', md5($request->password))
            ->first();

        if ($user) {
            Auth::login($user);
            return redirect()->route('MainPage');
//            if ($user->role == 'user') {
//                return redirect()->route('UserPage');
//            }
//            if ($user->role == 'admin') {
//                return redirect()->route('AdminPage');
//            }
        } else {
            return response()->json('Неверный логин или пароль', 403);
        }
    }



    public function Exit() {
        Auth::logout();
        return redirect()->route('login');
    }
}

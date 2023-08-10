<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CategoryGender;
use App\Models\CategoryJewel;
use App\Models\CategoryMetal;
use App\Models\CategoryType;
use App\Models\Product;
use App\Models\ProductShop;
use App\Models\Shop;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //---ОБЩИЕ СТРАНИЦЫ
    public function MainPage() {
        $new_products = Product::query()
            ->orderByDesc('created_at')->limit(5)
            ->get();

        $products = Product::query()
            ->orderBy('purchase')
            ->get();

        $bestsellers = Product::query()
            ->orderBy('purchase')
            ->limit(4)
            ->get();

        $types = CategoryType::all();

//        $popular_categories = [];
//
//        foreach ($products as $key => $value) {
//            if (count($popular_categories) < 4) {
//                $category = CategoryType::query()
//                    ->where('id', $value->category_type_id)
//                    ->first();
//                array_push($popular_categories, $category);
//                array_unique($popular_categories);
//            }
//        }

        return view('welcome', ['types'=>$types, 'new_products'=>$new_products, 'bestsellers'=>$bestsellers]);
    }
    public function CatalogPage() {
        return view('catalog');
    }
    public function ShopsPage() {
        return view('shops');
    }
    public function ResultsPage() {
        return view('search');
    }

    //---СТРАНИЦЫ ГОСТЯ
    public function RegistrationPage() {
        return view('guest.registration');
    }
    public function AuthorizationPage() {
        return view('guest.authorization');
    }

    //---СТРАНИЦЫ ПОЛЬЗОВАТЕЛЯ
    public function CartPage() {
        return view('user.cart');
    }
    public function MyOrderPage() {
        return view('user.orders');
    }

    //---СТРАНИЦЫ АДМИНИСТРАТОРА
    public function TypePage() {
        return view('admin.categories.types');
    }
    public function GenderPage() {
        return view('admin.categories.genders');
    }
    public function JewelPage() {
        return view('admin.categories.jewels');
    }
    public function MetalPage() {
        return view('admin.categories.metals');
    }
    public function BrandPage() {
        return view('admin.brands');
    }
    public function ShopPage() {
        return view('admin.shops');
    }
    public function ProductPage() {
        return view('admin.products');
    }
    public function OrderPage() {
        return view('admin.orders');
    }
}

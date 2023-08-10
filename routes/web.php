<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryGenderController;
use App\Http\Controllers\CategoryJewelController;
use App\Http\Controllers\CategoryMetalController;
use App\Http\Controllers\CategoryTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductShopController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//--------------------------------------------------СТРАНИЦЫ--------------------------------------------------
Route::get('/', [PageController::class, 'MainPage'])->name('MainPage');

Route::get('/authorization', [PageController::class, 'AuthorizationPage'])->name('login');
Route::get('/registration', [PageController::class, 'RegistrationPage'])->name('RegistrationPage');

Route::get('/catalog', [PageController::class, 'CatalogPage'])->name('CatalogPage');
Route::get('/shops', [PageController::class, 'ShopsPage'])->name('ShopsPage');
Route::get('/results', [PageController::class, 'ResultsPage'])->name('ResultsPage');

Route::get('/cart', [PageController::class, 'CartPage'])->name('CartPage');
Route::get('/order', [PageController::class, 'MyOrderPage'])->name('MyOrderPage');




//--------------------------------------------------ФУНКЦИИ--------------------------------------------------
//---аккаунт
Route::post('/registration/save', [UserController::class, 'Registration'])->name('Registration');
Route::post('/authorization/entry', [UserController::class, 'Authorization'])->name('Authorization');
Route::get('/exit', [UserController::class, 'Exit'])->name('Exit');
//---получение данных
Route::get('/categories/type/get', [CategoryTypeController::class, 'index'])->name('TypeGet');
Route::get('/categories/gender/get', [CategoryGenderController::class, 'index'])->name('GenderGet');
Route::get('/categories/jewel/get', [CategoryJewelController::class, 'index'])->name('JewelGet');
Route::get('/categories/metal/get', [CategoryMetalController::class, 'index'])->name('MetalGet');
Route::get('/brand/get', [BrandController::class, 'index'])->name('BrandGet');
Route::get('/shop/get', [ShopController::class, 'index'])->name('ShopGet');
Route::get('/product/get', [ProductController::class, 'index'])->name('ProductGet');
Route::get('/available/products/shops/get', [ProductShopController::class, 'index'])->name('GetProductsAndShops');
Route::get('/cart/get', [CartController::class, 'index'])->name('CartGet');
Route::get('/available/carts/shops/get', [CartController::class, 'show'])->name('GetCartsAndShops');
Route::get('/order/get', [OrderController::class, 'index'])->name('OrderGet');
//---корзина
Route::post('/cart/add', [CartController::class, 'create'])->name('CartAdd');
Route::post('/cart/decrement', [CartController::class, 'edit'])->name('CartDecrement');
Route::post('/cart/delete', [CartController::class, 'destroy'])->name('CartDelete');
//---заказ
Route::post('/order/arrange', [OrderController::class, 'create'])->name('OrderArrange');



//--------------------------------------------------АДМИНКА--------------------------------------------------
Route::group(['middleware'=>['auth', 'admin'], 'prefix'=>'xxxxxx/admin'], function () {
    //---тип ювелирных изделий
    Route::get('/categories/type', [PageController::class, 'TypePage'])->name('TypePage');
    Route::post('/categories/type/add', [CategoryTypeController::class, 'create'])->name('TypeAdd');
    Route::post('/categories/type/delete', [CategoryTypeController::class, 'destroy'])->name('TypeDelete');
    Route::post('/categories/type/edit', [CategoryTypeController::class, 'edit'])->name('TypeEdit');
    //---пол
    Route::get('/categories/gender', [PageController::class, 'GenderPage'])->name('GenderPage');
    Route::post('/categories/gender/add', [CategoryGenderController::class, 'create'])->name('GenderAdd');
    Route::post('/categories/gender/delete', [CategoryGenderController::class, 'destroy'])->name('GenderDelete');
    Route::post('/categories/gender/edit', [CategoryGenderController::class, 'edit'])->name('GenderEdit');
    //---драгоценность
    Route::get('/categories/jewel', [PageController::class, 'JewelPage'])->name('JewelPage');
    Route::post('/categories/jewel/add', [CategoryJewelController::class, 'create'])->name('JewelAdd');
    Route::post('/categories/jewel/delete', [CategoryJewelController::class, 'destroy'])->name('JewelDelete');
    Route::post('/categories/jewel/edit', [CategoryJewelController::class, 'edit'])->name('JewelEdit');
    //---металл
    Route::get('/categories/metal', [PageController::class, 'MetalPage'])->name('MetalPage');
    Route::post('/categories/metal/add', [CategoryMetalController::class, 'create'])->name('MetalAdd');
    Route::post('/categories/metal/delete', [CategoryMetalController::class, 'destroy'])->name('MetalDelete');
    Route::post('/categories/metal/edit', [CategoryMetalController::class, 'edit'])->name('MetalEdit');
    //---бренд
    Route::get('/brand', [PageController::class, 'BrandPage'])->name('BrandPage');
    Route::post('/brand/add', [BrandController::class, 'create'])->name('BrandAdd');
    Route::post('/brand/delete', [BrandController::class, 'destroy'])->name('BrandDelete');
    Route::post('/brand/edit', [BrandController::class, 'edit'])->name('BrandEdit');
    //---магазин
    Route::get('/shop', [PageController::class, 'ShopPage'])->name('ShopPage');
    Route::post('/shop/add', [ShopController::class, 'create'])->name('ShopAdd');
    Route::post('/shop/delete', [ShopController::class, 'destroy'])->name('ShopDelete');
    Route::post('/shop/edit', [ShopController::class, 'edit'])->name('ShopEdit');
    //---товар
    Route::get('/product', [PageController::class, 'ProductPage'])->name('ProductPage');
    Route::post('/product/add', [ProductController::class, 'create'])->name('ProductAdd');
    Route::post('/product/delete', [ProductController::class, 'destroy'])->name('ProductDelete');
    Route::post('/product/edit', [ProductController::class, 'edit'])->name('ProductEdit');
    //---наличие
    Route::post('/available/edit', [ProductShopController::class, 'update'])->name('AvailableEdit');
    //---заказы
    Route::get('/order', [PageController::class, 'OrderPage'])->name('OrderPage');
    Route::post('/order/approved', [OrderController::class, 'update'])->name('OrderApproved');
    Route::post('/order/rejected', [OrderController::class, 'edit'])->name('OrderRejected');
});

<?php


use Carbon\Carbon;
use App\Models\product;
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

Route::get('/', function () {
    return view('welcome');
});

route::get('time',function(){
    return date('Y-M-D  H:i:s');
});

Route::get('Get-All-Product' ,'product\productController@GetAllProduct2');

/*route::get('ahmad',function () {
    return view('test');
});*/


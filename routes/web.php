<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FirstController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
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

//Route::get('/', function () {
//    return view('welcome');
//})->name('home');

//client side
Route::get('/', [ClientController::class, 'client'])->name('clientindex');
//client side

Route::middleware('auth')->group(function () {
//about section    
Route::get('/adminhome', function () { return view('admin.index'); })->name('adminhome'); 
Route::get('/adminabout', [AboutController::class, 'about'])->name('adminabout');
Route::post('/addabout', [AboutController::class, 'store'])->name('about.store');
Route::get('/about/{id}/edit', [AboutController::class, 'edit'])->name('about.edit');
Route::post('/about/{id}/update', [AboutController::class, 'update'])->name('about.update');
Route::delete('/about/{id}/delete', [AboutController::class, 'delete'])->name('about.delete');
//about section
//first section
Route::get('/adminfirst', [FirstController::class, 'first'])->name('adminfirst');
Route::post('/addfirst', [FirstController::class, 'store'])->name('first.store');
Route::get('/first/{id}/edit', [FirstController::class, 'edit'])->name('first.edit');
Route::post('/first/{id}/update', [FirstController::class, 'update'])->name('first.update');
Route::delete('/first/{id}/delete', [FirstController::class, 'delete'])->name('first.delete');
//first section
//portfolio section
Route::get('/adminportfolio', [PortfolioController::class, 'portfolio'])->name('adminportfolio');
Route::post('/addportfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
Route::get('/portfolio/{id}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
Route::post('/portfolio/{id}/update', [PortfolioController::class, 'update'])->name('portfolio.update');
Route::delete('/portfolio/{id}/delete', [PortfolioController::class, 'delete'])->name('portfolio.delete');
//portfolio section

//team section
Route::get('/adminteam', [TeamController::class, 'team'])->name('adminteam');
Route::post('/addteam', [TeamController::class, 'store'])->name('team.store');
Route::get('/team/{id}/edit', [TeamController::class, 'edit'])->name('team.edit');
Route::post('/team/{id}/update', [TeamController::class, 'update'])->name('team.update');
Route::delete('/team/{id}/delete', [TeamController::class, 'delete'])->name('team.delete');
//team section

//team section
Route::get('/adminservice', [ServiceController::class, 'service'])->name('adminservice');
Route::post('/addservice', [ServiceController::class, 'store'])->name('service.store');
Route::get('/service/{id}/edit', [ServiceController::class, 'edit'])->name('service.edit');
Route::post('/service/{id}/update', [ServiceController::class, 'update'])->name('service.update');
Route::delete('/service/{id}/delete', [ServiceController::class, 'delete'])->name('service.delete');
//team section
});

Route::get('/admin/login', [AuthManager::class, 'adminlogin'])->name('login');
Route::post('/adminlogin', [AuthManager::class, 'adminloginPost'])->name('adminlogin.post');
Route::get('/adminlogout', [AuthManager::class, 'adminlogout'])->name('adminlogout');

/*
Route::get('/login', [AuthManager::class, 'login'])->name('login');
Route::post('/login', [AuthManager::class, 'loginPost'])->name('login.post');
Route::get('/register', [AuthManager::class, 'register'])->name('register');
Route::post('/register', [AuthManager::class, 'registerPost'])->name('register.post');
Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');
*/

<?php

use App\Http\Controllers\AdminDiscountController;
use App\Http\Controllers\DiscountCommentController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DiscountLikesController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UserDiscountController;
use App\Models\Category;
use App\Models\Discount;
use App\Services\Newsletter;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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


Route::get('/', [DiscountController::class, 'index'])->name('home');
Route::get('discounts', [DiscountController::class, 'onlyDiscounts'])->name('normal-discounts');
Route::get('/faq', function() {
   return view('faq', [
       'categories' => Category::all()
   ]);
});

Route::get('/contact', function() {
    return view('contact', [
        'categories' => Category::all()
    ]);
});

Route::get('discounts/{discount:slug}', [DiscountController::class, 'show']);

Route::post('newsletter', NewsletterController::class);

Route::get('/users/{user:username}', [ProfilesController::class, 'show'])
    ->name('profile');

Route::get('categories/{category:slug}', function (Category $category) {
    return view('discounts.normal-discounts', [
//        N+1 problem solved here too when clicking on Category (this needs to be added to a Controller for code cleanup & to avoid type of routes mixing)
        'discounts' => $category->discounts()->with(['category','comments'])->paginate(5),
        // Confused if it's better to use all() or with('discounts') when loading categories, when testing using all() = less queries and almost same ms time
        'categories' => Category::all()
    ]);
});

Route::middleware('auth')->group(function () {

    Route::post('discounts/{discount:slug}/comments', [DiscountCommentController::class, 'store']);

//    ROUTES FOR LIKES IN THE FUTURE
//    Route::post('/discounts/{discount}/like', [DiscountLikesController::class, 'store']);
//    Route::delete('/discounts/{discount}/like', [DiscountLikesController::class, 'destroy']);

    Route::get('user/discounts', [UserDiscountController::class, 'index']);
    Route::post('user/discounts', [UserDiscountController::class, 'store']);
    Route::get('user/discounts/create', [UserDiscountController::class, 'create']);
    Route::get('user/discounts/{discount}/edit', [UserDiscountController::class, 'edit']);
    Route::patch('user/discounts/{discount}', [UserDiscountController::class, 'update']);
    Route::delete('user/discounts/{discount}', [UserDiscountController::class, 'destroy']);


    Route::get('/users/{user:username}/edit',
        [ProfilesController::class, 'edit'])->middleware('can:edit,user');

    Route::patch('/users/{user:username}',
        [ProfilesController::class, 'update'])->middleware('can:edit,user');
});


Route::middleware('can:admin')->group(function () {
    Route::get('admin/discounts', [AdminDiscountController::class, 'index']);
    Route::post('admin/discounts', [AdminDiscountController::class, 'store']);
    Route::get('admin/discounts/create', [AdminDiscountController::class, 'create']);
    Route::get('admin/discounts/{discount}/edit', [AdminDiscountController::class, 'edit']);
    Route::patch('admin/discounts/{discount}', [AdminDiscountController::class, 'update']);
    Route::delete('admin/discounts/{discount}', [AdminDiscountController::class, 'destroy']);
});


require __DIR__.'/auth.php';

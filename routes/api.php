<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PromoCodesController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\SubCategoriesController;

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('refreshToken', 'refreshToken');

    Route::middleware('jwt.verify')->group(function () {
        Route::get('logout', 'logout');
    });
});

Route::prefix('categories')->controller(CategoriesController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-category/{id}','update');
        Route::delete('delete-category/{id}','destroy');
        Route::get('get-all-categories','index');
    });
});

Route::prefix('subCategories')->controller(SubCategoriesController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::put('update-subCategory/{id}','update');
        Route::delete('delete-subCategory','destroy');
        Route::get('get-all-subCategories/{categoryId}','index');
    });
});

Route::prefix('resources')->controller(ResourcesController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer|seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-resource','update');
        Route::delete('delete-resource','destroy');
        Route::get('get-all-resources','index');
    });
});

Route::prefix('orders')->controller(OrdersController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer|seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-order','update');
        Route::delete('delete-order','destroy');
        Route::get('get-all-orders','index');
        Route::get('get-order/{id}','show');
    });
});

Route::prefix('order-items')->controller(OrderItemsController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer|seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-order-item','update');
        Route::delete('delete-order-item','destroy');
        Route::get('get-all-order-items','index');
        Route::get('get-order-item/{id}','show');
    });
});

Route::prefix('carts')->controller(CartsController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer'])->group(function () {
        Route::post('create', 'store');
        Route::get('get-cart','show');
        Route::patch('update-cart','update');
        Route::delete('delete-cart/{cart_item_id}/{cart_id?}','destroy');
    });
});

Route::prefix('promo-codes')->controller(PromoCodesController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-promo-code/{id}','update');
        Route::delete('delete-promo-code','destroy');
        Route::get('get-all-promo-codes','index');
    });
});

Route::prefix('reviews')->controller(ReviewsController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer|seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-review','update');
        Route::delete('delete-review','destroy');
        Route::get('get-all-reviews/{resource_id}','index');
    });
});

Route::prefix('messages')->controller(MessagesController::class)->group(function () {
    Route::middleware(['jwt.verify', 'role:buyer|seller|admin'])->group(function () {
        Route::post('create', 'store');
        Route::patch('update-message','update');
        Route::delete('delete-message','destroy');
        Route::get('get-all-messages/{receiver_id}','index');
    });
});

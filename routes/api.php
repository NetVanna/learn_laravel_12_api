<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/hello", function () {
    return ["message" => "Hello Laravel API"];
});

/// using get method for show all posts
Route::get("/posts", [PostController::class, "index"])->name("posts.index");

/// using get method for show single post
Route::get("/posts/{id}", [PostController::class, "show"])->name("posts.show");

/// using post method for create post
Route::post("/posts", [PostController::class, "store"])->name("posts.store");

/// using put method for update post
Route::put("/posts/{id}", [PostController::class, "update"])->name("posts.update");

/// using delete method for delete post
Route::delete("/posts/{id}", [PostController::class, "destroy"])->name("posts.destroy");

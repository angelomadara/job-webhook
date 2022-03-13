<?php

/**
 * in every route just return this views
 */
Route::view('/','home');
Route::view('/{any}','home');
Route::view('/{any}/{another_any}','home');

/**
 * disable registration
 */
// Auth::routes([
//     'register' => false,
// ]);

// Route::group(['middleware'=> 'api'],function(){

//     Route::group([
//         'prefix' => 'admin',
//         'middleware' => 'admin',
//         'as' => 'admin.'
//     ],function(){
//         Route::get("/",[AdminController::class,'index'])->name('home');
//         Route::get("/home",[AdminController::class,'index'])->name('home');
//     });

// });

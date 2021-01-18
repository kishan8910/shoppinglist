<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// list all items
Route::get('/items', 'ItemController@index');

// create an item
Route::post('/items', 'ItemController@store');

// update an item
Route::put('/items', 'ItemController@store');

// sort items
Route::put('/items/sort', 'ItemController@sort');

// update bought
Route::put('/items/bought', 'ItemController@bought');

// delete an item
Route::delete('/items/{id}', 'ItemController@destroy');
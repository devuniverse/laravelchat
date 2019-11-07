<?php
// $messengerPath = config('messenger.messengerpath');
// Route::group(['prefix' => $messengerPath,  'middleware' => ['web','auth']], function()
// {
//
//   Route::get('/', '\Devuniverse\Laravelchat\Controllers\MessageController@messengerHome')->name('messenger.home');
//   Route::get('t/{id}', '\Devuniverse\Laravelchat\Controllers\MessageController@laravelMessenger')->name('messenger');
//   Route::post('send', '\Devuniverse\Laravelchat\Controllers\MessageController@store')->name('message.store');
//   Route::get('threads', '\Devuniverse\Laravelchat\Controllers\MessageController@loadThreads')->name('threads');
//   Route::get('more/messages', '\Devuniverse\Laravelchat\Controllers\MessageController@moreMessages')->name('more.messages');
//   Route::delete('delete/{id}', '\Devuniverse\Laravelchat\Controllers\MessageController@destroy')->name('delete');
//   // AJAX requests.
//   Route::prefix('ajax')->group(function () {
//     Route::post('make-seen', '\Devuniverse\Laravelchat\Controllers\MessageController@makeSeen')->name('make-seen');
//   });
//
// });

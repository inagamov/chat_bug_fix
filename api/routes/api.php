<?php

# Auth
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    # Auntification actions
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
});

# Gather info
Route::group(['middleware' => 'api', 'prefix' => 'get'], function () {
    # User data
    Route::get('/user/data', 'GatherController@getUserData');
    Route::get('/user/privacy-settings', 'GatherController@getUserPrivacySettings');
    Route::post('/user/by-id', 'GatherController@getUserById');
    Route::post('/users/profile-images', 'GatherController@getUsersProfileImages');

    # Friendship related data
    Route::get('/user/friendships', 'GatherController@getUserFriendships');

    # Messenger related data
    Route::get('/interlocutors', 'GatherController@getInterlocutors');
    Route::post('/messages', 'GatherController@getMessages');
    Route::post('/message-attachments', 'GatherController@getMessageAttachments');
});

# Files
Route::group(['middleware' => 'api', 'prefix' => 'files'], function () {
    Route::post('/upload', 'FileController@uploadFile');
    Route::post('/remove', 'FileController@removeFile');
});

# Messenger
Route::group(['middleware' => 'api', 'prefix' => 'messenger'], function () {
    Route::post('/message/send', 'MessengerController@sendMessage');
    Route::post('/message/edit', 'MessengerController@editMessage');
    Route::post('/message/delete', 'MessengerController@deleteMessage');
    Route::post('/attach/image', 'MessengerController@attachImage');
    Route::post('/mark-as-read', 'MessengerController@markAsRead');
});
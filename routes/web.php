<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentLikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/register',[RegisterController::class, 'index'])->name('register');

Route::group(['middleware' => 'auth'], function(){
    #Chat(Home)
    Route::get('/home', [ChatController::class, 'index'])->name('home');
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/chats/{tag}/show', [ChatController::class, 'show'])->name('chats.show');
    Route::post('chats/{tag}/store', [ChatController::class, 'store'])->name('chats.store');
    Route::delete('chats/{chat}/destroy', [ChatController::class, 'destroy'])->name('chats.destroy');

    #Like(Chat Like)
    Route::get('/like/{chat}/store', [LikeController::class, 'store'])->name('chat.like.store');
    Route::post('/like/{chat}/store', [LikeController::class, 'store'])->name('chat.like.store');
    Route::delete('/like/{chat}/destroy', [LikeController::class, 'destroy'])->name('chat.like.destroy');

    #Profile(User)
    Route::resource('/profiles', UserController::class, ['only' => ['index', 'show', 'edit', 'update']]);
    #Avatar
    Route::get('/users/{user}/avatars', [AvatarController::class, 'edit'])->name('avatars.edit');
    Route::patch('/users/{user}/avatars', [AvatarController::class, 'update'])->name('avatars.update');
    #Tag
    Route::resource('/tags', TagController::class, ['only' => ['edit', 'store', 'destroy']]);
    #Password
    Route::get('/users/{user}/passwords', [ChangePasswordController::class, 'edit'])->name('passwords.edit');
    Route::patch('/users/{user}/passwords', [ChangePasswordController::class, 'update'])->name('passwords.update');
    #Follow
    Route::resource('/users/{user}/follows', FollowController::class, ['only' => ['store', 'destroy']]);

    #Post
    Route::resource('/posts', PostController::class, ['only' => ['create', 'store', 'show', 'edit', 'update', 'destroy']]);
    #PostLike
    Route::resource('/posts/{post}/responses', PostLikeController::class, ['only' => ['store', 'destroy']]);

    #Comment
    Route::resource('/posts/{post}/comments', CommentController::class, ['only' => ['store', 'destroy']]);
    #CommentLike
    Route::resource('/posts/{post}/comments/{comment}/reactions', CommentLikeController::class);

    #Message
    Route::resource('/users/{user}/messages', MessageController::class,  ['only' => ['store', 'update', 'destroy']]);
    #Message show
    Route::get('/users/{user}/messages', [MessageController::class, 'show'])->name('messages.show');

    #Search
    Route::resource('/search', SearchController::class, ['only' => ['index']]);

    #Contact
    Route::resource('/contact', ContactController::class, ['only' => ['index', 'store', 'destroy']]);

    #Report
    Route::resource('/reports', ReportController::class, ['only' => ['store']]);

    #Admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        #Admin Users
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::delete('/users/{user}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');
        #Admin Posts
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/posts/{post}/hide', [PostsController::class, 'hide'])->name('posts.hide');
        Route::patch('/posts/{id}/unhide', [PostsController::class, 'unhide'])->name('posts.unhide');
    });

});

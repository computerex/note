<?php

use Illuminate\Http\Request;
use \App\User;
use \App\Note;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->post('/auth/login', 'AuthController@postLogin');

$app->get('/', ['middleware' => [], function(Request $request) use ($app) {
  return view('dash', []);
}]);

$app->get('/api/user', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  $user = User::where('email', $_SESSION['user'])->first();
  return $user;
}]);

$app->get('/api/logout', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  unset($_SESSION['user']);
  session_unset();
  session_destroy();
  return response()->json([], 200);
}]);

$app->get('/api/notes', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  $user = User::where('email', $_SESSION['user'])->first();
  return Note::where('user_id', $user->id)->get();
}]);

$app->post('/api/note', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  $user = \App\User::where('email', $_SESSION['user'])->first();
  $note = new Note;
  $note->title = $request->input('title');
  $note->desc = $request->input('desc');
  $note->user_id = $user->id;
  $note->save();
  return response()->json(json_encode($note), 201);
}]);

$app->put('/api/note', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  $note = \App\Note::find($request->input('id'));
  if ( $note ) {
    $note->title = $request->input('title');
    $note->desc = $request->input('desc');
    $note->save();
    return response()->json([], 200);
  }
  return response()->json([], 404);
}]);

$app->delete('/api/note', ['middleware' => ['mw.auth'], function(Request $request) use ($app) {
  $note= \App\Note::find($request->input('id'));
  if($note) {
    $note->delete();
    return response()->json([], 204);
  }
  return response()->json([], 404);
}]);

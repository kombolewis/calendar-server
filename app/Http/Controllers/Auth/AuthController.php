<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(Request $request) {
		$req = Request::create(config('services.passport.login_endpoint'), 'POST', [
			'grant_type' => 'password',
			'client_id' => config('services.passport.client_id'),
			'client_secret' => config('services.passport.client_secret'),  
			'username' => $request->username,
			'password' => $request->password,
		]);
		
		$response = app()->handle($req);
		if ($response->status() == 400) {
				return response()->json([
						'message' => 'Invalid request. Please enter a username or password'
				]);

		} else if ($response->status() == 401) {
				return response()->json([
						'message' => 'Your credentials are incorrect. Please try again'
				]);
		}

		return $response;
	}


	public function register(Request $request){


		$request->validate([
			'fname' => 'required|string|max:255',
			'lname' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6',
		]);

		return User::create([
				'fname' => $request->fname,
				'lname' => $request->lname,
				'email' => $request->email,
				'password' => Hash::make($request->password),
		]);

				
	}



	public function logout(){


		auth()->user()->tokens->each(function ($token, $key){
				$token->delete();
		});

		return response()->json('Logged out successfully', 200);


	}
}

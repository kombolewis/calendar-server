<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}

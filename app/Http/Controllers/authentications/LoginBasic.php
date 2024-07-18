<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Auth;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function login(Request $request){
    // dd($request);
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = [
      'email' => $request->email,
      'password' => $request->password,
      'authorized' => function ($query) {
            $query->where('status','1')->where('role','1');
      },
    ];

    if (Auth::attempt($credentials)) {
        return redirect()->intended('/')
                    ->withSuccess('Signed in');
    }

    return redirect("/auth/login-basic")->withSuccess('Login details are not valid');
  }
}

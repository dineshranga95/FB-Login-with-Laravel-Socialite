<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->middleware('guest')->except('logout');
    }
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleProviderCallback()
    {
        $fbuser = Socialite::driver('facebook')->stateless()->user();        
        $finduser=User::where('email',$fbuser->email)->first();        
        if($finduser){
            Auth::login($finduser);
            return redirect('/home');
        }else{
            $createuser = new User;
            $createuser->name=$fbuser->name;
            $createuser->email=$fbuser->email;
            $createuser->password= bcrypt('123456789');
            $createuser->save();
            Auth::login($createuser);
            return redirect()->route('home');

        }
        // $user->token;
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use League\OAuth1\Client\Server\Server;
use App\Models\IdentityProvider;

use Exception;

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

    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('user');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        if($provider === 'twitter' && ! $request->session()->get('oauth.temp')) {
            $providerUser = $this->getUserFromTwitterUsingTokenAndVerifier($request);
        }else{
            try {
                $providerUser = Socialite::driver($provider)->user();
            } catch (Exception $e) {
                return redirect('/login');
            }
        }

        $identityProviderSerachByProvider = IdentityProvider::where([
            'provider_id' => $providerUser->id,
            'provider_name' => $provider
            ])->get()->first();

        if( $identityProviderSerachByProvider === null) {
            if ($providerUser->email === '') {
                return redirect('/login')->with('flash_message', 'メールアドレス登録済のSNSアカウントよりアクセスして下さい。');
            }

            $authUser = User::firstOrCreate(
                ['email'    => $providerUser->email],
                ['name'     => $providerUser->name, 'password' => \Hash::make(uniqid())],
            );

            IdentityProvider::create([
                'user_id'       =>  $authUser->id,
                'provider_id'   =>  $providerUser->id,
                'provider_name' =>  $provider,
            ]);
            
            \Auth::login($authUser, true);
        }

        $authUser = User::where('id', $identityProviderSerachByProvider->user_id)->first();
        \Auth::login($authUser, true);

        return redirect('/login');
    }

    public function createUser($providerUser)
    {
        $user = User::create([
            'name'     => $providerUser->name,
            'email'    => $providerUser->email,
            'password' => \Hash::make(uniqid()),
        ]);
        return $user;
    }

    private function getUserFromTwitterUsingTokenAndVerifier(Request $request)
    {
        $token = $request->query('oauth_token');
        $secret = $request->query('oauth_verifier');

        $CURLERR = NULL;

        $data = array(
            'msg' => 'メッセージ',
        );
    
        $url = 'https://api.twitter.com/oauth/access_token?oauth_token='.$token.'&oauth_verifier='.$secret;
    
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_POST, TRUE);                            //POSTで送信
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //データをセット
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                    //受け取ったデータを変数に
        $html = curl_exec($ch);

        $afterTokenHtml = strlen('auth_token=') + 1;
        $token = substr($html, $afterTokenHtml, mb_strpos($html, '&oauth_token_secret=') - $afterTokenHtml);

        $afterSecretHtml = mb_strpos($html, "&oauth_token_secret=") + strlen('&oauth_token_secret=');
        $secret = substr($html, $afterSecretHtml, mb_strpos($html, '&user_id=') - $afterSecretHtml);

        try {
            $tUser = Socialite::driver('twitter')->userFromTokenAndSecret($token, $secret);
        } catch (Exception $e) {
            return redirect('/login');
        }

        return $tUser;
    }
}

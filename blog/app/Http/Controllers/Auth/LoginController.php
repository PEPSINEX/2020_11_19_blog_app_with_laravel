<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use League\OAuth1\Client\Server\Server;

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

    // Socialite導入のため
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookProviderCallback()
    {
        // Facebook 認証後の処理
        // あとで処理を追加しますが、とりあえず dd() で取得するユーザー情報を確認
        $fUser = Socialite::driver('facebook')->stateless()->user();
        // dd($fUser);

        $user = User::where('email', $fUser->email)->first();
        // 見つからなければ新しくユーザーを作成
        if ($user == null) {
            $user = $this->createUserByFacebook($fUser);
        }
        // ログイン処理
        \Auth::login($user, true);
        return redirect('/home');
    }

    public function createUserByFacebook($fUser)
    {
        $user = User::create([
            'name'     => $fUser->name,
            'email'    => $fUser->email,
            'password' => \Hash::make(uniqid()),
        ]);
        return $user;
    }

    public function redirectToTwitterProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterProviderCallback(Request $request)
    {
        $tUser = Socialite::driver('twitter')->user();
        dd($tUser->email);

        // dd($request->query());

        // ----------------------------------------------------------------
        // 無理やりログイン

        // $token = $request->query('oauth_token');
        // $secret = $request->query('oauth_verifier');

        // $CURLERR = NULL;

        // $data = array(
        //     'msg' => 'メッセージ',
        // );
    
        // $url = 'https://api.twitter.com/oauth/access_token?oauth_token='.$token.'&oauth_verifier='.$secret;
    
        // $ch = curl_init($url);
    
        // curl_setopt($ch, CURLOPT_POST, TRUE);                            //POSTで送信
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //データをセット
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                    //受け取ったデータを変数に
        // $html = curl_exec($ch);

        // $afterTokenHtml = strlen('auth_token=') + 1;
        // $token = substr($html, $afterTokenHtml, mb_strpos($html, '&oauth_token_secret=') - $afterTokenHtml);

        // $afterSecretHtml = mb_strpos($html, "&oauth_token_secret=") + strlen('&oauth_token_secret=');
        // $secret = substr($html, $afterSecretHtml, mb_strpos($html, '&user_id=') - $afterSecretHtml);

        // $tUser = Socialite::driver('twitter')->userFromTokenAndSecret($token, $secret);

        // ----------------------------------------------------------------


        $user = User::where('email', $tUser->email)->first();
        // 見つからなければ新しくユーザーを作成
        if ($user == null) {
            $user = $this->createUserByTwitter($tUser);
        }
        // ログイン処理
        \Auth::login($user, true);
        return redirect('/home');
    }

    public function createUserByTwitter($tUser)
    {
        $user = User::create([
            'name'     => $tUser->name,
            'email'    => $tUser->email,
            'password' => \Hash::make(uniqid()),
        ]);
        return $user;
    }

}

<?php

namespace Bakgat\Notos\Http\Controllers\Identity;

use Bakgat\Notos\Domain\Services\Identity\OrganizationService;
use Bakgat\Notos\Domain\Services\Identity\UserService;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;
use NotosPlus\Http\Controllers\Controller;

class AuthController extends Controller
{
    use RedirectsUsers;

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    protected $redirectPath = '/';

    private $userService;

    /**
     * Create a new authentication controller instance.
     *
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->userService = $userService;
    }


    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|email', 'password' => 'required',
        ]);

        $credentials =$this->getCredentials($request);

        if($this->userService->login($credentials, $request->has('remember'))) {
            return redirect('/');
        } else {
            return redirect($this->loginPath())
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => $this->getFailedLoginMessage(),
                ]);
        }

    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('username', 'password');
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'These credentials do not match our records.';
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();

        Session::flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }



}

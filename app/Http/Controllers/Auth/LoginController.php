<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Order;

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
    public function login(Request $request)
    {
       
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',

        ]);

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            if (auth()->user()->is_admin == 1) {
                if($request->order == '1'){
                    $order = Order::with('room','bathroom','discount','time_slot','extraorder','cleaningtype')->latest()->first();
                    if(!empty($order)){
                        return redirect()->route('order.detail',$order->id);
                    }
                    return redirect()->route('orders.index');
                   
                }
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('client.dashboard');
            }
        }else{

            return redirect()->route('login')
                ->with('error','Authentication Failed. Email or Password Is Invalid.');
        }

    }
}

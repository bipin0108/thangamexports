<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('admin.dashboard');
    }

    public function profile(){
        $user = auth()->user();
        return view('admin.profile', compact('user'));   
    }

    public function profile_change(Request $request){
         
        $this->validate(request(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ]);
        $user = User::find(auth()->user()->id);
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->save();
        return back()->with('success','Profile has been changed successfully!');
         
    }

    public function change_password(Request $request){
        $this->validate(request(), [
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);
        $user = User::find(auth()->user()->id);
        $user->password = Hash::make(request('password'));
        $user->save();
        return back()->with('success','Password has been changed successfully!');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}

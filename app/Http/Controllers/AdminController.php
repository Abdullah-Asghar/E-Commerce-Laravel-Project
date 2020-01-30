<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->input();
            if(Auth::attempt(['email'=>$data['email'], 'password'=>$data['password'], 'admin'=>'1'])){
               // echo 'Success'; die;
               Session::put('adminSession', $data['email']);
              return redirect('admin/dashboard');
            }
            else{
              //  echo 'Failed'; die;
            return redirect('/admin')->with('flash_message_error', 'Invalid username or password');
            }
        }
        return view('admin.admin_login');
    }
    public function dashboard(){
        if(Session::has('adminSession')){
        }
        else{
            return redirect('/admin')->with('flash_message_error', 'Please login to access');
        }
        
        return view('admin.dashboard');
    }

    public function settings(){
        return view('admin.settings');
    }
    public function chkPassword(Request $request){
     $data = $request->all();
     $current_password = $data['current_pwd'];
     $check_password = User::where(['admin'=>'1'])->first();
     if(Hash::check($current_password,$check_password->password)){
      return 1;
     }
     else{
         return 0;
     }
    }
    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $check_password = User::where(['email' => Auth::user()->email])->first();
            $current_password = $data['current_pwd'];
            if(Hash::check($current_password,$check_password->password)){
                $password = bcrypt($data['new_pwd']);
                User::where('id',$check_password->id)->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success', 'Password updated successfully');
            }else{
                return redirect('/admin/settings')->with('flash_message_error', 'Incurrect current password');
                
            }

        }
    }
    public function logout(){
        Session::flush();
        return redirect('/admin')->with('flash_message_success', 'Logout Successlly');

    }

}
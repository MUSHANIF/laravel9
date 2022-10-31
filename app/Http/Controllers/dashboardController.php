<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index() {
        return view('dashboard');
    }
    public function detail(Request $request , $id){
        $datas = User::where('id',$id)->first();
        return view('profile' ,compact('datas'));
    }
        
}

<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class profileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datas = User::where('id',$id)->first();
        // $password = $datas->password;
        // $decrypted = Crypt::decrypt($password);
        return view('profile' ,compact('datas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $model = User::findOrFail($id);
        $model->image = $request->image;
        $model->name = $request->name;
        $model->username = $request->username;
        $model->email = $request->email;
      
       


        $validasi = Validator::make($data, [
            'name' => 'required|max:255',
           
            'username' => 'required|max:15',
            'email' => 'required|email',
          
            

        ]);
        if ($validasi->fails()) {
            return redirect()->route('profile.show', $id)->withInput()->withErrors($validasi);
        }
        if ($image = $request->file('image')) {
            $destinationPath = 'assets/img/avatars';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $model['image'] = "$profileImage";
        }
        $model->save();

        toastr()->success('Berhasil di ubah!', 'Sukses');
        return redirect()->route('profile.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function change($id){

       $datas = User::where('id',$id)->first();
        return view('change' ,compact('datas'));
    }
    public function updatepw(Request $request, $id){
        $data = $request->all();
        $validasi = Validator::make($data, [
            'old' => 'required',
            'new' => 'required',
            'confirm' => 'required|same:new',
        ]);
        if ($validasi->fails()) {
            toastr()->error('gagal di ubah! ,tolong cek lagi', 'Gagal');
            return redirect()->back();
        }
        $hash = Auth::user()->password;
        if(Hash::check($request->old,$hash)){
            $user = User::findOrFail($id);
            $user->password = bcrypt($request->new);
            $user->save();
            toastr()->success('password Berhasil di ubah!', 'Sukses');
            return redirect()->back();
        }else{
            toastr()->error('gagal di ubah! ,tolong cek lagi', 'Gagal');
            return redirect()->back();
        }
     }
}

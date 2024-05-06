<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
        return view('administrator.user'); //blade.php
    }

    public function getUsers(Request $req){
        $sort = explode('.', $req->sort_by);

        $users = User::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $users;
    }

    public function show($id){
        return User::find($id);
    }

    function saveBoarder($req){
        $validate = $req->validate([
            'username' => ['required', 'string', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'max:30', 'confirmed'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],

            'email' => ['required', 'string', 'max:100', 'unique:users'],
            'sex' => ['required', 'string', 'max:20'],
            'contact_no' => ['required', 'max:30', 'regex: /^(09|\+639)\d{9}$/'],

            'role' => ['required', 'max:30'],

            'guardian_name' => ['required', 'max:100'],
            'guardian_contact_no' => ['required', 'max:100'],
            'guardian_address' => ['required', 'max:100'],

            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ]);

        User::create([
            'username' => $req->username,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'sex' => strtoupper($req->sex),
            'role' => strtoupper($req->role),
            'email' => $req->email,
            'contact_no' => $req->contact_no,

            'guardian_name' => strtoupper($req->guardian_name),
            'guardian_contact_no' => strtoupper($req->guardian_contact_no),
            'guardian_address' => strtoupper($req->guardian_address),

            'province' => strtoupper($req->province),
            'city' => strtoupper($req->city),
            'barangay' => strtoupper($req->barangay),
            'street' => strtoupper($req->street),

            'password' => Hash::make($req->password),
        ]);
    }

    function saveLandOwner($req){
        $validate = $req->validate([
            'username' => ['required', 'string', 'max:30', 'unique:users'],
            'password' => ['required', 'string', 'max:30', 'confirmed'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],

            'email' => ['required', 'string', 'max:100', 'unique:users'],
            'sex' => ['required', 'string', 'max:20'],
            'contact_no' => ['required', 'max:30', 'regex: /^(09|\+639)\d{9}$/'],

            'role' => ['required', 'max:30'],

            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ]);

        User::create([
            'username' => $req->username,
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'sex' => strtoupper($req->sex),
            'role' => strtoupper($req->role),
            'email' => $req->email,
            'contact_no' => $req->contact_no,
            'province' => strtoupper($req->province),
            'city' => strtoupper($req->city),
            'barangay' => strtoupper($req->barangay),
            'street' => strtoupper($req->street),
            'password' => Hash::make($req->password),
        ]);
    }




    public function store(Request $req){
        //this will create random unique QR code
        //$qr_code = substr(md5(time() . $req->lname . $req->fname), -8);

        if($req->role == 'BOARDER'){
            $this->saveBoarder($req);
        }else{
            $this->saveLandOwner($req);
        }

        return response()->json([
            'status' => 'saved'
        ]);
    }




    function updateBoarder($req, $id){
        $validate = $req->validate([
            'username' => ['required', 'string', 'max:30', 'unique:users,username,' .$id. ',user_id'],
            'password' => ['required', 'string', 'max:30', 'confirmed'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:100', 'unique:users,email,' .$id. ',user_id'],
            'sex' => ['required', 'string', 'max:20'],
            'contact_no' => ['required', 'max:30'],
            'role' => ['required', 'max:30'],
            'guardian_name' => ['required', 'max:100'],
            'guardian_contact_no' => ['required', 'max:100'],
            'guardian_address' => ['required', 'max:100'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ]);

        $data = User::find($id);
        $data->username = $req->username;
        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->suffix = strtoupper($req->suffix);
        $data->sex = $req->sex;
        $data->email = $req->email;
        $data->contact_no = $req->contact_no;
        $data->role = $req->role;
        $data->guardian_name = strtoupper($req->guardian_name);
        $data->guardian_contact_no = strtoupper($req->guardian_contact_no);
        $data->guardian_address = strtoupper($req->guardian_address);
        $data->province = strtoupper($req->province);
        $data->city = strtoupper($req->city);
        $data->barangay = strtoupper($req->barangay);
        $data->street = strtoupper($req->street);
        $data->save();
    }

    function updateLandOwner($req, $id){
        $validate = $req->validate([
            'username' => ['required', 'string', 'max:30', 'unique:users,username,' .$id. ',user_id'],
            'password' => ['required', 'string', 'max:30', 'confirmed'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:100', 'unique:users,email,' .$id. ',user_id'],
            'sex' => ['required', 'string', 'max:20'],
            'contact_no' => ['required', 'max:30'],
            'role' => ['required', 'max:30'],
            'province' => ['required'],
            'city' => ['required'],
            'barangay' => ['required'],
            'street' => ['required'],
        ]);

        $data = User::find($id);
        $data->username = $req->username;
        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->suffix = strtoupper($req->suffix);
        $data->sex = $req->sex;
        $data->email = $req->email;
        $data->contact_no = $req->contact_no;
        $data->role = $req->role;
        $data->province = strtoupper($req->province);
        $data->city = strtoupper($req->city);
        $data->barangay = strtoupper($req->barangay);
        $data->street = strtoupper($req->street);
    }



    public function update(Request $req, $id){

        if($req->role == 'BOARDER'){
            $this->updateBoarder($req, $id);
        }else{
            $this->updateLandOwner($req, $id);
        }

        return response()->json([
            'status' => 'updated'
        ]);
    }


    public function destroy($id){
        $data = User::find($id);

        if(Storage::exists('public/bpermits/'. $data->business_permit_img)){
            Storage::delete('public/bpermits/'. $data->business_permit_img);
        }

        User::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }

    public function resetPassword(Request $req, $id){

        $req->validate([
            'password' => ['required',  'confirmed', 'min:4']
        ]);

        $data = User::find($id);
        $data->password = Hash::make($req->password);
        $data->save();

        return response()->json([
            'status' => 'reseted'
        ]);
    }



}

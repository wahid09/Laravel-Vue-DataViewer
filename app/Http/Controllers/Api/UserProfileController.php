<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileCollection;
use App\Http\Resources\UserProfileResource;
use App\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new UserProfileCollection(UserProfile::latest()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:user_profiles',
            'phone'    => 'required|numeric|unique:user_profiles',
            'Country'  => 'required',
        ]);

        $userpro = new UserProfile();
        $userpro->name = $request->name;
        $userpro->email = $request->email;
        $userpro->phone = $request->phone;
        $userpro->Country = $request->Country;
        $userpro->address = $request->address;
        if(isset($request->status)){
            $userpro->status =1;
        }else{
            $userpro->status =0;
        }
        $userpro->save();

        return new UserProfileResource($userpro);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserProfileResource(UserProfile::findOrFail($id));
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
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:user_profiles,email,'.$id,
            'phone'    => 'required|numeric|unique:user_profiles,phone,'.$id,
            'Country'  => 'required',
        ]);

        $userpro = UserProfile::findOrFail($id);
        $userpro->name = $request->name;
        $userpro->email = $request->email;
        $userpro->phone = $request->phone;
        $userpro->Country = $request->Country;
        $userpro->address = $request->address;
        if(isset($request->status)){
            $userpro->status =1;
        }else{
            $userpro->status =0;
        }
        $userpro->save();

        return new UserProfileResource($userpro);
    }
    public function SearchData($field, $query){
        return new UserProfileCollection(UserProfile::where($field, 'LIKE', "%$query%")->latest()->paginate(10));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = UserProfile::findOrFail($id);
        $user->delete();
        return new UserProfileResource($user);
    }
}

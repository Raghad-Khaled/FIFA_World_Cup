<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Http\Requests\RegisterAdminRequest;
use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdministratorsContoller extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getall()
    {
        $user = User::all();

        return $this->respondWithResourceCollection(new ResourceCollection(["user" => $user]), "user info");
    }

    public function register(RegisterAdminRequest $request)
    {

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $admin = Administrator::create($input);
        $token = $admin->createToken("API TOKEN")->plainTextToken;

        return $this->respondtextContent($token, "admin registered successfully");
    }

    public function login(Request $request)
    {

        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];
        # authorize with gard admin
        if (Auth::guard('administrator')->attempt($data)) {
            $admin = Auth::guard('administrator')->user();
            $admin = Administrator::find($admin->id);
            $token = $admin->createToken("API TOKEN")->plainTextToken;
            return $this->respondtextContent($token,"admin loged in");
        } else {
            return $this->respondUnAuthorized("user Unauthorised");
        }
    }

     /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteuser($id)
    {
        $user = User::find($id);
        if($user->delete()){
            return $this->respondSuccess("user deleted Successfully");
        }else{
            return $this->respondError();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
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
        //
    }

}

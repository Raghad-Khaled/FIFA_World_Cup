<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    use ApiResponseTrait;
    /**
     * @OA\Post(
     * path="/api/users/login",
     * summary="login",
     * description="Login by username, password",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="RaghadKhaled"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully loged in",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="user loged in"),
     *       @OA\Property(property="token", type="string", example="14|dfghjklkjhgfdcxs34576543nhgngh")
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorised",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean", example=false),
     *       @OA\Property(property="message", type="string", example="Sorry, user unauthorised"),
     *       @OA\Property(property="error", type="string", example="Unauthorised")
     *        )
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $user = auth()->user();
            $user = User::find($user->id);
            $token = $user->createToken("API TOKEN")->plainTextToken;
            return $this->respondtextContent($token, "user loged in");
        } else {
            return $this->respondUnAuthorized("user Unauthorised");
        }
    }

    /**
     * @OA\Post(
     * path="/api/users/create",
     * summary="sign up",
     * description="sign up as user or manager",
     * operationId="authSignUp",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"username","first_name","last_name","birth_date","gneder","role","email","password"},
     *       @OA\Property(property="username", type="string", example="RaghadKhaled"),
     *       @OA\Property(property="first_name", type="string", example="Raghad"),
     *       @OA\Property(property="last_name", type="string", example="Khaled"),
     *       @OA\Property(property="birth_date", type="date", example="2000-09-06"),
     *       @OA\Property(property="gender", type="string", example="f OR m"),
     *       @OA\Property(property="nationality", type="string", example="egyption"),
     *       @OA\Property(property="role", type="string", example="manager OR fan"),
     *       @OA\Property(property="email", type="string", example="Raghad@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="user registered successfully",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="user registered successfully"),
     *       @OA\Property(property="token", type="string", example="14|dfghjklkjhgfdcxs34576543nhgngh")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Error in input",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, user unauthorised"),
     *       @OA\Property(property="errors", type="object", example="{'username': [ 'The username has already been taken.'],'email': ['The email has already been taken.']}")
     *        )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['be_manager'] = $input['role'] == 'manager' ? 1 : 0;
        $input['role'] = 'fan';
        $user = User::create($input);
        $token = $user->createToken("API TOKEN")->plainTextToken;
        return $this->respondtextContent($token, "user registered successfully");
    }
    /**
     * @OA\Get(
     * path="/api/user",
     * summary="user info",
     * description="get ingormation of login user",
     * operationId="authInfo",
     * security={{"bearerAuth":{}}},
     * tags={"auth"},
     * @OA\Response(
     *    response=200,
     *    description="Successfully loged in",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="user info"),
     *       @OA\Property(property="user", type="object", example={"id": 5,
     *   "username": "RaghadKhaled5",
     *   "first_name": "raghad",
     *   "last_name": "khaled",
     *   "birth_date": "2000-06-09",
     *   "nationality": "egyption",
     *   "gender": "f",
     *   "role": "manager",
     *   "email": "raghad12347@gmail.com",
     *   "email_verified_at": null,
     *   "created_at": "2022-12-03T12:25:51.000000Z",
     *   "updated_at": "2022-12-03T12:25:51.000000Z"})
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorised",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     )
     * )
     */
    public function userinfo()
    {
        $user = auth()->user();

        return $this->respondWithResourceCollection(new ResourceCollection(["user"=>$user]),"user info");
    }

    public function bemanager()
    {

        # get all users want to be manager
        $users = User::where('be_manager', 1)->get();
        return $this->respondWithResourceCollection(new ResourceCollection(["users" => $users]), "users want to be manager");
    }

    public function manager($id)
    {

        # get user with id
        $user = User::find($id);
        # update user role to manager
        $user->role = 'manager';
        $user->be_manager = 0;
        $user->save();
        return $this->respondWithResourceCollection(new ResourceCollection(["user" => $user]), "users want to be manager");
    }

    public function update(UpdateRequest $request)
    {

        #get current user
        $user = auth()->user();
        # update current user
        $user = User::find($user->id);
        #check if request has password
        if ($request->has('password')) {
            #hash password
            $request['password'] = Hash::make($request['password']);
        }
        #update user exept username and email
        $user->update($request->all());
        return $this->respondWithResourceCollection(new ResourceCollection(["user" => $user]), "user updated successfully");
    }

    /**
     * @OA\Get(
     * path="/api/users/logout",
     * summary="user logout",
     * description="logout user",
     * operationId="authLogout",
     * security={{"bearerAuth":{}}},
     * tags={"auth"},
     * @OA\Response(
     *    response=200,
     *    description="Successfully loged in",
     *    @OA\JsonContent(
     *      @OA\Property(property="status", type="boolean", example=true),
     *       @OA\Property(property="message", type="string", example="user info"),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Unauthorised",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *        )
     *     )
     * )
     */
    public function logout(Request $request)
    {

        if ($request->user()->currentAccessToken()->delete()) {
            return $this->respondSuccess("user loged out");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getall()
    {
        $users = User::all();
        return $this->respondWithResourceCollection(new ResourceCollection(["users" => $users]), "users");
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

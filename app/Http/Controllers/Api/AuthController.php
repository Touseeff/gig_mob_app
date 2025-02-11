<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function signup(Request $request)
    {
        //    return $request;
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'first_name'    => 'required|string|max:100',
                    'last_name'     => 'required|string|max:100',
                    'email'         => 'required|email|unique:users,email',
                    'password'      => 'required|min:6',
                    'schedule_type' => 'required|string',
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validate->errors()->all(),
                ], 422);
            }

            $user                = new User();
            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->password      = Hash::make($request->password);
            $user->schedule_type = $request->schedule_type;
            $user->auth_provider = $request->auth_provider;
            $user->save();
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'status'  => true,
                'message' => 'Record Inserted Successfully',
                'data'    => $user,
                'token'   => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * new user verification.
     */

    /**
     * Store a newly signin in storage.
     */
    public function signin(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()->all(),
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Pleae check Email or Password',
                ], 401);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Login successful',
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'user'    => $user,
                'token'   => $token,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => "Something went wrong",
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);
    
           
            $otp = rand(1000, 9999);
    
            
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User not found',
                ], 404);
            }
    
            
            $user->forgot_password_token = $otp;
            $user->save();
    
            
            Mail::to($user->email)->send(new ResetPasswordMail($otp));
    
            return response()->json([
                'status'  => true,
                'message' => 'OTP sent successfully to your email.',
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    


public function passwordVerification(Required $request){
    try{

    $validation =  validator::make($request->all(),[
        'password'=>'require|confirmed|password',
    ]);
    $user = User::where('password',operator: $request->password);

    }
    catch(\Exception $e){
        return response()->json([
            'status'=>false,
            
        ]);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

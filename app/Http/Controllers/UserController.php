<?php
namespace App\Http\Controllers;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{

    function LoginPage():View{
        return view('pages.auth.login-page');
    }

    function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }
    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }

    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }

    function ProfilePage():View{
        return view('pages.dashboard.profile-page');
    }



    function UserRegistration(Request $request){
    try {

        // 1. create user first
        $user = User::create([
            'firstName' => $request->input('firstName'),

            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => $request->input('password'),

            'shop_name' => $request->input('shopName'),
            'role' => $request->input('role'),
        ]);

        // 2. make user id as shop id
        $user->shop_id = $user->id;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User Registration Successfully'
        ], 200);

    } catch (Exception $e) {

        return response()->json([
            'status' => 'failed',
            'message' => 'User Registration Failed'
        ], 200);
    }
}
    function UserLogin(Request $request){

    $user = User::where('email',$request->input('email'))->first();

  if ($user->password == $request->input('password')){

        $token = JWTToken::CreateToken($user->email, $user->id);

        // 🔥 STORE CURRENT ACTIVE TOKEN
        $user->update([
            'login_token' => $token
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Login Successful',
        ])->cookie('token', $token, 60*24*30);
    }

    return response()->json([
        'status' => 'failed',
        'message' => 'unauthorized'
    ]);
}

    function SendOTPCode(Request $request){

        $email=$request->input('email');
        $otp=rand(1000,9999);
        $count=User::where('email','=',$email)->count();

        if($count==1){
            // OTP Email Address
            Mail::to($email)->send(new OTPMail($otp));
            // OTO Code Table Update
            User::where('email','=',$email)->update(['otp'=>$otp]);

            return response()->json([
                'status' => 'success',
                'message' => '4 Digit OTP Code has been send to your email !'
            ],200);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }
    }

    function VerifyOTP(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->count();

        if($count==1){
            // Database OTP Update
            User::where('email','=',$email)->update(['otp'=>'0']);

            // Pass Reset Token Issue
            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successful',
            ],200)->cookie('token',$token,60*24*30);

        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ],200);
        }
    }

    function ResetPassword(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }

    function UserLogout(){
        return redirect('/')->cookie('token','',-1);
    }


     function UserProfile(Request $request)
{
    try {

        $email = $request->auth_email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'User not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Error'
        ]);
    }
}


function UpdateProfile(Request $request)
{
    try {

        $email = $request->auth_email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'fail',
                'message' => 'User not found'
            ]);
        }

        // UPDATE FIELDS
        $user->firstName = $request->input('firstName');
        $user->mobile    = $request->input('mobile');
        $user->shop_name = $request->input('shopName');

        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);

    } catch (Exception $e) {

        return response()->json([
            'status' => 'fail',
            'message' => 'Update failed'
        ]);
    }
}
}

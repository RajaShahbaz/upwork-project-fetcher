<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
class UserController extends Controller
{


function signup(Request $request)
{
    try{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
    ]);
} catch (ValidationException $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}

    // Create a new user
    $user = new User;
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->phone = $validatedData['phone'];
    $user->subcription_type=$request->subcription_type ?? "free";
    $user->password = Hash::make($validatedData['password']);
    $user->save();

    // Generate a Sanctum token for the new user
    $token = $user->createToken('auth-token')->plainTextToken;
    $response["success"] = true;
    $response["message"] = "User Registered Successfully";
    $response["token"] =$token;
    $response["user"] = $user;
    return $response;
}
function login(Request $request)
{
    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    } catch (ValidationException $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }

    // Find the user by email
    $user = User::where('email', $validatedData['email'])->first();
    // Check if the user exists and the password is correct
    if (!$user || !Hash::check($validatedData['password'], $user->password)) {
        return response()->json(['error' => 'invalid email or password'], 400);
    }

    // Generate a Sanctum token for the user
    $token = $user->createToken('auth-token')->plainTextToken;

    // Return the user data and token
    $response["success"] = true;
    $response["message"] = "LoggedIn Successfully";
    $response["token"] =$token;
    $response["user"] = $user;
    return $response;
}
public function sendResetLinkEmail(Request $request)
{
    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);
    } catch (ValidationException $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
    $status = Password::sendResetLink(
        $request->only('email')
    );
    $response["success"] = true;
    $response["message"] = "Mail Sent Successfully";
    return $response;
    // return $status === Password::RESET_LINK_SENT
    //             ? back()->with('status', trans($status))
    //             : back()->withErrors(['email' => trans($status)]);
}

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users=User::get();
        if($users->isEmpty()){
            $response["success"] = true;
            $response["message"] = "No User found";
            $response["users"] = $users;
        }else{
            $response["success"] = true;
            $response["message"] = "Record found";
            $response["users"] = $users;
        }

    return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        $user=User::find($id);
        if(empty($user)){
            $response["success"] = true;
            $response["message"] = "No User found";
            $response["user"] = $user;
        }else{
            $response["success"] = true;
            $response["message"] = "Record found";
            $response["user"] = $user;
        }

    return $response;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $user=User::find(auth('sanctum')->user()->id);
        $user->email=$request->email ?? $user->email;
        $user->phone=$request->phone ?? $user->phone;
        $user->name=$request->name ?? $user->name;
        $user->subcription_type=$request->subcription_type ?? $user->subcription_type;
        $user->save();
        $response["success"] = true;
        $response["message"] = "profile updated successfully";
        $response["data"] = $user;
        return $response;

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

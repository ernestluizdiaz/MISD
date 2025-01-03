<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    protected $auth;

    public function __construct()
    {
        // Initialize Firebase Auth with credentials
        $firebase = (new \Kreait\Firebase\Factory)
            ->withServiceAccount(base_path('storage/firebase/firebase_credentials.json')) // Corrected path
            ->createAuth(); // Use createAuth to initialize Firebase Authentication

        $this->auth = $firebase;
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate the form fields
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Attempt to sign in with Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            // Get user data from Firebase
            $user = $signInResult->data();

            // Create or update a user record in your Laravel database if necessary
            // For example: FirebaseUser::updateOrCreate(...);

            // Return success response
            return response()->json(['success' => true]);
        } catch (FailedToVerifyToken $e) {
            // Handle incorrect password
            return response()->json(['error' => 'Password is incorrect.'], 400);
        } catch (\Exception $e) {
            // Only return password incorrect error message, ignoring other errors
            return response()->json(['error' => 'Password is incorrect.'], 400);
        }
    }





    public function logout()
    {
        // Laravel logout
        Auth::logout();  // Use the Laravel Auth facade to log out the user

        return redirect()->route('login');
    }
}

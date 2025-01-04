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
            // Check if the email exists in Firebase
            $user = $this->auth->getUserByEmail($request->email);

            // If email exists, attempt to sign in with Firebase
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            // Get user data from Firebase
            $user = $signInResult->data();

            // Return success response
            return response()->json(['success' => true]);

        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            // Email does not exist in Firebase
            return response()->json(['error' => 'Email does not exist.'], 400);
        } catch (FailedToVerifyToken $e) {
            // Handle incorrect password
            return response()->json(['error' => 'An error occurred.'], 400);
        } catch (\Exception $e) {
            // Handle any other exceptions
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

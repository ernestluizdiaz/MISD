<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase\Exception\Auth\EmailExists;

class RegisterController extends Controller
{
    protected $firebaseAuth;
    protected $firebaseDatabase;

    public function __construct()
    {
        // Initialize Firebase Auth and Database services
        $this->firebaseAuth = app('firebase.auth');
        $this->firebaseDatabase = app('firebase.database');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm-password' => 'required|same:password',
        ]);

        try {
            $user = $this->firebaseAuth->createUserWithEmailAndPassword(
                $request->input('email'),
                $request->input('password')
            );

            // Optional: Store additional user details in Firebase
            $database = app('firebase.database');
            $database->getReference('users/' . $user->uid)
                ->set([
                    'email' => $user->email,
                    'created_at' => now()->toDateTimeString(),
                ]);

            return redirect()->route('login')->with('success', 'Account created successfully!');
        } catch (EmailExists $e) {
            return back()->withErrors(['email' => 'This email is already registered.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to register. Please try again.']);
        }
    }
}

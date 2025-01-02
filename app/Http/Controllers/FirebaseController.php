<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Factory;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    public function testConnection()
    {
        try {
            // Directly access the credentials file from the filesystem
            $firebase = (new Factory)
                ->withServiceAccount(storage_path('firebase/firebase_credentials.json'))
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
            $database = $firebase->createDatabase();

            // Perform a simple operation to test the connection
            $reference = $database->getReference('test_connection');
            $value = $reference->getValue();

            return response()->json(['data' => $value], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


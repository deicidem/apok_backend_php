<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'organisation' => ['nullable', 'string', 'max:255'],
            'phoneNumber' => ['nullable', 'string', 'max:255'],
        ])->validate();
        $user = User::create([
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => 2,
            'organisation' => $input['organisation'],
            'phone_number' => $input['phoneNumber']
        ]);
        UserLog::create([
            'user_id' => $user->id,
            'message' => 'Пользователь был зарегистрирован',
            'type' => 'store'
          ]);
        return $user;
    }
}

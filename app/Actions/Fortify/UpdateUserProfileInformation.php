<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        if (array_key_exists('id', $input)) {
            $user = User::find($input['id']);
        }

        Validator::make($input, [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'organisation' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'first_name' => $input['firstName'],
                'last_name' => $input['lastName'],
                'organisation' => $input['organisation'],
                'phone_number' => $input['phoneNumber'],
                'email' => $input['email'],
            ])->save();
        }
        UserLog::create([
            'user_id' => $user->id,
            'message' => 'Изменил личную информацию',
            'type' => 'change'
        ]);
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'organisation' => $input['organisation'],
            'phone_number' => $input['phoneNumber'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}

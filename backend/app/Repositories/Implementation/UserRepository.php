<?php

namespace App\Repositories\Implementation;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $attributes)
    {
        return User::create($attributes);
    }

    public function findUser($id)
    {
        return User::find($id);
    }

    public function updateUser($model, $id, $userRoles)
    {
        $user = $this->findUser($id);
        if ($user) {
            $user->update($model);
            // You can add logic here to update user roles if necessary.
        }
        return $user;
    }

    public function updateUserProfile($request)
    {
        $user = $this->findUser($request->user()->id);
        if ($user) {
            $user->update($request->all());
        }
        return $user;
    }

    public function getUsersForDropdown()
    {
        return User::select('id', 'name')->get();
    }

    public function forgotPassword($request)
    {
        // Implement forgot password logic here.
    }

    public function getUserInfoForResetPassword($id)
    {
        return $this->findUser($id);
    }

    public function resetPassword($request)
    {
        $user = $this->findUser($request->id);
        if ($user) {
            $user->password = bcrypt($request->newPassword);
            $user->save();
        }
        return $user;
    }
}

<?php

namespace App\Repositories\Contracts;

// You can extend a BaseRepositoryInterface if needed
interface UserRepositoryInterface
{
    public function createUser(array $attributes);
    public function findUser($id);
    public function updateUser($model, $id, $userRoles);
    public function updateUserProfile($request);
    public function getUsersForDropdown();
    public function forgotPassword($request);
    public function getUserInfoForResetPassword($id);
    public function resetPassword($request);
}

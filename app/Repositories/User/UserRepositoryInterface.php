<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function searchUser(array $data);
    public function searchUserAdmin(array $data);
    public function findByID($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByEmail(string $email);
}

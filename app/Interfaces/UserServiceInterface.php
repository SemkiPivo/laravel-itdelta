<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function index();
    public function store(Request $request);
    public function update(User $user, Request $request);
    public function destroy(User $user);
}
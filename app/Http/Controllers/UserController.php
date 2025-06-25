<?php

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService,
    )
    {
    }

    public function index(): View
    {
        return $this->userService->index();
    }
    public function store(): View
    {
        return $this->userService->index();
    }
    public function update(): View
    {
        return $this->userService->index();
    }
    public function delete(): View
    {
        return $this->userService->index();
    }
}
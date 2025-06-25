<?php

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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
    public function store(Request $request): RedirectResponse
    {
        return $this->userService->store($request);
    }
    public function update(User $user, Request $request): RedirectResponse
    {
        return $this->userService->update($user, $request);
    }
    public function destroy(User $user): RedirectResponse
    {
        return $this->userService->destroy($user);
    }
}
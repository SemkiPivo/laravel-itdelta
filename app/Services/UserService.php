<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{

    public function index()
    {
        return view('users.index', [
            'users' => User::all()->sortBy('id')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        dd(1);

        $userData = [
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'login' => $validated['login'],
            'password' => Hash::make($validated['password']),
        ];

        // Загрузка изображения
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/users/photos');
            $userData['photo'] = str_replace('public/', '', $path);
        }

        User::create($userData);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }


    public function update(User $user, Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'login' => 'required|string|max:255|unique:users,login,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'login' => $validated['login'],
        ];

        // Обновление пароля
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        //Загрузка фото
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            $path = $request->file('photo')->store('public/users/photos');
            $userData['photo'] = str_replace('public/', '', $path);
        }

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', ' Пользователь успешно обновлен.');
    }


    public function destroy(User $user)
    {
        // Удаляем изображение если существует
        if ($user->photo) {
            Storage::delete('public/' . $user->photo);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Пользователь удален.');
    }

}
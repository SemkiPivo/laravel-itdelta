<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        try {
            // Валидация входных данных
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'phone' => 'required|string|max:20',
                'email' => 'required|string|email|max:255|unique:users',
                'login' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Подготовка данных пользователя
            $userData = [
                'full_name' => $validated['full_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'login' => $validated['login'],
                'password' => Hash::make($validated['password']),
            ];

            // Обработка загрузки фото
            if ($request->hasFile('photo')) {
                try {
                    $path = $request->file('photo')->store('public/users/photos');
                    $userData['photo'] = str_replace('public/', '', $path);
                } catch (\Exception $e) {
                    // Логирование ошибки загрузки файла
                    Log::error('Photo upload failed: ' . $e->getMessage());
                    return back()
                        ->withInput()
                        ->with('error', 'Failed to upload user photo. Please try again.');
                }
            }

            // Создание пользователя
            try {
                $user = User::create($userData);

            } catch (\Exception $e) {
                // Если фото было загружено, но создание пользователя не удалось - удаляем фото
                if (isset($userData['photo'])) {
                    Storage::delete('public/' . $userData['photo']);
                }

                Log::error('User creation failed: ' . $e->getMessage());
                return back()
                    ->withInput()
                    ->with('error', 'Failed to create user. Please try again.');
            }

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Обработка ошибок валидации
            return back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            // Ловим все остальные исключения
            Log::error('Unexpected error in UserController@store: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again later.');
        }
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
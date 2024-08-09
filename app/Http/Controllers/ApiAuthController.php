<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\User;
use App\Enums\UserGender;
use App\Rules\DoesntContainEmojis;
use App\Rules\FullnameRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Outl1ne\NovaMediaHub\Http\Controllers\MediaHubController;
use Outl1ne\NovaMediaHub\MediaHub;
use Outl1ne\NovaMediaHub\Models\Media;

class ApiAuthController extends Controller
{
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(['success' => __("Sign out successful!")], 200);
        } else {
            return response()->json(['error' => __("Api has gone wrong!")], 500);
        }
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_code' => ['required', 'max:50', 'unique:users', new DoesntContainEmojis()],
                'password' => 'required|string|confirmed|min:8',
            ], [], [
                'employee_code' => __('Employee Code'),
                'password' => __('Password'),
                'password_confirmation' => __('Password Confirmation')
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 422);
            }

            $user = User::create([
                'employee_code' => $request->get('employee_code'),
                'password' => Hash::make($request->get('password')),
                'status' => false,
                'type' => UserType::Mobile
            ]);

            return response()->json([
                'success' => __('Registered successfully!'),
                'user' => [
                    'employee_code' => $user->getAttribute('employee_code')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __('Registration failed!'),
            ], 500);
        }
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = auth('api')?->user();

            if (empty($user)) {
                return response()->json(['message' => __('Unauthorized !')], 401);
            }

            $fields = collect([
                'employee_code' => __('Employee Code'),
                'name' => __('Name User'),
                'dob' => __('Date Of Birth'),
                'gender' => __('Gender'),
                'phone' => __('Phone Number'),
                'username' => 'CCCD/CMND',
                'email' => __('Email'),
                'position' => __('Position'),
                'department' => __('Department'),
                'factory_name' => __('Factory'),
                'avatar' => __('Avatar'),
            ]);

            $validator = Validator::make($request->all(),
                [
                    'employee_code' => ['nullable',
                        fn (string $attribute, mixed $value, \Closure $fail) =>
                            $value === $user->getAttribute('employee_code') ?:
                                $fail(__('The Employee Code cannot be changed.'))
                    ],
                    'name' => ['nullable', 'max:50', new FullnameRule()],
                    'dob' => 'nullable|date|date_format:Y-m-d|before:today',
                    'gender' => 'nullable|in:' . implode(',', UserGender::getKeys()),
                    'phone' => ['nullable', 'regex:/((\+|)84|0[3|5|7|8|9])+([0-9]{8,9})\b/'],
                    'username' => 'nullable|numeric|digits_between:9,12',
                    'email' => 'nullable|email',
                    'position' => 'nullable',
                    'department' => 'nullable',
                    'factory_name' => 'nullable',
                    'avatar' => [
                        'nullable',
                        File::image()->max(12 * 1024)
                    ]
                ],
                [
                    'before' => __('The :attribute field must be a day before the today.'),
                    'date_format' => __('The :attribute does not match the date format.'),
                ],
                $fields->toArray()
            );
            if ($validator->fails()) {
                return response()->json($validator->messages(), 422);
            }

            if ($request->file('avatar')) {
                $media = MediaHub::fileHandler()->save($request->file('avatar'));
                $user->setAttribute('avatar', $media->getAttribute('id'));
                $user->setAttribute('avatar_url', $media->getAttribute('url'));
            }

            $required_fields = collect(['name', 'employee_code', 'dob', 'gender', 'phone', 'username']);

            $data = collect($request->all())->filter(
                fn($value, $key) => $key != 'avatar' &&
                    $fields->keys()->contains($key) &&
                    (($required_fields->contains($key) && $value) || $value)
            );

            $data->each(fn($value, $key) => $user->setAttribute($key, $value));
            $user->save();

            return response()->json([
                'success' => __('Successfully updated!'),
                'user' => [
                    'employee_code' => $user->getAttribute('employee_code'),
                    'name' => $user->getAttribute('name'),
                    'gender' => $user->getAttribute('gender'),
                    'dob' => $user->getAttribute('dob'),
                    'username' => $user->getAttribute('username'),
                    'phone' => $user->getAttribute('phone'),
                    'email' => $user->getAttribute('email'),
                    'department' => $user->getAttribute('department'),
                    'factory_name' => $user->getAttribute('factory_name'),
                    'position' => $user->getAttribute('position'),
                    'avatar' => Media::find($user->getAttribute('avatar'))?->getAttribute('url')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Update failed!')], 500);
        }
    }

    public function show(Request $request): \Illuminate\Http\JsonResponse|array
    {
        $user = auth('api')?->user();

        if (empty($user)) {
            return response()->json(['message' => __('Unauthorized !')], 401);
        }

        return [
            'employee_code' => $user->getAttribute('employee_code'),
            'name' => $user->getAttribute('name'),
            'gender' => $user->getAttribute('gender'),
            'dob' => $user->getAttribute('dob'),
            'username' => $user->getAttribute('username'),
            'phone' => $user->getAttribute('phone'),
            'email' => $user->getAttribute('email'),
            'department' => $user->getAttribute('department'),
            'factory_name' => $user->getAttribute('factory_name'),
            'position' => $user->getAttribute('position'),
            'avatar' => Media::find($user->getAttribute('avatar'))?->getAttribute('url')
        ];
    }
    public function tokenExist() {
        return ['is_existed' => (boolean)auth('api')?->user()];
    }
}

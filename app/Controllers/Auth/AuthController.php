<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Auth;

use ThemeHub\Core\{Controller, Auth, Database, Session, Validator};
use ThemeHub\Models\UserModel;

final class AuthController extends Controller
{
    public function login(): string
    {
        if (auth_check()) {
            return $this->redirect('/');
        }

        return $this->view('auth.login');
    }

    public function doLogin(): string
    {
        csrf_verify();

        $email = $this->input('email');
        $password = $this->input('password');

        if (empty($email) || empty($password)) {
            return $this->redirect('/login')->with('error', 'Please fill in all fields');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return $this->redirect('/login')->with('error', 'Your account is not active');
            }

            unset($user['password']);
            Session::set('user', $user);
            Session::regenerate(true);

            $userModel->update($user['id'], [
                'last_login_at' => date('Y-m-d H:i:s'),
                'last_login_ip' => client_ip(),
            ]);

            log_message('info', "User logged in: {$user['email']}");

            $redirect = match ($user['role']) {
                'admin' => '/admin',
                'vendor' => '/vendor',
                default => '/',
            };

            return $this->redirect($redirect)->with('success', 'Welcome back, ' . $user['name']);
        }

        log_message('warning', "Failed login attempt for: {$email}");
        return $this->redirect('/login')->with('error', 'Invalid credentials');
    }

    public function register(): string
    {
        if (auth_check()) {
            return $this->redirect('/');
        }

        return $this->view('auth.register');
    }

    public function doRegister(): string
    {
        csrf_verify();

        $rules = [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'required|in:customer,vendor',
        ];

        $validator = new Validator($this->request->all(), $rules);
        $validation = $validator->validate();

        if (!$validation['valid']) {
            $_SESSION['old'] = $this->request->all();
            $_SESSION['errors'] = $validation['errors'];
            return $this->redirect('/register')->with('error', 'Please fix the errors');
        }

        $data = $this->request->all();

        $userModel = new UserModel();
        $userId = $userModel->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'customer',
            'status' => 'active',
        ]);

        $user = $userModel->find($userId);
        unset($user['password']);
        Session::set('user', $user);
        Session::regenerate(true);

        log_message('info', "New user registered: {$user['email']}");

        $redirect = match ($user['role']) {
            'vendor' => '/vendor',
            default => '/',
        };

        return $this->redirect($redirect)->with('success', 'Account created successfully!');
    }

    public function logout(): string
    {
        Auth::logout();
        return $this->redirect('/')->with('success', 'You have been logged out');
    }

    public function forgotPassword(): string
    {
        return $this->view('auth.forgot-password');
    }

    public function resetPassword(): string
    {
        csrf_verify();

        $email = $this->input('email');

        if (empty($email)) {
            return $this->redirect('/forgot-password')->with('error', 'Please enter your email');
        }

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if ($user) {
            $token = random_string(64);

            Database::connection()->prepare("UPDATE users SET remember_token = ? WHERE id = ?")
                ->execute([$token, $user['id']]);

            send_email($user['email'], 'Reset Your Password', 'emails.reset-password', [
                'name' => $user['name'],
                'token' => $token,
            ]);

            log_message('info', "Password reset requested for: {$user['email']}");
        }

        return $this->redirect('/login')->with('success', 'If an account exists, you will receive a password reset link');
    }

    public function demoLogin(): string
    {
        $role = $this->input('role', 'customer');

        $userModel = new UserModel();
        $user = $userModel->findBy('role', $role);

        if (!$user) {
            return $this->redirect('/login')->with('error', 'Demo account not found');
        }

        unset($user['password']);
        Session::set('user', $user);
        Session::regenerate(true);

        log_message('info', "Demo login as: {$user['role']}");

        $redirect = match ($user['role']) {
            'admin' => '/admin',
            'vendor' => '/vendor',
            default => '/',
        };

        return $this->redirect($redirect)->with('success', 'Demo login successful!');
    }
}

<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Customer;

use ThemeHub\Core\{Controller, Auth};
use ThemeHub\Models\{Order, Wishlist, Download, SupportTicket, UserModel};

/**
 * Customer Controller
 */
final class CustomerController extends Controller
{
    public function dashboard(): string
    {
        auth_require('customer');
        
        $orders = (new Order())->where('user_id', (string)auth_user()['id']);
        $wishlist = (new Wishlist())->where('user_id', (string)auth_user()['id']);
        $downloads = (new Download())->where('user_id', (string)auth_user()['id']);
        $tickets = (new SupportTicket())->where('user_id', (string)auth_user()['id']);
        
        return $this->view('customer.dashboard', [
            'orders' => $orders,
            'wishlist' => $wishlist,
            'downloads' => $downloads,
            'tickets' => $tickets,
        ]);
    }

    public function orders(): string
    {
        auth_require('customer');
        
        $orders = (new Order())->where('user_id', (string)auth_user()['id']);
        
        return $this->view('customer.orders', ['orders' => $orders]);
    }

    public function order(string $id): string
    {
        auth_require('customer');
        
        $order = (new Order())->find((int)$id);
        
        if (!$order || $order['user_id'] != auth_user()['id']) {
            abort(404);
        }
        
        return $this->view('customer.order', ['order' => $order]);
    }

    public function downloads(): string
    {
        auth_require('customer');
        
        $downloads = (new Download())->where('user_id', (string)auth_user()['id']);
        
        return $this->view('customer.downloads', ['downloads' => $downloads]);
    }

    public function wishlist(): string
    {
        auth_require('customer');
        
        $items = (new Wishlist())->where('user_id', (string)auth_user()['id']);
        
        return $this->view('customer.wishlist', ['items' => $items]);
    }

    public function profile(): string
    {
        auth_require();
        
        return $this->view('customer.profile', ['user' => auth_user()]);
    }

    public function updateProfile(): string
    {
        csrf_verify();
        auth_require();
        
        $data = $this->request->all();
        
        (new UserModel())->update(auth_user()['id'], [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        
        return $this->redirect('/customer/profile')->with('success', 'Profile updated successfully');
    }

    public function password(): string
    {
        auth_require();
        
        return $this->view('customer.password');
    }

    public function updatePassword(): string
    {
        csrf_verify();
        auth_require();
        
        $currentPassword = $this->input('current_password');
        $newPassword = $this->input('new_password');
        $confirmPassword = $this->input('confirm_password');
        
        $user = (new UserModel())->find(auth_user()['id']);
        
        if (!password_verify($currentPassword, $user['password'])) {
            return $this->redirect('/customer/password')->with('error', 'Current password is incorrect');
        }
        
        if ($newPassword !== $confirmPassword) {
            return $this->redirect('/customer/password')->with('error', 'Passwords do not match');
        }
        
        (new UserModel())->update(auth_user()['id'], [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
        
        return $this->redirect('/customer/profile')->with('success', 'Password updated successfully');
    }

    public function tickets(): string
    {
        auth_require();
        
        $tickets = (new SupportTicket())->where('user_id', (string)auth_user()['id']);
        
        return $this->view('customer.tickets', ['tickets' => $tickets]);
    }

    public function createTicket(): string
    {
        auth_require();
        
        return $this->view('customer.create-ticket');
    }

    public function storeTicket(): string
    {
        csrf_verify();
        auth_require();
        
        $data = $this->request->all();
        
        (new SupportTicket())->create([
            'user_id' => auth_user()['id'],
            'subject' => $data['subject'],
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'open',
        ]);
        
        return $this->redirect('/customer/tickets')->with('success', 'Ticket created successfully');
    }

    public function apiTokens(): string
    {
        auth_require();
        
        $token = Auth::generateApiToken(auth_user()['id']);
        
        return $this->view('customer.api-tokens', ['token' => $token]);
    }
}

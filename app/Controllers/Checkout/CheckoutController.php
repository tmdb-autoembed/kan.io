<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Checkout;

use ThemeHub\Core\Controller;
use ThemeHub\Models\{Order, OrderItem, Cart, Coupon, Payment, LicenseKey};

/**
 * Checkout Controller
 */
final class CheckoutController extends Controller
{
    public function checkout(): string
    {
        auth_require();
        
        $cartItems = Cart::getItems((string)auth_user()['id']);
        
        if (empty($cartItems)) {
            return $this->redirect('/cart')->with('error', 'Your cart is empty');
        }
        
        $subtotal = Cart::getTotal((string)auth_user()['id']);
        $tax = $subtotal * (setting('tax_rate', 0) / 100);
        $discount = 0;
        
        // Apply coupon if present
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::findByCode($couponCode);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $total = $subtotal + $tax - $discount;
        
        return $this->view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'coupon' => $coupon,
        ]);
    }

    public function applyCoupon(): string
    {
        csrf_verify();
        auth_require();
        
        $code = strtoupper($this->input('coupon_code', ''));
        
        $coupon = Coupon::findByCode($code);
        
        if (!$coupon || !$coupon->isValid()) {
            return $this->redirect('/checkout')->with('error', 'Invalid or expired coupon code');
        }
        
        $subtotal = Cart::getTotal((string)auth_user()['id']);
        
        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            return $this->redirect('/checkout')->with('error', 'Minimum amount not met for this coupon');
        }
        
        Session::set('coupon_code', $code);
        
        return $this->redirect('/checkout')->with('success', 'Coupon applied successfully');
    }

    public function placeOrder(): string
    {
        csrf_verify();
        auth_require();
        
        $cartItems = Cart::getItems((string)auth_user()['id']);
        
        if (empty($cartItems)) {
            return $this->redirect('/cart')->with('error', 'Your cart is empty');
        }
        
        $subtotal = Cart::getTotal((string)auth_user()['id']);
        $tax = $subtotal * (setting('tax_rate', 0) / 100);
        $discount = 0;
        
        $coupon = null;
        $couponCode = Session::get('coupon_code');
        if ($couponCode) {
            $coupon = Coupon::findByCode($couponCode);
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $total = $subtotal + $tax - $discount;
        
        // Create order
        $orderModel = new Order();
        $orderId = $orderModel->create([
            'user_id' => auth_user()['id'],
            'order_number' => 'TH-' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $this->input('payment_method', 'stripe'),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'billing_name' => $this->input('billing_name'),
            'billing_email' => $this->input('billing_email'),
            'billing_phone' => $this->input('billing_phone'),
            'billing_address' => $this->input('billing_address'),
            'billing_city' => $this->input('billing_city'),
            'billing_state' => $this->input('billing_state'),
            'billing_country' => $this->input('billing_country'),
            'billing_postal_code' => $this->input('billing_postal_code'),
        ]);
        
        // Create order items
        foreach ($cartItems as $item) {
            $theme = (new \ThemeHub\Models\Theme())->find((int)$item['theme_id']);
            
            (new OrderItem())->create([
                'order_id' => $orderId,
                'theme_id' => $item['theme_id'],
                'theme_name' => $theme['name'],
                'theme_price' => $item['price'],
                'license_type' => 'regular',
                'quantity' => $item['quantity'],
            ]);
        }
        
        // Create license keys
        foreach ($cartItems as $item) {
            $licenseKey = strtoupper(chunk_split(bin2hex(random_bytes(16)), 4, '-'));
            
            (new LicenseKey())->create([
                'order_id' => $orderId,
                'user_id' => auth_user()['id'],
                'theme_id' => $item['theme_id'],
                'key' => $licenseKey,
                'status' => 'active',
                'activations_limit' => 1,
            ]);
        }
        
        // Create payment record
        (new Payment())->create([
            'order_id' => $orderId,
            'user_id' => auth_user()['id'],
            'gateway' => $this->input('payment_method', 'stripe'),
            'amount' => $total,
            'status' => 'pending',
        ]);
        
        // Update coupon usage
        if ($coupon) {
            $coupon->incrementUsage();
        }
        
        // Clear cart
        Database::connection()->prepare("DELETE FROM cart_items WHERE user_id = ?")
            ->execute([auth_user()['id']]);
        
        // Clear coupon from session
        Session::forget('coupon_code');
        
        // Send confirmation email
        send_email(auth_user()['email'], 'Order Confirmation', 'order-confirmation', [
            'name' => auth_user()['name'],
            'order_number' => $orderModel->find($orderId)['order_number'],
            'total' => $total,
        ]);
        
        return $this->redirect('/order/' . $orderId)->with('success', 'Order placed successfully');
    }

    public function order(int $id): string
    {
        auth_require();
        
        $order = (new Order())->find($id);
        
        if (!$order || $order['user_id'] != auth_user()['id']) {
            abort(404);
        }
        
        $items = (new OrderItem())->where('order_id', (string)$id);
        
        return $this->view('checkout.order', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function download(string $id): void
    {
        auth_require();
        
        $licenseKey = (new LicenseKey())->find((int)$id);
        
        if (!$licenseKey || $licenseKey['user_id'] != auth_user()['id']) {
            abort(404);
        }
        
        $theme = (new \ThemeHub\Models\Theme())->find((int)$licenseKey['theme_id']);
        
        if (!$theme || !$theme['download_file']) {
            abort(404);
        }
        
        // Log download
        (new \ThemeHub\Models\Download())->create([
            'user_id' => auth_user()['id'],
            'order_id' => $licenseKey['order_id'],
            'theme_id' => $theme['id'],
            'ip_address' => client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ]);
        
        // Activate license key
        $licenseKey->activate();
        
        // Download file
        $filePath = PUBLIC_PATH . '/uploads/themes/' . $theme['download_file'];
        
        if (is_file($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $theme['slug'] . '.zip"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        }
        
        abort(404, 'Download file not found');
    }

    public function success(): string
    {
        return $this->view('checkout.success');
    }
}

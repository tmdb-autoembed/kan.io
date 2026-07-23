<?php
namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;

class ApiController extends Controller
{
    public function products(Request $request): string
    {
        return $this->json(['data' => (new Product())->browse($request->input)]);
    }

    public function categories(): string
    {
        return $this->json(['data' => (new Category())->all('status="active"')]);
    }

    public function vendor(Request $request, string $slug): string
    {
        $vendor = (new Vendor())->all('slug=?', [$slug])[0] ?? null;
        return $this->json(['data' => $vendor]);
    }

    public function login(Request $request): string
    {
        if (!Auth::attempt($request->get('email'), $request->get('password'))) {
            return $this->json(['message' => 'Invalid credentials'], 422);
        }

        $token = bin2hex(random_bytes(32));
        Database::pdo()
            ->prepare("insert into api_tokens(user_id,token_hash,expires_at) values(?,?,datetime('now', '+30 days'))")
            ->execute([Auth::user()['id'], hash('sha256', $token)]);

        return $this->json(['token' => $token, 'user' => Auth::user()]);
    }

    public function order(Request $request): string
    {
        $auth = $request->headers['Authorization'] ?? '';
        $user = Auth::apiUser(str_replace('Bearer ', '', $auth));
        if (!$user) {
            return $this->json(['message' => 'Unauthenticated'], 401);
        }

        $id = (new Order())->createFromCart((int) $user['id'], $request->get('items', []), $request->get('payment_method', 'cod'));
        return $this->json(['order_id' => $id], 201);
    }

    public function payment(Request $request, string $reference): string
    {
        $order = (new Order())->all('number=?', [$reference])[0] ?? null;
        return $this->json(['data' => $order]);
    }
}

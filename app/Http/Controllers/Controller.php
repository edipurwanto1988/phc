<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Cleaner hanya boleh mengakses order yang ditugaskan kepadanya.
     */
    protected function authorizeOrderAccess(Order $order): void
    {
        $user = Auth::user();

        if ($user && $user->role && $user->role->name === 'Cleaner') {
            abort_unless($order->assignments()->where('user_id', $user->id)->exists(), 404);
        }
    }
}

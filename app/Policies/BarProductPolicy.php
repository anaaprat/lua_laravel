<?php

namespace App\Policies;

use App\Models\BarProduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarProductPolicy
{
    public function update(User $user, BarProduct $barProduct): bool
    {
        return $user->id === $barProduct->user_id;
    }

    public function delete(User $user, BarProduct $barProduct): bool
    {
        return $user->id === $barProduct->user_id;
    }
}

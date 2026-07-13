<?php

namespace App\Policies;

use App\Models\Rider;
use App\Models\Stock;
use App\Models\User;

class StockPolicy
{
    public function before(User|Rider $actor): ?bool
    {
        return $actor instanceof User ? true : null;
    }

    public function updateAvailability(User|Rider $actor, Stock $stock): bool
    {
        if (! $actor instanceof Rider) {
            return false;
        }

        $stock->loadMissing('menu');

        return $stock->outlet_id === $actor->outlet_id
            && $stock->menu?->category !== 'Literan';
    }
}

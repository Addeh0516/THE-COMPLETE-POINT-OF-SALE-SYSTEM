<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'price', 'stock_quantity', 'image'])]
class Product extends Model
{
    protected function casts(): array
    {
        return ['price' => 'decimal:2'];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}

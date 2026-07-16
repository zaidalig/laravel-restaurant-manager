<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'menu_category_id', 'name', 'description', 'price',
        'prep_time', 'is_vegetarian', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_vegetarian' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}

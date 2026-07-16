<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'description', 'status'];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}

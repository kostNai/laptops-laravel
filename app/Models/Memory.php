<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memory extends Model
{
    protected $fillable = (['manufacturer','type','size','product_id']);

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }
    public function products():HasMany{
        return $this->hasMany(Product::class);
    }
    use HasFactory;
}

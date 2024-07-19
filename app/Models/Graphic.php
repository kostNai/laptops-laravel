<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Graphic extends Model
{
    protected $fillable = (['manufacturer','series','model','type','product_id']);

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }
    public function products():HasMany{
        return $this->hasMany(Product::class,'graphic_id','id');
    }
    use HasFactory;
}

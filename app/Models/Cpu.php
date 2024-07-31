<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cpu extends Model
{
    protected $fillable = (['manufacturer','series','model','cores_value','frequency','product_id','slug']);

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }
    public function products():HasMany{
        return $this->hasMany(Product::class,'cpu_id','id');
    }
    use HasFactory;
}

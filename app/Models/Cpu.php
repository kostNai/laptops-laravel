<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cpu extends Model
{
    protected $fillable = (['manufacturer','series','model','cores_value','frequency','product_id']);

    public function product():BelongsTo{
        return $this->belongsTo(Product::class);
    }
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable=(['manufacturer','price','image','name','model','color','weight','multimedia','dimensions','os','description','sales_count','cpu_id','display_id','memory_id','ram_id','graphic_id']);

    public function cpu():HasOne{
        return $this->hasOne(Cpu::class,'id','cpu_id');
    }
    public function display():HasOne{
        return $this->hasOne(Display::class,'id','display_id');
    }
    public function memory():HasOne{
        return $this->hasOne(Memory::class,'id','memory_id');
    }
    public function ram():HasOne{
        return $this->hasOne(Ram::class,'id','ram_id');
    }
    public function graphic():HasOne{
        return $this->hasOne(Graphic::class,'id','graphic_id');
    }
    use HasFactory;
}

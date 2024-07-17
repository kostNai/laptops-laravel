<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable=(['image','name','model','color','weight','multimedia','dimensions','os','cpu_id','display_id','memory_id','ram_id','graphic_id']);

    public function cpu():HasOne{
        return $this->hasOne(Cpu::class);
    }
    public function display():HasOne{
        return $this->hasOne(Display::class);
    }
    public function memory():HasOne{
        return $this->hasOne(Memory::class);
    }
    public function ram():HasOne{
        return $this->hasOne(Ram::class);
    }
    public function graphic():HasOne{
        return $this->hasOne(Graphic::class);
    }
    use HasFactory;
}

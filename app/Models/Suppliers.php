<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suppliers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
      'id',
      'fullname',
      'phone',
      'image',
      'status',
    ];

    public function products()
    {
      return $this->hasMany(Product::class,'supplier_id');
    }

    public function payments(){
      return $this->morphMany(Payment::class, 'payable');
    }
}

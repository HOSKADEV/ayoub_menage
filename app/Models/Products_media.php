<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products_media extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products_media';
    protected $fillable = [
      'id',
      'products_id',
      'images',
      'videos',
      'image',
      'created_at',
      'updated_at',
      'deleted_at',
    ];

    public function product()
    {
      return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorySubcategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_subcategory';

    protected $fillable = [
      'id',
      'category_id',
      'subcategory_id',
    ];

    public function category()
    {
      return $this->belongsTo(category::class);
    }

    public function subcategory()
    {
      return $this->belongsTo(subcategory::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = "settings";

    protected $fillable = [
        'id',
        'price_max',
        'bank_account_bankily',
        'bank_account_sedad',
        'bank_account_bimbank',
        'bank_account_masrfy',
    ];

    protected $casts = [
      'price_max' => 'double',
      'bank_account_bankily' => 'integer',
      'bank_account_sedad'   => 'integer',
      'bank_account_bimbank' => 'integer',
      'bank_account_masrfy'  => 'integer',
    ];
}

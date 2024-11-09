<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'salesperson_id',
    ];

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }
}

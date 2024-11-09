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

    protected static function booted()
    {
        static::created(function ($lead) {
            \App\Models\LeadStatus::create([
                'leads_id' => $lead->id,
                'master_status_id' => 1,
            ]);
        });
    }

    public function salesperson()
    {
        return $this->belongsTo(User::class, 'salesperson_id');
    }

    public function leadStatus()
    {
        return $this->hasMany(LeadStatus::class, 'leads_id');
    }
}

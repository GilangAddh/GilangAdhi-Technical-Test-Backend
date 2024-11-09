<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStatus extends Model
{
    use HasFactory;

    protected $table = 'master_status';

    protected $fillable = [
        'name',
    ];

    public function leadStatus()
    {
        return $this->hasMany(LeadStatus::class, 'master_status_id');
    }
}

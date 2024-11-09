<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    use HasFactory;

    protected $table = 'leads_status';

    protected $fillable = [
        'leads_id',
        'master_status_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'leads_id');
    }

    public function masterStatus()
    {
        return $this->belongsTo(MasterStatus::class, 'master_status_id');
    }
}

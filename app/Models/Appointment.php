<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = ['consultant', 'date', 'time_slot', 'name', 'details'];

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }
}

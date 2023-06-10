<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class CoinSync extends Model
{
    use HasFactory;
	const CREATED_AT = 'started_at';

    const UPDATED_AT = null;

    protected $casts = [
        'errors' => 'array',
    ];

    protected $dates = [
        'started_at',
        'completed_at',
    ];
	
	protected $guarded = [];
	
	
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
	
	public function getCompletedInSecondsAttribute(): ?int
    {
        return $this->completed_at ? (int)$this->started_at->diffInSeconds($this->completed_at) : null;
    }
}

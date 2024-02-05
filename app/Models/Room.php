<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Element;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seating_capacity',
        'type_id'
    ];

    /**
     * Get the roomModules that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roomModules(): BelongsTo
    {
        return $this->belongsTo(RoomModule::class, 'id', 'room_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Room;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock'
    ];

    /**
     * The rooms that belong to the Element
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_elements', 'element_id', 'room_id')->with('room_stock');
    }
}

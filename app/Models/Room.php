<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * The elements that belong to the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function elements(): BelongsToMany
    {
        return $this->belongsToMany(Element::class, 'room_elements', 'room_id', 'element_id')->withPivot('room_stock');
    }
}

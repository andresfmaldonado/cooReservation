<?php

namespace App\Services;

use App\Models\Room_element;

class ElementService
{
    public static function getElementStockUsed($elementId, $roomId) {
        return Room_element::selectRaw('sum(room_stock) as room_stock_sum')
            ->where('element_id', $elementId)
            ->where('room_id', '!=', $roomId)
            ->get();
    }
}

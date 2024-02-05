<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Room;
use App\Http\Requests\StoreElementRequest;
use App\Http\Requests\UpdateElementRequest;
use App\Http\Requests\StoreRoomElementsRequest;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\RedirectResponse;
use App\Services\ElementService;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder, String $id)
    {
        $elements = Element::all();

        if (request()->ajax()) {
            try {
                return DataTables::of($elements)->addColumn('actions', function ($item) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('edit-element', $item->id).'">Edit element</a>
                            </button>';
                })
                ->rawColumns(['actions'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $table = $builder->columns([
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'actions', 'footer' => 'Actions']
                ]);
        return view('element.list-elements', compact('table','elements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('element.create-element');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreElementRequest $request)
    {
        try {
            Element::create([
                'name' => $request->name,
                'stock' => $request->stock
            ]);

            return back()->with('status', 'element-created');
        } catch (\Throwable $th) {
            return back()->with('status', 'element-not-created');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Int $id)
    {
        try {
            $element = Element::find($id);
            return view('element.edit-element', compact('element'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElementRequest $request, int $id)
    {
        try {
            Element::find($id)->update([
                'name' => $request->name,
                'stock' => $request->stock
            ]);

            return back()->with('status', 'element-updated');
        } catch (\Throwable $th) {
            return back()->with('status', 'element-not-updated');
        }
    }

    private function validateStock($elements, $room_id) {
        $flag = true;
        $messages = [];
        foreach ($elements as $element) {
            $elementData = Element::findOrFail($element['element_id']);
            $originalStock = $elementData['stock'];

            $elementStockUsed = ElementService::getElementStockUsed($element['element_id'], $room_id);

            $newStock = $elementStockUsed[0]->room_stock_sum + $element['room_stock'];
            if ($newStock > $originalStock) {
                $availableStock = $originalStock - $elementStockUsed[0]->room_stock_sum;
                $messages[] = "The $elementData->name stock ".$element['room_stock']." has exhausted. Avalaible stock: $availableStock";
                $flag = false;
            }
        }

        return [
            'messages' => $messages,
            'flag' => $flag
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Type;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            try {
                return DataTables::of(Room::query())->addColumn('actions', function ($item) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('edit-room', $item->id).'">Edit</a>
                            </button>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" onclick="deleteRoom('.$item->id.')">Delete</button>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >
                                <a href="'.route('list-elements', $item->id).'">Elements</a>
                            </button>';
                })
                ->rawColumns(['actions'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $table = $builder->columns([
                    ['data' => 'id', 'footer' => 'Id'],
                    ['data' => 'name', 'footer' => 'Name'],
                    ['data' => 'seating_capacity', 'footer' => 'Seating Capacity'],
                    // ['data' => 'type', 'footer' => 'Type'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'actions', 'footer' => 'Actions']
                ]);

        return view('room.list-room', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $types = Type::all();
            return view('room.create-room', compact('types'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request): RedirectResponse
    {
        try {
            Room::create([
                'name' => $request->name,
                'seating_capacity' => $request->seating_capacity,
                'type_id' =>  $request->type_id
            ]);

            return back()->with('status', 'room-created');
        } catch (\Throwable $th) {
            return back()->with('status', 'room-not-created');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        try {
            $room = Room::find($id);
            $types = Type::all();
            return view('room.edit-room', compact('room', 'types'));
        } catch (\Throwable $th) {
            return response(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, String $id): RedirectResponse
    {
        try {
            Room::find($id)->update([
                'name' => $request->name,
                'seating_capacity' => $request->seating_capacity,
                'type_id' => $request->type_id,
            ]);

            return back()->with('status', 'room-updated');
        } catch (\Throwable $th) {
            return back()->with('status', 'room-not-updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            Room::find($request->id)->delete();

            return response(200);
        } catch (\Throwable $th) {
            return response(500);
        }
    }
}

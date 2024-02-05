<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Type;
use App\Models\Reservations;
use App\Models\RoomModule;
use App\Models\Element;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            try {
                if (request()->user()->is_super_admin) {
                    $reservations = Reservation::query()->with('room')->get();
                } else {
                    $reservations = Reservation::where('user_id', request()->user()->id)->with('room')->get();
                }

                return DataTables::of($reservations)->addColumn('actions', function ($item) {
                    return '<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('show-reservation', $item->id).'">Show</a>
                            </button>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <a href="'.route('edit-reservation', $item->id).'">Edit</a>
                            </button>
                            <button class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" onclick="deleteReservation('.$item->id.')">Delete</button>';
                })
                ->addColumn('room', function($item) {
                    return $item->room->name;
                })
                ->rawColumns(['actions', 'room'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $table = $builder->columns([
                    ['data' => 'id', 'footer' => 'Id'],
                    ['data' => 'room', 'footer' => 'Room'],
                    ['data' => 'created_at', 'footer' => 'Created At'],
                    ['data' => 'updated_at', 'footer' => 'Updated At'],
                    ['data' => 'actions', 'footer' => 'Actions']
                ]);

        return view('reservation.list-reservations', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Builder $builder)
    {
        $types = Type::all();
        $rooms = Room::all();
        $roomModules = RoomModule::all();
        $elements = Element::all();

        if (request()->ajax()) {
            try {
                return DataTables::of($elements)->addColumn('actions', function ($item) {
                    return '';
                })
                ->rawColumns(['actions'])->toJson();
            } catch (\Throwable $th) {
                return response(500);
            }
        }

        $tableElements = $builder->columns([
            ['data' => 'id', 'footer' => 'Id'],
            ['data' => 'name', 'footer' => 'Name'],
            ['data' => 'actions', 'footer' => 'Actions']
        ]);

        return view('reservation.create-reservation', compact('types', 'rooms', 'roomModules', 'elements', 'tableElements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request): RedirectResponse
    {
        try {
            $reservation = [
                'user_id' => request()->user()->id,
                'people_number' => $request->people_number,
                'initial_date' => $request->initial_date,
                'final_date' => $request->final_date,
                'status_id' => env('STATUS_ACTIVE_ID'),
            ];

            if (isset($request->room_module_id)) {
                $roomModule = RoomModule::find($request->room_module_id);
                $roomModule->room;
                $reservation['room_id'] = $roomModule->room->id;
                $reservation['room_module_id'] = $request->room_module_id;
            } else {
                $reservation['room_id'] = $request->room_id;
            }

            Reservation::create($reservation);

            return back()->with('status', 'reservation-created');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('status', 'reservation-not-created');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}

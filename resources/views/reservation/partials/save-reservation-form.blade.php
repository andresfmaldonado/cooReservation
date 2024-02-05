<section>
    <!-- Types -->
    <div class="mt-4">
        <x-input-label for="type_id" :value="__('Room Type')"  />

        <select id="type_id" name="type_id" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="showOrHideRoomsList()">
            @foreach ($types as $type)
                <option value="{{$type->id}}">{{ucwords($type->name)}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
    </div>

    <!-- Rooms -->
    <div class="mt-4">
        <x-input-label for="room_id" :value="__('Room')"  />

        <select id="room_id" name="room_id" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="searchModules()">
            @foreach ($rooms as $room)
                <option value="{{$room->id}}">{{ucwords($room->name)}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
    </div>

    <!-- Room Modules -->
    <div class="mt-4">
        <x-input-label for="room_module_id" :value="__('Room Modules')"  />

        <select id="room_module_id" name="room_module_id" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            @foreach ($roomModules as $room)
                <option value="{{$room->id}}">{{ucwords($room->name)}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('room_module_id')" class="mt-2" />
    </div>

    <!-- People Number -->
    <div class="mt-4">
        <x-input-label for="people_number" :value="__('People number')" />
        <x-text-input id="people_number" class="block mt-1 w-full" type="number" name="people_number" required autofocus autocomplete="people_number" />
        <x-input-error :messages="$errors->get('people_number')" class="mt-2" />
    </div>

    <!-- Initial Date -->
    <div class="mt-4">
        <x-input-label for="initial_date" :value="__('Initial Date')" />
        <input id="initial_date" class="block mt-1 w-full" type="date" name="initial_date" required autofocus autocomplete="initial_date" />
        <input id="initial_time" class="block mt-1 w-full" type="time" name="initial_time" required autofocus autocomplete="initial_time" />
        <x-input-error :messages="$errors->get('initial_date')" class="mt-2" />
    </div>

    <!-- Initial Date -->
    <div class="mt-4">
        <x-input-label for="final_date" :value="__('Final Date')" />
        <input id="final_date" class="block mt-1 w-full" type="date" name="final_date" required autofocus autocomplete="final_date" />
        <input id="final_time" class="block mt-1 w-full" type="time" name="final_time" required autofocus autocomplete="final_time" />
        <x-input-error :messages="$errors->get('final_date')" class="mt-2" />
    </div>
</section>

<script>
    $(document).ready(() => {
        loadReservationData();
        $('#type_id').change();
    });

    function showOrHideRoomsList(room_id = null, room_module_id = null) {
        const type = $('#type_id').val();
        const typeEstandar = @json(env('TYPE_ESTANDAR_ID'));
        if (type == typeEstandar) {
            $('#room_id').prop('disabled', false);
            $('#room_id').val(room_id);
            $('#room_module_id').prop('disabled', true);
        } else {
            $('#room_id').prop('disabled', true);
            $('#room_module_id').prop('disabled', false);
            $('#room_module_id').val(room_module_id);

        }
    }

    function loadReservationData() {
        const reservation = @json(isset($reservation) ? $reservation : '');
        if (reservation) {
            $('#type_id').val(reservation.type_id);
            showOrHideRoomsList(reservation.room_id, reservation.room_module_id);
            $('#people_number').val(reservation.people_number);
            $('#initial_date').val(reservation.initial_date);
            $('#initial_time').val(reservation.initial_time);
            $('#final_date').val(reservation.final_date);
            $('#final_time').val(reservation.final_time);
        }
    }

    function searchModules() {
        const room_id = $('#room_id').val();
        $.ajax({
            url: "/rooms/getRoomModules/"+room_id,
            type:  'GET',
            success: (response) => {
                console.log(response);
            },
            error: (error) => {
                console.log(error);
            }
        });
    }
</script>

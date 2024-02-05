<section>
    <!-- Name -->
    <div class="mt-4">
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Seating Capacity -->
    <div class="mt-4">
        <x-input-label for="seating_capacity" :value="__('Seating Capacity')" />
        <x-number-input id="seating_capacity" class="block mt-1 w-full" name="seating_capacity" required autofocus autocomplete="seating_capacity" />
        <x-input-error :messages="$errors->get('seating_capacity')" class="mt-2" />
    </div>

    <!-- Type -->
    <div class="mt-4">
        <x-input-label for="role_id" :value="__('Type')"  />

        <select id="type_id" name="type_id" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="activeModuleField()">
            @foreach ($types as $type)
                <option value="{{$type->id}}">{{ucwords($type->name)}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type_id')" class="mt-2" />
    </div>

    <!-- Modules -->
    <div class="mt-4">
        <x-input-label for="modules" :value="__('Modules')"  />

        <x-text-input id="modules" name="modules" type="number" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200" disabled/>
        <x-input-error :messages="$errors->get('modules')" class="mt-2" />
    </div>


</section>

<script>
    $(document).ready(() => {
        loadRoomData();
    })
    function loadRoomData() {
        const room = @json(isset($room) ? $room : '');
        const modulesSum = @json(isset($modulesSum) ? $modulesSum:0);
        if (room) {
            $('#name').val(room.name);
            $('#seating_capacity').val(room.seating_capacity);
            $("#type_id").val(room.type_id);
            $("#modules").val(modulesSum);
        }
    }

    function activeModuleField() {
        const typeModulerizedRoom = @json(env('TYPE_MODULERIZED_ID'));
        const typeValue = $('#type_id').val();

        if (typeModulerizedRoom === typeValue) {
            $('#modules').prop('disabled', false);
        } else {
            $('#modules').prop('disabled', true);
        }
    }
</script>

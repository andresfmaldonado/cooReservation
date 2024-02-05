<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Elements') }} - {{ $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="saveRoomElements({{ $room->id }})">Save Room Elements</button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="{{ route('create-element') }}">New Element</a>
            </button>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{ $table->table(['id' => 'elements_table'], true) }}
                    @csrf
                </div>
            </div>
        </div>
    </div>
    {{ $table->scripts() }}

    <script>
        let roomElements = [];

        $(document).ready(function() {
            loadElements();
        });

        function saveRoomElements(room_id) {
            $.ajax({
                url : "{{ route('save-room-elements') }}",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    '_token': '{{ csrf_token() }}',
                    room_id,
                    roomElements
                },
                success: (response) => {
                    console.log(response);
                },
                error: (error) => {
                    console.log(error);
                }
            });
        }

        function changeRoomElements(elementId) {
            const newStock = $('#stock'+elementId).val();
            roomElements.map((element) => {
                if (element.element_id === elementId) {
                    element.room_stock = Number(newStock);
                }

                return element;
            });
            console.log(roomElements, 'changeRoomElements');
        }

        function loadElements() {
            const elements =  @json($elements);
            const roomElementsTemp = @json(isset($roomElements) ? $roomElements : '');
            roomElements = elements.map((element) => {
                const room_stock =  roomElementsTemp.find(e => e.element_id == element.id)?.room_stock ?? 0 ;
                return {
                    element_id: element.id,
                    room_stock
                };
            });
        }
    </script>
</x-app-layout>

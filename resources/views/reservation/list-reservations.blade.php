<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="{{ route('create-reservation') }}">New</a>
            </button>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{ $table->table() }}
                    @csrf
                </div>
            </div>
        </div>
    </div>
    {{ $table->scripts() }}

    <script>
        function deleteReservation(id) {

            const confirm = window.confirm('Are you secure?');
            if (confirm) {
                $.ajax({
                    url: "{{ route('delete-reservation') }}",
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        id
                    },
                    success: (response) => {
                        window.alert('Deleted reservation');
                        location.reload();
                    },
                    error: (error) => {
                        console.log(error);
                    }
                });
            }
        }
    </script>
</x-app-layout>

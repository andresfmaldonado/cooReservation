<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }} - {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="savePermissions({{ $role->id }})">Save Permissions</button>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{ $table->table(['id' => 'permission_table'], true) }}
                    @csrf
                </div>
            </div>
        </div>
    </div>
    {{ $table->scripts() }}

    <script>
        const permissions = [];

        $(document).ready(() => {
            $('#permission_table').on('draw.dt', () => {
                loadActivedPermission();
            });
        });

        function loadActivedPermission() {
            const role = @json($role);
            role.permissions.forEach(element => {
                $('#active'+element.id).prop('checked', true);
                $('#active'+element.id).change();
            });
        }

        function addOrRemovePermission(id) {
            if ($('#active'+id).prop('checked')) {
                permissions.push(id);
            } else {
                const index = permissions.indexOf(id);
                delete permissions[index];
            }
        }

        function savePermissions(roleId) {
            $.ajax({
                url : "{{ route('save-permissions') }}",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    '_token': '{{ csrf_token() }}',
                    permissions,
                    roleId
                },
                success: (response) => {
                    location.reload();
                },
                error: (error) => {

                }
            });
        }
    </script>
</x-app-layout>

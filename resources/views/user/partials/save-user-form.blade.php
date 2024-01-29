<section>
    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Role -->
    <div class="mt-4">
        <x-input-label for="role_id" :value="__('Role')"  />

        <select id="role_id" name="role_id" class="block w-full mt-1 p-2 border rounded-md shadow-sm focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-200">
            @foreach ($roles as $role)
                <option value="{{$role->id}}">{{ucwords($role->name)}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
    </div>

    <!-- Super Admin -->
    <div class="mt-4">
        <x-input-label for="is_super_admin"  :value="__('Is super admin?')" />
        <input type="checkbox" id="is_super_admin" name="is_super_admin" class="mt-1" onchange="changeRole()" />
        <x-input-error :messages="$errors->get('is_super_admin')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" />

        <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password" autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation" autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>
</section>

<script>
    $(document).ready(() => {
        loadUserData();
    });
    function changeRole() {
        const roleIdSelect = $('#role_id');
        // Lógica a ejecutar cuando el checkbox cambie de estado
        if ($('#is_super_admin').prop('checked')) {
            roleIdSelect.val({{env('ADMIN_ROLE_ID')}});
            roleIdSelect.prop('disabled', true);
        } else {
            roleIdSelect.prop('disabled', false);
        }
    }

    function loadUserData() {
        const user = @json(isset($user) ? $user : '');
        if (user) {
            console.log(user);
            $('#name').val(user.name);
            $('#email').val(user.email);
            $('#is_super_admin').prop('checked', user.is_super_admin);
            if (user.is_super_admin === 1) {
                $('#role_id').val({{env('ADMIN_ROLE_ID')}});
                $('#role_id').prop('disabled', true);
            } else {
                $('#role_id').val(user.role_id);
            }
        }
    }
</script>

<section>
    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
</section>

<script>
    $(document).ready(() => {
        loadUserData();
    })
    function loadUserData() {
        const role = @json(isset($role) ? $role : '');
        if (role) {
            $('#name').val(role.name);
        }
    }
</script>

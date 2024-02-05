<section>
    <!-- Name -->
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Stock -->
    <div>
        <x-input-label for="stock" :value="__('Stock')" />
        <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock" required autofocus autocomplete="stock" />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>
</section>

<script>
    $(document).ready(() => {
        loadElementData();
    })
    function loadElementData() {
        const element = @json(isset($element) ? $element : '');
        if (element) {
            $('#name').val(element.name);
            $('#stock').val(element.stock);
        }
    }
</script>

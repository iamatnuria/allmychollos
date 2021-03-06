<x-layout>
    <x-setting heading="Crear mi descuento">
        <form action="/user/discounts" method="POST" enctype="multipart/form-data">
            @csrf

            <x-form.input name="title" />
            <x-form.input name="slug" value="" />
            <x-form.input name="thumbnail" type="file" />
            <x-form.input name="original_price" type="number" step="any" min="10" max="900" />
            <x-form.input name="discounted_price" type="number" step="any" min="10" max="900" />
            <x-form.input name="link" />
{{--            COMMENTED TO ADD WHEN STRIPE IS ADDED --}}
{{--            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3)--}}
{{--                <x-form.checkbox name="premium"/>--}}
{{--            @endif--}}
            <x-form.textarea name="body" />

            <x-form.field>
                <x-form.label name="category" />
                <select name="category_id" id="category_id" class="border border-gray-400 p-2 rounded">

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{$category->name}}</option>
                    @endforeach
                </select>

                <x-form.error name="category" />
            </x-form.field>

            <x-form.button>Publicar</x-form.button>
        </form>
    </x-setting>
</x-layout>

<div class=" mx-4 my-4 w-full">
    <label for="{{ $fieldName }}" class="block font-medium text-sm text-gray-700 w-full mb-1">
        {{ __('word.'.$fieldName) }}
    </label>

    <select id="{{ $fieldName }}" name="{{ $fieldName }}" class="border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full block mt-1" >
        @foreach ($options as $key)
            <option value="{{ $key->id }}"
                {{ (old('$fieldName') ?? $fieldTable->  $fieldName) == $key->id ? 'selected' : '' }}>
                {{ $key->$fieldColumn}} 
            </option>
        @endforeach
    </select>
    
    @error($fieldName)
        <span class="text-sm text-red-600 space-y-1" role="alert">
            {{ $message }}
        </span>
    @enderror

</div>



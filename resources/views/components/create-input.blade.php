
<div class=" mx-4 my-4 w-full">
    <label for="{{ $fieldName }}" class="block font-medium text-sm text-gray-700 w-full mb-1">
        {{ __('word.'.$fieldName) }}
    </label>

    <input  id="{{ $fieldName }}" type="{{ $fieldType }}" class="border-gray-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full block mt-1" 
            @error($fieldName) is-invalid @enderror" name="{{ $fieldName }}"
            value="{{ old('$fieldName') }}"
            required autocomplete="{{ $fieldName }}" autofocus> 

    @error($fieldName)
        <span class="text-sm text-red-600 space-y-1" role="alert">
            {{ $message }}
        </span>
    @enderror
</div> 
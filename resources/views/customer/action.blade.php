<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('customer-show')
        <a href="{{ route('customer.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan
    @can('customer-statement')
        <a href="{{ route('customer.statement', $url_address) }}" class="my-1 mx-1 btn btn-custom-statement">
            {{ __('word.statement') }}
        </a>
    @endcan
    @can('customer-update')
        <a href="{{ route('customer.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('customer-delete')
        <form action="{{ route('customer.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>

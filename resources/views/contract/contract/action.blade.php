<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('contract-show')
        <a href="{{ route('contract.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan
    @can('contract-statement')
        <a href="{{ route('contract.statement', $url_address) }}" class="my-1 mx-1 btn btn-custom-statement">
            {{ __('word.statement') }}
        </a>
    @endcan
    @can('contract-update')
        <a href="{{ route('contract.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('contract-delete')
        <form action="{{ route('contract.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>

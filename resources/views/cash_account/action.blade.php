<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('cash_account-show')
        <a href="{{ route('cash_account.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan
    @can('cash_account-update')
        <a href="{{ route('cash_account.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('cash_account-delete')
        <form action="{{ route('cash_account.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>

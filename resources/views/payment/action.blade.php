<!-- app css-->
<link rel="stylesheet" type="text/css" href="{{ url('/css/app.css') }}" />

<div class="flex ">
    @can('payment-show')
        <a href="{{ route('payment.show', $url_address) }}" class="my-1 mx-1 btn btn-custom-show">
            {{ __('word.view') }}
        </a>
    @endcan
    @can('payment-update')
        <a href="{{ route('payment.edit', $url_address) }}" class="my-1 mx-1 btn btn-custom-edit">
            {{ __('word.edit') }}
        </a>
    @endcan
    @can('payment-delete')
        <form action="{{ route('payment.destroy', $url_address) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit" class="my-1 mx-1 btn btn-custom-delete">
                {{ __('word.delete') }}
            </button>

        </form>
    @endcan
</div>

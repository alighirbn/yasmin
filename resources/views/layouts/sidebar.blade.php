<style>
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #161616;
    }

    .item {
        color: #fff;
        text-align: center;
        font-weight: bold;
        padding-bottom: 30px;
    }

    .item a.sub-btn {
        display: inline-flex;
        align-items: center;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    /* .item :hover {
        padding-top: 10px;
        padding-bottom: 10px;
        border-width: 3px;
        border-style: ridge;
        border-color: white;
        border-right: none;
        border-left: none;
    } */
    .item i {
        margin-right: 10px;
        transition: 0.3s ease;
    }

    .rotate {
        transform: rotate(-180deg);
    }

    .item .sub-menu a {
        padding: 5px;
    }

    .item .sub-menu a:focus {
        border: 2px solid blue;
        border-radius: 12px;
        padding: 5px;
    }
</style>

<!-- Boxicons CSS -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<div id="sidebarmenu" class="py-2 " style="display: none">
    <div class="w-full h-auto p-2 flex justify-center " style="height:100dvh;position: sticky;top:0px; z-index: 1;">
        <img src="{{ URL('images/logo.png') }}">
    </div>
    <div class="w-full h-auto p-2 flex justify-center " style="height:100dvh;position: sticky;top:0px;">

    </div>
    <div class="w-full h-auto p-2 flex justify-center " style="height:100dvh;position: sticky;top:0px;">

    </div>
    <div class="item"><a class="sub-btn"> {{ __('word.Map') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('map.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.contract') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('contract.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.payment') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('payment.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.service') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('service.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.Building') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('building.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.Customer') }} <i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('customer.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.users') }}<i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('user.nav.navigation')
        </div>
    </div>

    <div class="item"><a class="sub-btn"> {{ __('word.roles') }}<i class="bx bx-chevrons-down dropdown"></i> </a>
        <div class="flex flex-col   sub-menu" style="display: none;">
            @include('role.nav.navigation')
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        $('.sub-btn').click(function() {

            $(this).next('.sub-menu').slideToggle();
            $(this).find('.dropdown').toggleClass('rotate');

        });
    });
</script>
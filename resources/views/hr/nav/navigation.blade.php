<style>
    a.underline-active {
        position: relative;
    }

    a.underline-active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        /* Adjust distance from text */
        width: 100%;
        height: 3px;
        /* Thickness of underline */
        background-color: #e8f8ff;
        /* Color of underline */
    }
</style>

<a href="{{ route('hr.employees.index') }}"
    class="px-3 {{ request()->routeIs('hr.employees.index') ? 'underline-active' : '' }}">
    🧑‍💼 الموظفين
</a>
<a href="{{ route('hr.payrolls.index') }}"
    class="px-3 {{ request()->routeIs('hr.payrolls.index') ? 'underline-active' : '' }}">
    💰 الرواتب
</a>
<a href="{{ route('hr.incentives.index') }}"
    class="px-3 {{ request()->routeIs('hr.incentives.index') ? 'underline-active' : '' }}">
    🎁 الحوافز والاستقطاعات
</a>
<a href="{{ route('hr.advances.index') }}"
    class="px-3 {{ request()->routeIs('hr.advances.index') ? 'underline-active' : '' }}">
    💳 السلف
</a>
<a href="{{ route('hr.soa.index') }}" class="px-3 {{ request()->routeIs('hr.soa.index') ? 'underline-active' : '' }}">
    📑 كشف الحساب
</a>
<a href="{{ route('hr.terminations.index') }}"
    class="px-3 {{ request()->routeIs('hr.terminations.index') ? 'underline-active' : '' }}">
    🛑 إنهاء الخدمة
</a>

<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link"><x-app-logo /></a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('dashboard') }}" wire:navigate>{{ __('Dashboard') }}</a>
        </li>

        <!-- Students -->
        <li class="menu-item {{ request()->is('students/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate">{{ __('Students') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('students.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('students.index') }}">{{ __('All Student') }}</a>
                </li>
            </ul>
        </li>

        <!-- Course -->
        <li class="menu-item {{ request()->is('course/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book"></i>
                <div class="text-truncate">{{ __('Course') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('course.index') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('course.index') }}">{{ __('All Course') }}</a>
                </li>
            </ul>
        </li>

        <!-- Schedules -->
        <li class="menu-item {{ request()->is('exams/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate">{{ __('Exams') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('exams.schedule') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('exams.schedule') }}">{{ __('Schedule') }}</a>
                </li>
                <li class="menu-item {{ request()->routeIs('exams.mark') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('exams.mark') }}">{{ __('Add Marks') }}</a>
                </li>
            </ul>
        </li>

        <!-- Reports -->
        <li class="menu-item {{ request()->is('reports/*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate">{{ __('Reports') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('reports.student') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('reports.student') }}">{{ __('Student Average') }}</a>
                </li>
                <li class="menu-item {{ request()->routeIs('reports.subject') ? 'active' : '' }}">
                    <a class="menu-link" href="{{ route('reports.subject') }}">{{ __('Subject Average') }}</a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
<!-- / Menu -->

<script>
    // Toggle the 'open' class when the menu-toggle is clicked
    function initMenuToggle() {
        document.querySelectorAll('.menu-toggle').forEach(function(menuToggle) {
            menuToggle.addEventListener('click', function() {
                const menuItem = menuToggle.closest('.menu-item');
                menuItem.classList.toggle('open');
            });
        });
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', initMenuToggle);

    // If using Livewire + wire:navigate, re-apply toggle after navigation
    document.addEventListener('livewire:navigated', initMenuToggle);
</script>

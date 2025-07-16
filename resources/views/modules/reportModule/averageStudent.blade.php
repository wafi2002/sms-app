@section('title', __('Student Average'))

<x-layouts.app>
    
    <x-responsive-table :columns="$columns" :rows="$rows" :title="$title" :showAddButton="$showAddButton" :hasAction="$hasAction" :showExportButton="$showExportButton" :exportActions="$exportActions"/>

    @push('scripts')
        <script></script>
    @endpush
</x-layouts.app>

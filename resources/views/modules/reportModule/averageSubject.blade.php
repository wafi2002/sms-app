@section('title', __('Subject Average'))

<x-layouts.app>
    <x-responsive-table :columns="$columns" :rows="$rows" :title="$title" :showAddButton="$showAddButton" :hasAction="$hasAction"
        :showExportButton="$showExportButton" :exportActions="$exportActions" />
</x-layouts.app>

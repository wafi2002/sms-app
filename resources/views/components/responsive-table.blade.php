<!-- Define Props -->
@props([
    'columns' => [],
    'columnAlignments' => [],
    'rows' => [],
    'hasAction' => false,
    'actions' => [],
    'exportActions' => [],
    'title' => 'Table',
    'buttonLabel' => 'Add',
    'addButtonIcon' => 'bx bx-book',
    'showAddButton' => false,
    'showExportButton' => false,
    'showModal' => false,
    'modalTarget' => '',
    'paginator' => null,
])

<!-- Responsive Table -->
<div class="card">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-header">{{ $title ?? 'Table' }}</h5>
        @if ($showAddButton)
            <button type="button" class="btn btn-primary mx-2"
                @if ($showModal) data-bs-toggle="modal" data-bs-target="{{ $modalTarget }}" @endif>
                <span class="tf-icons {{ $addButtonIcon ?? 'bx bx-book' }}"></span>&nbsp; {{ $buttonLabel ?? 'Add' }}
            </button>
        @endif
        @if ($showExportButton)
            <div class="demo-inline-spacing px-3">
                <div class="btn-group dropstart">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Export
                    </button>
                    <ul class="dropdown-menu" style="min-width: 200px;">
                        @foreach ($exportActions as $action)
                            <li>
                                <a class="dropdown-item" href="{{ route($action['route']) }}">
                                    <i class="{{ $action['icon'] ?? 'bx bx-cog' }} me-2"></i>
                                    {{ $action['exportLabel'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr class="text-nowrap">
                    <th>#</th>
                    @foreach ($columns as $key => $column)
                        <th class="{{ ($columnAlignments[$key] ?? '') === 'center' ? 'text-center' : '' }}">
                            {{ $column }}</th>
                    @endforeach
                    @if (!empty($hasAction) && $hasAction)
                        <th class="text-center">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    {{-- <pre>{{ print_r($row, true) }}</pre> --}}
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        @foreach ($columns as $key => $column)
                            <td class="{{ $columnAlignments[$key] === 'center' ? 'text-center' : '' }}">
                                @if ($key === 'mark')
                                    <div class="input-group input-group-sm">
                                        <input type="number" step="0.01" min="0" max="100"
                                            value="{{ $row[$key] ?? 0 }}" class="form-control update-mark"
                                            data-id="{{ $row['id'] }}"
                                            data-url="{{ route('exams.update', $row['id']) }}">
                                        <button type="button" class="btn btn-outline-success submit-mark-btn"
                                            data-id="{{ $row['id'] }}"
                                            data-url="{{ route('exams.update', $row['id']) }}">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </div>
                                @else
                                    {{ $row[$key] ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                        @if (!empty($hasAction) && $hasAction)
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach ($actions as $action)
                                            <a class="dropdown-item {{ $action['jsTrigger'] }}"
                                                href="{{ route($action['route'], [$action['paramKey'] ?? 'id' => $row['id']]) }}"
                                                @if (!empty($action['modal']) && $action['modal'] === true) data-bs-toggle="modal" data-bs-target="{{ $action['target'] ?? '' }}" @endif
                                                data-id="{{ $row['id'] }}"
                                                data-url="{{ route($action['route'], [$action['paramKey'] ?? 'id' => $row['id']]) }}">
                                                <i class="{{ $action['icon'] ?? 'bx bx-cog' }} me-1"></i>
                                                {{ $action['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if (isset($paginator))
            <div class="mt-3 mx-3">
                {{ $paginator->links() }}
            </div>
        @endif
    </div>
</div>
<!--/ Responsive Table -->

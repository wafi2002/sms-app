<!-- Define Props -->
@props([
    'columns' => [],
    'rows' => [],
    'hasAction' => false,
    'actions' => [],
    'title' => 'Table',
    'buttonLabel' => 'Add',
    'addButtonIcon' => 'bx bx-book',
    'showAddButton' => false,
    'showModal' => false,
    'modalTarget' => '',
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
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr class="text-nowrap">
                    <th>#</th>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                    @if (!empty($hasAction) && $hasAction)
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr>
                        <th scope="row">{{ $index + 1 }}</th>
                        @foreach ($columns as $key => $column)
                            <td>{{ $row[$key] ?? '-' }}</td>
                        @endforeach
                        @if (!empty($hasAction) && $hasAction)
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach ($actions as $action)
                                            <a class="dropdown-item {{ $action['jsTrigger'] ?? '' }}"
                                                href="{{ route($action['route'], [$action['paramKey'] ?? 'id' => $row['id']]) }}"
                                                @if (!empty($action['modal']) && $action['modal'] === true) data-bs-toggle="modal"
                                                data-bs-target="{{ $action['target'] ?? '' }}" @endif
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
    </div>
</div>
<!--/ Responsive Table -->

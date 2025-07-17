@if ($paginator->hasPages())
    @php
        $elements = $paginator->linkCollection()->elements();
    @endphp
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item prev disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-left"></i></span>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link" href="#" wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }})">
                        <i class="tf-icon bx bx-chevrons-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Page Number Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="#"
                                    wire:click.prevent="gotoPage({{ $page }})">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link" href="#"
                        wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }})">
                        <i class="tf-icon bx bx-chevrons-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item next disabled">
                    <span class="page-link"><i class="tf-icon bx bx-chevrons-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif

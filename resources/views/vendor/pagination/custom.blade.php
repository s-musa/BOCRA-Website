@if ($paginator->hasPages())
   <nav role="navigation" aria-label="Pagination Navigation" class="d-flex justify-content-center mt-4">
      <ul class="pagination m-0 gap-2">

         {{-- Previous Page Link --}}
         @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                    <span class="page-link rounded-circle bg-light border-0 text-muted" style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                        <i class="uil uil-angle-left"></i>
                    </span>
            </li>
         @else
            <li class="page-item">
               <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                  class="page-link rounded-circle bg-light border-0 text-muted"
                  style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                  <i class="uil uil-angle-left"></i>
               </a>
            </li>
         @endif

         {{-- Pagination Elements --}}
         @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
               <li class="page-item disabled"><span class="page-link bg-light border-0">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
               @foreach ($element as $page => $url)
                  @if ($page == $paginator->currentPage())
                     <li class="page-item active">
                                <span class="page-link rounded-circle border-0 text-white fw-bold"
                                      style="background-color:#2563eb;width:40px;height:40px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 10px rgba(37,99,235,0.3);">
                                    {{ $page }}
                                </span>
                     </li>
                  @else
                     <li class="page-item">
                        <a href="{{ $url }}" class="page-link rounded-circle bg-light border-0 text-muted"
                           style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                           {{ $page }}
                        </a>
                     </li>
                  @endif
               @endforeach
            @endif
         @endforeach

         {{-- Next Page Link --}}
         @if ($paginator->hasMorePages())
            <li class="page-item">
               <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                  class="page-link rounded-circle bg-light border-0 text-muted"
                  style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                  <i class="uil uil-angle-right"></i>
               </a>
            </li>
         @else
            <li class="page-item disabled">
                    <span class="page-link rounded-circle bg-light border-0 text-muted"
                          style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;">
                        <i class="uil uil-angle-right"></i>
                    </span>
            </li>
         @endif

      </ul>
   </nav>
@endif

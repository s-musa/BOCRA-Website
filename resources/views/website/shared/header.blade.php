@if($customisation->top_header)
   @include('website.designs.shared.addon.header-tagline')
@endif
@if($customisation->header_style && $customisation->header_style !== 'default')
   @include('website.designs.shared.' . $customisation->header_style, ['customisation' => $customisation])
@else
   <nav id="navbar" class="navbar navbar-expand-lg fixed-top sticky-top {{$customisation->header_bg}}">
      <div @class([
             $customisation->section_width_style ?? 'container',
             'w__' . ($customisation->section_width ?? '100') ])
      >
         <a class="navbar-brand" href="{{ route('home') }}">
            @if($logo)
               <img src="{{ $logo }}" alt="Logo" class="" style="max-height:70px;">
            @else
               <div class="h4 mb-2 mb-sm-0 text-primary">BOCRA</div>
            @endif
         </a>
         <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                 data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                 aria-label="Toggle navigation">
            <i class="mdi mdi-menu text-muted"></i>
         </button>
         <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0" id="navbar-navlist">
               @foreach($menus as $menu)
                  @if($menu->type === 'page' && $menu->has_children && $menu->child_type === 'pages')
                     <li class="nav-item dropdown">
                        <a href="{{ url($menu->page->slug) }}" class="nav-link @if(request()->is($menu->page->slug . '*')) active @endif"
                           id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           {{ $menu->title }} <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                           @foreach($menu->children as $child)
                              <li>
                                 <a href="{{ url($child->page?->slug) }}" class="nav-link @if(request()->is($child->page?->slug)) active @endif">
                                    {{ $child->page?->title }}
                                    @if(!empty($child->page->children) && $child->page->children->count() > 0)
                                    <i class="mdi mdi-chevron-down"></i>
                                    @endif
                                 </a>
                                 @if(!empty($child->page->children) && $child->page->children->count() > 0)
                                    <ul class="dropdown-menu">
                                       @foreach($child->page->children as $item)
                                          <li>
                                             <a class="nav-link" href="{{ url($item->slug) }}">
                                                {{ $item->title }}
                                             </a>
                                          </li>
                                       @endforeach
                                    </ul>
                                 @endif
                              </li>
                           @endforeach
                        </ul>
                     </li>
                  @elseif($menu->type === 'page' && $menu->has_children && $menu->child_type === 'component')
                     <li class="nav-item dropdown">
                        @php
                           $component = $menu->component;
                           $items = $component::where('active', true)->orderBy('id')->get();
                           $baseRoute = $component === 'App\\Models\\Service' ? 'services' : 'projects';
                        @endphp
                        <a href="{{ url($menu->page?->slug) }}" class="nav-link @if(request()->is('/' . $baseRoute . '*' )) active @endif"
                           id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           {{ $menu->title }} <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                           @foreach($items as $item)
                              <li>
                                 <a href="{{ route("$baseRoute.show", $item->slug) }}" class="nav-link @if(request()->is("$baseRoute/{$item->slug}")) active @endif">
                                    {{ $item->title }}
                                 </a>
                              </li>
                           @endforeach
                        </ul>
                     </li>
                  @elseif($menu->type === 'custom' && $menu->has_children && $menu->child_type === 'pages')
                     <li class="nav-item dropdown">
                        <a href="{{ $menu->url }}" class="nav-link @if(request()->is($menu->url . '*')) active @endif"
                           id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           {{ $menu->title }} <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                           @foreach($menu->children as $child)
                              <li>
                                 <a href="{{ url($child->page?->slug) }}" class="nav-link @if(request()->is($child->page?->slug)) active @endif">
                                    {{ $child->page?->title }}
                                    @if(!empty($child->page->children) && $child->page->children->count() > 0)
                                    <i class="mdi mdi-chevron-down"></i>
                                    @endif
                                 </a>
                                 @if(!empty($child->page->children) && $child->page->children->count() > 0)
                                 <ul class="dropdown-menu">
                                    @foreach($child->page->children as $item)
                                       <li>
                                          <a class="nav-link" href="{{ url($item->slug) }}">
                                             {{ $item->title }}
                                          </a>
                                       </li>
                                    @endforeach
                                 </ul>
                                 @endif
                              </li>
                           @endforeach
                        </ul>
                     </li>
                  @elseif($menu->type === 'custom' && $menu->has_children && $menu->child_type === 'component')
                     <li class="nav-item dropdown">
                        @php
                           $component = $menu->component;
                           $items = $component::where('active', true)->orderBy('id')->get();
                           $baseRoute = $component === 'App\\Models\\Service' ? 'services' : 'projects';
                        @endphp
                        <a href="{{ $menu->url }}" class="nav-link @if(request()->is($menu->url . '*' )) active @endif"
                           id="navbarDropdown">
                           {{ $menu->title }} <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                           @foreach($items as $item)
                              <li>
                                 <a href="{{ route("$baseRoute.show", $item->slug) }}" class="nav-link @if(request()->is("$baseRoute/{$item->slug}")) active @endif">
                                    {{ $item->title }}
                                 </a>
                              </li>
                           @endforeach
                        </ul>
                     </li>
                  @else
                     <li class="nav-item">
                        <a href="@if($menu->page) {{ url($menu->page->slug) }} @else {{ url($menu->url ?? '') }} @endif" class="nav-link @if(request()->is($menu->page ? $menu->page->slug : $menu->url)) active @endif">
                           {{ $menu->title }}
                        </a>
                     </li>
                  @endif
               @endforeach
            </ul>
         </div>
      </div>
   </nav>
@endif

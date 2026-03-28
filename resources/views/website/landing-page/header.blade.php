@if($customisation->top_header)
   @include('website.designs.shared.addon.header-tagline')
@endif
@if($customisation->header_style && $customisation->header_style !== 'default')
   @include('website.designs.shared.' . $customisation->header_style, ['customisation' => $customisation])
@else
<header id="topnav" class="defaultscroll main-site-header fixed @if($customisation->top_header) tagline-height @endif">
   <div @class([
       $customisation->section_width_style ?? 'container',
       'w__' . ($customisation->section_width ?? '100') ])
   >
      <!-- Logo container-->
      <a class="logo" href="{{ route('home') }}">
         <span class="logo-light-mode">
            <img src="{{ $logo }}" class="l-dark" alt="BOCRA" style="max-height:40px;">
            <img src="{{ $logo }}" class="l-light" alt="BOCRA" style="max-height:40px;">
         </span>
      </a>
      <!-- End Logo container-->
      <div class="menu-extras">
         <div class="menu-item">
            <!-- Mobile menu toggle-->
            <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
               <div class="lines">
                  <span></span>
                  <span></span>
                  <span></span>
               </div>
            </a>
            <!-- End mobile menu toggle-->
         </div>
      </div>

      <div id="navigation">
         <!-- Navigation Menu-->
         <ul class="navigation-menu nav-center nav-light">
            @foreach($menus as $menu)
               @if($menu->type === 'page' && $menu->has_children && $menu->child_type === 'pages')
                  <li class="has-submenu parent-parent-menu-item {{ $menu->hasActiveChild() ? 'active' : '' }}">
                     <a href="{{ url($menu->page?->slug ?? '#') }}">
                        {{ $menu->title }}
                     </a>
                     <span class="menu-arrow"></span>
                     <ul class="submenu">
                        @foreach($menu->children as $child)
                           <li class="@if(request()->is('/' . $child->page?->slug . '*')) active @endif">
                              <a href="{{ url($child->page?->slug ?? '#') }}" class="sub-menu-item @if(request()->is('/' . $child->page?->slug . '*')) active @endif">
                                 {{ $child->title }}
                              </a>
                           </li>
                        @endforeach
                     </ul>
                  </li>
               @elseif($menu->type === 'page' && $menu->has_children && $menu->child_type === 'component')
                  @php
                     $component = $menu->component;
                     $items = $component::where('active', true)->$menuorderBy('id')->get();
                     $baseRoute = $component === 'App\\Models\\Service' ? 'services' : 'projects';
                  @endphp
                  <li class="has-submenu parent-parent-menu-item @if(request()->is('/' . $baseRoute . '*')) active @endif">
                     <a href="{{ url($menu->page?->slug) ?? '#' }}">{{ $menu->title }}</a><span class="menu-arrow"></span>
                     <ul class="submenu">
                        @foreach($items as $item)
                           <li>
                              <a href="{{ route("$baseRoute.show", $item->slug) }}" class="sub-menu-item @if(request()->is("$baseRoute/{$item->slug}")) active @endif">
                                 {{ $item->title }}
                              </a>
                           </li>
                        @endforeach
                     </ul>
                  </li>
               @elseif($menu->type === 'custom' && $menu->has_children && $menu->child_type === 'pages')
                  <li class="has-submenu parent-parent-menu-item {{ $menu->hasActiveChild() ? 'active' : '' }}">
                     <a href="{{ $menu->url ?? 'javascript:void(0)' }}">{{ $menu->title }}</a><span class="menu-arrow"></span>
                     <ul class="submenu">
                        @foreach($menu->children as $child)
                           <li class="@if(request()->is($child->page?->slug . '*')) active @endif">
                              <a href="{{ url($child->page->slug) }}" class="sub-menu-item @if(request()->is($child->page?->slug)) active @endif">
                                 {{ $child->title }}
                              </a>
                           </li>
                        @endforeach
                     </ul>
                  </li>
               @elseif($menu->type === 'custom' && $menu->has_children && $menu->child_type === 'component')
                  @php
                     $component = $menu->component;
                     $items = $component::where('active', true)->orderBy('id')->get();
                     $baseRoute = $component === 'App\\Models\\Service' ? 'services' : 'projects';
                  @endphp
                  <li class="has-submenu parent-parent-menu-item @if(request()->is($menu->url . '*')) active @endif">
                     <a href="{{ $menu->url ?? 'javascript:void(0)' }}">{{ $menu->title }}</a><span class="menu-arrow"></span>
                     <ul class="submenu">
                        @foreach($items as $item)
                           <li>
                              <a href="{{ route("$baseRoute.show", $item->slug) }}" class="sub-menu-item @if(request()->is("$baseRoute/{$item->slug}")) active @endif">
                                 {{ $item->title }}
                              </a>
                           </li>
                        @endforeach
                     </ul>
                  </li>
               @else
                  <li class="@if($menu->page && request()->is($menu->page?->slug.'*') || $menu->url && request()->is($menu->url.'*')) active @endif">
                     <a href="{{ url($menu->page?->slug ?? '#') }}" class="sub-menu-item">
                        {{ $menu->title }}
                     </a>
                  </li>
               @endif
            @endforeach
         </ul>
      </div>
   </div>
</header>
@endif

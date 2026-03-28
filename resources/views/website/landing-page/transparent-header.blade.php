@if($customisation->top_header)
   @include('website.designs.shared.addon.header-tagline')
@endif

@php
$pageMedia = $page->media->firstWhere('collection_name', 'page-image')
@endphp
<header id="topnav" class="defaultscroll landing-page tagline-height">
   <div @class([
       $customisation->section_width_style ?? 'container',
       'w__' . ($customisation->section_width ?? '100') ])
   >
      <a class="logo" href="{{ route('home') }}">
         <span class="logo-light-mode">
            <img src="{{ $pageMedia ? $pageMedia->getUrl() : $logo }}" class="l-dark" alt="BOCRA" style="max-height:70px;">
            <img src="{{ $pageMedia ? $pageMedia->getUrl() : $logo }}" class="l-light" alt="BOCRA" style="max-height:70px;">
         </span>
      </a>

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
         <ul class="navigation-menu nav-right nav-light">
            <li class="">
               <a href="{{ route('home') }}" class="sub-menu-item">
                  Home
               </a>
            </li>
            @foreach($page->sections as $menu)
               @if(!empty($menu->spa_section_name))
                  <li data-section="{{ $menu->spa_section_id }}" class="@if(request()->is($menu->spa_section_id)) active @endif">
                     <a href="{{'#'.$menu->spa_section_id}}" class="sub-menu-item">
                        {{ $menu->spa_section_name }}
                     </a>
                  </li>
               @endif
            @endforeach
         </ul>
      </div>
   </div>
</header>

@push('scripts')
   <script>
      document.addEventListener("DOMContentLoaded", function () {

         function setActiveMenu() {
            let hash = window.location.hash.replace('#', '');

            document.querySelectorAll('li[data-section]').forEach(li => {
               li.classList.remove('active');

               if (li.dataset.section === hash) {
                  li.classList.add('active');
               }
            });
         }

         // Run on page load
         setActiveMenu();

         // Run when hash changes
         window.addEventListener('hashchange', setActiveMenu);

      });
   </script>
@endpush

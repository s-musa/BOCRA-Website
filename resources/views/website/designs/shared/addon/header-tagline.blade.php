<div class="tagline @if($customisation->top_header_bg) {{$customisation->top_header_bg}} @else bg-light @endif">
   <div @class([
       $customisation->section_width_style ?? 'container',
       'w__' . ($customisation->section_width ?? '100') ])
   >
   <div class="row">
         <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
               <ul class="list-unstyled mb-0">
                  <li class="list-inline-item mb-0">
                     <a href="mailto:{{$company->email ?? 'javascript:void(0)'}}" class="text-muted">
                        <i class="mdi mdi-email icon-lg me-2"></i>{{ $company->email ?? '' }}
                     </a>
                  </li>
                  <li class="list-inline-item mb-0 ms-3">
                     <a href="tel:{{$company->primary_phone ?? 'javascript:void(0)'}}" class="text-muted">
                        <i class="mdi mdi-phone me-2"></i>{{ $company->primary_phone ?? '' }}
                     </a>
                  </li>
               </ul>

               <ul class="list-unstyled social-icon tagline-social mb-0">
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->ig_profile ?? '#' }}" target="_blank">
                        <i class="ti ti-brand-instagram mb-0"></i>
                     </a>
                  </li>
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->fb_profile ?? '#' }}" target="_blank">
                        <i class="ti ti-brand-facebook mb-0"></i>
                     </a>
                  </li>
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->x_profile ?? '#' }}" target="_blank">
                        <i class="ti ti-brand-x mb-0"></i>
                     </a>
                  </li>
                  @if($company->tiktok_profile)
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->tiktok_profile }}" target="_blank">
                        <i class="ti ti-brand-tiktok mb-0"></i>
                     </a>
                  </li>
                  @endif
                  @if($company->pinterest_profile)
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->pinterest_profile }}" target="_blank">
                        <i class="ti ti-brand-pinterest mb-0"></i>
                     </a>
                  </li>
                  @endif
                  @if($company->youtube_profile)
                  <li class="list-inline-item mb-0">
                     <a href="{{ $company->youtube_profile }}" target="_blank">
                        <i class="ti ti-brand-youtube mb-0"></i>
                     </a>
                  </li>
                  @endif
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

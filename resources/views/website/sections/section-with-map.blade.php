<section class="section map" id="{{$section->spa_section_id ?? ''}}" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="300">
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <iframe loading="lazy"
              class="w-100 h-100"
              src="{{ $section->map_link }}" title="{{ $company->physical_address }}" aria-label="{{ $company->physical_address }}">
      </iframe>
   </div>
</section>

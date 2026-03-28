@php
   $backgroundImage = $section->media?->firstWhere('collection_name', 'section_bg');
   $sectionImage = $section->media?->firstWhere('collection_name', 'section_image');
@endphp
@if($section->section_style)
   @include('website.designs.' . $section->section_style, ['section' => $section,
            'backgroundImage' => $backgroundImage, 'sectionImage' => $sectionImage, 'customisation' => $customisation])
@else
<section @class([ 'section',
      $section->section_has_bg ? 'section-bg section-bg-fixed' : '',
      $section->section_has_map ? 'pb-0' : '',
   ]) id="{{$section->spa_section_id ?? ''}}">
   @if($section->section_has_bg)
   <div class="{{ $section->section_background_color ?? '' }}">
      @if($section->section_background_type === 'image-bg')
         <img src="{{ $backgroundImage->getUrl() ?? asset('website/images/backgrounds/T5.jpg') }}" alt="">
      @endif
   </div>
   @endif
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ])
   >
      <div class="row justify-content-center">
         @if($section->title || $section->sub_title || $section->details)
         <div class="col-lg-10 col-12 mx-auto">
            <div class="mb-5">
               <div class="section-title title-2 text-center mb-4">
                  @if($section->sub_title)
                     <p class="sub_title" data-aos="fade-right" data-aos-duration="1800" data-aos-delay="100">
                        {{ $section->sub_title }}
                     </p>
                  @endif
                  <h3 class="title" data-aos="fade-right" data-aos-duration="1800" data-aos-delay="200">
                     {{ $section->title }}
                  </h3>
                  <div class="text-muted text-wrap details" data-aos="fade-right" data-aos-duration="1800" data-aos-delay="350">
                     {!! $section->details !!}
                  </div>
               </div>
            </div>
         </div>
         @endif
         <div class="col-lg-6 justify-content-center mx-auto mb-3">
            <div class="custom-form rounded mb-5 bg-white p-5"
                 data-aos="zoom-up" data-aos-duration="1800" data-aos-delay="500">
               <form method="post" id="contactForm">
                  @if($section->form_title || $section->form_sub_title)
                     <div class="section-title mb-5">
                     @if($section->form_sub_title)
                        <p class="text-capitalize fw-semibold mb-0">
                           {{ $section->form_sub_title }}
                        </p>
                     @endif
                     @if($section->form_title)
                        <h4 class="title text-dark">
                           {{ $section->form_title }}
                        </h4>
                     @endif
                     </div>
                  @endif
                  <p id="error-msg"></p>
                  <div id="simple-msg"></div>
                  <div class="row">
                     <div class="col-md-6 col-12">
                        <div class="form-group">
                           <label for="name">Name*</label>
                           <input name="name" id="name" type="text" class="form-control"
                                  placeholder="Your name...">
                           <div class="invalid-feedback text-danger"></div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="form-group">
                           <label for="email">Email Address*</label>
                           <input name="email" id="email" type="email" class="form-control"
                                  placeholder="Your email...">
                           <div class="invalid-feedback text-danger"></div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="subject">Subject*</label>
                           <input name="subject" id="subject" type="text" class="form-control"
                                  placeholder="Your subject...">
                           <div class="invalid-feedback text-danger"></div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="form-group">
                           <label for="comments">Message*</label>
                           <textarea name="comments" id="comments" rows="4" class="form-control"
                                     placeholder="Your message..."></textarea>
                           <div class="invalid-feedback text-danger text-danger"></div>
                        </div>
                     </div>
                     <div class="col-12">
                        <button type="submit" id="submitContactForm" name="send" class="btn btn-primary {{ $customisation->button_style ?? '' }} w-100">
                           <span class="btn-text">Send Message</span>
                           <i class="icon-size-15 ms-2 icon mdi mdi-send"></i>
                           <span class="spinner"></span>
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         @if($section->include_contact_cards)
         <div class="col-lg-6 text-start">
            <div class="contact-detail ms-lg-5">
               <div class="d-flex w-100 mb-5">
                  <div class="features border-0 d-flex h-100" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="400">
                     <div class="fea-icon rounded-circle bg-light shadow icon">
                        <i class="mdi mdi-email-open-outline h1 mb-0 text-primary"></i>
                     </div>
                     <div class="content flex-1 ms-3">
                        <h4 class="title title-shadow-none text-dark">Email</h4>
                        <div class="mt-2 {{ $section->section_has_bg ? 'text-muted' : '' }}">
                           <span class="me-1">{{ $company->email }}</span> @if($company->secondary_email)
                              | <span class="ms-1">{{ $company->secondary_email }} @endif</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex w-100 mb-5" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="600">
                  <div class="features feature-primary border-0 d-flex h-100">
                     <div class="fea-icon rounded-circle bg-light shadow icon">
                        <i class="mdi mdi-phone-outline h1 mb-0 text-primary"></i>
                     </div>
                     <div class="content flex-1 ms-3">
                        <h4 class="title title-shadow-none">Call Us On</h4>
                        <div class="mt-2 {{ $section->section_has_bg ? 'text-muted' : '' }}">
                           <span class="me-1">{{ $company->primary_phone }}</span> @if($company->secondary_phone)
                              | <span class="ms-1">{{ $company->secondary_phone }} @endif</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex w-100 mb-5" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="800">
                  <div class="features feature-primary border-0 d-flex h-100">
                     <div class="fea-icon rounded-circle bg-light shadow icon">
                        <i class="mdi mdi-whatsapp h1 mb-0 text-primary"></i>
                     </div>
                     <div class="content flex-1 ms-3">
                        <h4 class="title text-dark">Whatsapp</h4>
                        <div class="mt-2 {{ $section->section_has_bg ? 'text-muted' : '' }}">
                           <span class="me-1">{{ $company->primary_whatsapp }}</span> @if($company->secondary_whatsapp)
                              | <span class="ms-1">{{ $company->secondary_whatsapp }} @endif</span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="d-flex w-100 mb-5" data-aos="fade-up" data-aos-duration="1800" data-aos-delay="1000">
                  <div class="features feature-primary border-0 d-flex h-100">
                     <div class="fea-icon rounded-circle bg-light shadow icon">
                        <i class="mdi mdi-map-marker-outline h1 mb-0 text-primary"></i>
                     </div>
                     <div class="content flex-1 ms-3">
                        <h4 class="title title-shadow-none text-dark">Location</h4>
                        <div class="mt-2 {{ $section->section_has_bg ? 'text-muted' : '' }}">{{ $company->physical_address }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </div>
   @if($section->section_has_map)
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 p-0">
            <div class="map">
               {!! $section->map_link !!}
            </div>
         </div>
      </div>
   </div>
   @endif
</section>
@endif

@push('scripts')
   <script src="{{ asset('website/js/contact.js') }}"></script>
@endpush

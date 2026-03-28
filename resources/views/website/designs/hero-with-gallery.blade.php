@push('styles')
   <link rel="stylesheet" href="{{ asset('website/css/swiper-bundle.min.css') }}">
   <style>
      .bg-home-2 {
         position: relative;
         overflow: hidden;
      }

      /* Background Slider Styles */
      .hero-bg-slider {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         z-index: 0;
      }

      .hero-bg-slider .swiper {
         width: 100%;
         height: 100%;
      }

      .hero-bg-slider .swiper-slide {
         position: relative;
         overflow: hidden;
      }

      /* Zoom animation for each slide */
      .hero-bg-slide-image {
         width: 100%;
         height: 100%;
         background-size: cover;
         background-position: center;
         background-repeat: no-repeat;
         animation: kenburns 8s ease-out forwards;
      }

      /* Ken Burns zoom effect */
      @keyframes kenburns {
         0% {
            transform: scale(1);
         }
         100% {
            transform: scale(1.15);
         }
      }

      /* Reset animation for active slide */
      .swiper-slide-active .hero-bg-slide-image {
         animation: kenburns 8s ease-out forwards;
      }

      /* Gradient overlay */
      .hero-gradient-overlay {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         background: linear-gradient(to right,rgba(0, 0, 0, 0.8),rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.2));
         z-index: 1;
      }

      /* Content wrapper */
      .hero-content-wrapper {
         position: relative;
         z-index: 2;
      }

      /* Smooth fade for slides */
      .hero-bg-slider .swiper-slide {
         opacity: 0;
         transition: opacity 1s ease-in-out;
      }

      .hero-bg-slider .swiper-slide-active {
         opacity: 1;
      }
   </style>
@endpush

@php
   // Get all gallery images for this section
   $galleryImages = $section->media->where('collection_name', 'section-gallery');
   // Fallback to single image if no gallery
   if($galleryImages->isEmpty() && isset($section->media[0])) {
      $galleryImages = collect([$section->media[0]]);
   }
@endphp

<section class="bg-home-2 align-content-center bg-home-gallery" id="home">
   <!-- Background Image Slider -->
   @if($galleryImages->isNotEmpty())
   <div class="hero-bg-slider">
      <div class="swiper" id="heroBgSlider-{{ $section->id }}">
         <div class="swiper-wrapper">
            @foreach($galleryImages as $image)
               <div class="swiper-slide">
                  <div class="hero-bg-slide-image" style="background-image: url('{{ $image->original_url ?? asset('dummy-image.jpg') }}');"></div>
               </div>
            @endforeach
         </div>
      </div>
      <!-- Gradient Overlay -->
      <div class="hero-gradient-overlay"></div>
   </div>
   @else
      <!-- Fallback static background -->
   <div class="hero-bg-slider">
      <div style="width: 100%; height: 100%; background: url('{{ asset('dummy-image.jpg') }}') no-repeat center / cover;"></div>
      <div class="hero-gradient-overlay"></div>
   </div>
   @endif

   <!-- Content -->
   <div @class([
       $section->width_style ?: ($customisation->section_width_style ?? 'container'),
       'w__' . ($section->width ?? $customisation->section_width ?? '100') ]) id="{{$section->spa_section_id ?? ''}}"
   >
      <div class="row">
         <div class="col-lg-7">
            <div class="text-start text-white">
               @if($section->sub_title)
                  <h6 class="home-subtitle text-wrap" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="600">
                     {{ $section->sub_title }}
                  </h6>
               @endif
               <h3 class="fw-bold text-primary" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="800">
                  {{ $section->title }}
               </h3>
               <div class="home-desc-2" data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1000">
                  {!! $section->details !!}
               </div>
               @if($section->has_cta_buttons && $section->cta_buttons->isNotEmpty())
                  <div class="mt-4 pt-3">
                     @foreach($section->cta_buttons as $key => $button)
                        <a href="{{ url($button->page->slug ?? 'javascript:void(0)') }}"
                           class="btn {{ $button->cta_button_type . ' ' . $customisation->button_style ?? '' }} me-2"
                           data-aos="fade-right" data-aos-duration="1400" data-aos-delay="1200">
                           {{ $button->cta_button_text }}
                        </a>
                     @endforeach
                  </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</section>

@push('scripts')
   <script src="{{ asset('website/js/swiper-bundle.min.js') }}"></script>
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         @if($galleryImages->isNotEmpty())
         const heroBgSlider = new Swiper('#heroBgSlider-{{ $section->id }}', {
            effect: 'fade',
            fadeEffect: {
               crossFade: true
            },
            loop: true,
            speed: 1500,
            autoplay: {
               delay: 5000,
               disableOnInteraction: false,
            },
            allowTouchMove: false,
            on: {
               init: function() {
                  console.log('Background slider initialized with {{ $galleryImages->count() }} images');
               },
               slideChangeTransitionStart: function() {
                  // Optional: Reset zoom animation for the new active slide
                  const activeSlide = this.slides[this.activeIndex];
                  const imageDiv = activeSlide?.querySelector('.hero-bg-slide-image');
                  if(imageDiv) {
                     // Force animation restart
                     imageDiv.style.animation = 'none';
                     setTimeout(() => {
                        imageDiv.style.animation = 'kenburns 8s ease-out forwards';
                     }, 10);
                  }
               }
            }
         });
         @endif
      });
   </script>
@endpush

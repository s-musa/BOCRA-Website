<?php
   $serviceBackgroundImage = $service ? $service->media?->firstWhere('collection_name', 'service-image') : null;
?>

<section class="bg-half-170 d-table w-100" style="background: url({{
                isset($serviceBackgroundImage)
                    ? $serviceBackgroundImage->getUrl()
                    : asset('website/dummy_800x600.jpg')
            }}) no-repeat center / cover;">
   <div class="bg-overlay bg-gradient-overlay-90"></div>
   <div class="container">
      <div class="row g-0 align-items-center mt-5">
         <div class="col-12">
            <div class="title-heading">
               <h1 class="heading fw-semibold page-heading text-white title-dark">{{ $service->title }}</h1>

               <p class="text-muted w-75">{{ $service->short_description }}</p>
            </div>
         </div>
      </div>
   </div>
</section>

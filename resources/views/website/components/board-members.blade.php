<div class="px-1">
   <div class="row gy-4 mb-3">
      @foreach($board_members as $key => $member)
      <div class="col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="{{ ($key+1) * 200 }}">
         <div class="card team team-primary team-two rounded-bottom-0 shadow">
            <div class="team-image">
               <img class="img-fluid" src="{{ $member->media[0]->original_url ?? asset('website/dummy_450x450.jpg') }}" alt="image" >
               <div class="overlay"></div>
            </div>
            <div class="team-content">
               <div class="text-white details">
                  {!! $member->details !!}
               </div>
            </div>
         </div>
         <div class="team-card-text-3 border">
            <h5 class="fw-bold mb-0 text-uppercase">{{ $member->name }}</h5>
            <p class="mb-0 fs-13 text-primary">{{ $member->position }}</p>
         </div>
      </div>
      @endforeach
   </div>
</div>

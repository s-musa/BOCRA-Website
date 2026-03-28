<template>
   <div class="card mb-6">
      <div class="card-widget-separator-wrapper">
         <div class="card-body card-widget-separator">
            <div class="row gy-4 gy-sm-1">
               <div class="col-sm-6 col-lg-4">
                  <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                     <div>
                        <h4 class="mb-0">{{ stats.total_orders }}</h4>
                        <p class="mb-0">My Orders</p>
                     </div>
                     <span class="avatar w-px-40 h-px-40 me-sm-6">
                        <span class="avatar-initial bg-primary-subtle rounded">
                           <i class="icon-base tf-icon bx bx-calendar icon-lg text-heading"></i>
                        </span>
                     </span>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none me-6">
               </div>
               <div class="col-sm-6 col-lg-4">
                  <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                     <div>
                        <h4 class="mb-0">{{ stats.pending_orders }}</h4>
                        <p class="mb-0">Pending Orders</p>
                     </div>
                     <span class="avatar w-px-40 h-px-40 p-2 me-lg-6">
                        <span class="avatar-initial bg-success-subtle rounded">
                           <i class="icon-base bx bx-check-double icon-lg text-heading"></i>
                        </span>
                     </span>
                  </div>
                  <hr class="d-none d-sm-block d-lg-none">
               </div>
               <div class="col-sm-6 col-lg-4">
                  <div class="d-flex justify-content-between align-items-start">
                     <div>
                        <h4 class="mb-0">{{ stats.completed_orders }}</h4>
                        <p class="mb-0">Completed Orders</p>
                     </div>
                     <span class="avatar w-px-40 h-px-40 p-2 me-sm-6">
                        <span class="avatar-initial bg-secondary-subtle rounded">
                           <i class="icon-base bx bx-wallet icon-lg text-heading"></i>
                        </span>
                     </span>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
   props: ['stats'],
}
</script>

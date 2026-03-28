<template>
   <Head title="Dashboard"/>
   
   <DefaultLayout>
      <div class="row">
         <h4>Dashboard</h4>
         
         <div class="">
            <div class="col-12">
               <order-summary
               :stats="stats"></order-summary>
            </div>
            
            <div class="col-12">
               <div class="mb-3">
                  <div class="card">
                     <div class="card-header">
                        <div class="card-title mb-0">
                           <h5 class="mb-0">Recent Orders</h5>
                        </div>
                     </div>
                     <div class="table-responsive">
                        <table class="table">
                           <thead>
                           <tr>
                              <th>CODE</th>
                              <th>DATE</th>
                              <th>PAYMENT METHOD</th>
                              <th>STATUS</th>
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="order in recentOrders">
                              <td>
                                 <Link :href="route('admin.ecm.orders.show', order.hashid)">
                                 {{ order.code }}
                                 </Link>
                              </td>
                              <td>
                                 <div>
                                    {{ $filters.date_DAY_MONTH_YEAR(order.created_at) ?? '-' }},
                                    <span class="small text-muted">{{ $filters.TIME_HOUR_MINUTES(order.created_at) ?? '-' }}</span>
                                 </div>
                              </td>
                              <td>{{ order.payment_method.name }}</td>
                              <td>
                                 <span v-if="order.status === 0" class="badge bg-success">
                                    Pending Confirmation
                                 </span>
                                 <span v-else-if="order.status === 1" class="badge bg-danger">
                                    Complete
                                 </span>
                                 <span v-else-if="order.status === 2" class="badge bg-danger">
                                    Failed
                                 </span>
                                 <span v-else-if="order.status === 3" class="badge bg-danger">
                                    Cancelled
                                 </span>
                                 <span v-else-if="order.status === 4" class="badge bg-danger">
                                    Refunded
                                 </span>
                                 <span v-else-if="order.status === 5" class="badge bg-danger">
                                    Partially Refunded
                                 </span>
                                 <span v-else class="badge bg-secondary">
                                    Unknown
                                 </span>
                              </td>
                           </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/Customer/DefaultLayout.vue";
import {Head, Link} from '@inertiajs/vue3';
import OrderSummary from "./CustomerOrdersSummary.vue";

export default {
   components: {DefaultLayout, Head, Link, OrderSummary},
   props: ['stats', 'recentOrders'],
}
</script>

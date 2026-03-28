<template>
   <Head title="Ecommerce Orders"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">Ecommerce Orders</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('customer.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Orders
               </li>
            </ol>
         </nav>
         
         <div class="col-12">
            <div class="mb-3">
               <div class="card">
                  <div class="card-header flex-column flex-md-row">
                     <div class="row row-gap-1">
                        <div class="col-md-3 col-9">
                           <input type="search" id="search" class="form-control bg-muted-lt rounded-2"
                                  placeholder="Search Orders"
                                  @input="applyFilter" v-model="appendParams.filter.code">
                        </div>
                     </div>
                  </div>
                  <VueTable
                     :fields="fields"
                     api-url="customer/datatable/orders"
                     :append-params="appendParams"
                     ref="ecmCustomerOrdersTable"
                  >
                     <template #code="props">
                        <Link :href="route('customer.orders.show', props.rowData.hashid)">
                           #{{ props.rowData.code }}
                        </Link>
                     </template>
                     
                     <template v-slot:date="props">
                        <div>
                           {{ $filters.date_DAY_MONTH_YEAR(props.rowData.created_at) ?? '-' }},
                           <span class="small text-muted">{{ $filters.TIME_HOUR_MINUTES(props.rowData.created_at) ?? '-' }}</span>
                        </div>
                     </template>
                     
                     <template v-slot:customer="props">
                        <div class="d-flex">
                           {{ props.rowData.customer ? props.rowData.customer.name : props.rowData.customer_name }}
                        </div>
                        <span class="text-muted small">{{ props.rowData.customer ? props.rowData.customer.email : props.rowData.customer_email }}</span>
                     </template>
                     
                     <template v-slot:status="props">
                        <span v-if="props.rowData.status === 0" class="badge bg-success">
                           Pending Confirmation
                        </span>
                        <span v-else-if="props.rowData.status === 1" class="badge bg-danger">
                           Complete
                        </span>
                        <span v-else-if="props.rowData.status === 2" class="badge bg-danger">
                           Failed
                        </span>
                        <span v-else-if="props.rowData.status === 3" class="badge bg-danger">
                           Cancelled
                        </span>
                        <span v-else-if="props.rowData.status === 4" class="badge bg-danger">
                           Refunded
                        </span>
                        <span v-else-if="props.rowData.status === 5" class="badge bg-danger">
                           Partially Refunded
                        </span>
                        <span v-else class="badge bg-secondary">
                           Unknown
                        </span>
                     </template>
                     
                     <template #actions="props">
                        <div class="dropdown">
                           <button class="btn align-text-top py-1" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical"></i>
                           </button>
                           <div class="dropdown-menu dropdown-menu-end">
                              <Link class="dropdown-item" :href="route('customer.orders.show', props.rowData.hashid)">
                                 <i class="bx bx-detail me-2 align-content-center"></i>Details
                              </Link>
                           </div>
                        </div>
                     </template>
                  </VueTable>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/Customer/DefaultLayout.vue"
import {Head, Link} from "@inertiajs/vue3"
import _debounce from "lodash/debounce.js";

export default {
   components: {DefaultLayout, Head, Link},
   data() {
      return {
         fields: [
            {
               name: '__slot:code',
               title: 'CODE',
            },
            {
               name: '__slot:date',
               title: 'DATE',
            },
            {
               name: '__slot:customer',
               title: 'CUSTOMER',
            },
            {
               name: 'payment_method.name',
               title: 'METHOD',
            },
            {
               name: '__slot:status',
               title: 'STATUS',
            },
            {
               name: '__slot:actions',
               title: 'ACTIONS',
               titleClass: 'text-end w-5',
               dataClass: 'text-end w-5',
            },
         ],
         appendParams: {
            filter: {
               code: '',
               customer_id: this.$page.props.auth.customer.id,
            }
         },
      }
   },
   methods: {
      applyFilter: _debounce(function () {
         this.$refs.ecmCustomerOrdersTable.reloadTable();
      }, 800),
   }
}
</script>

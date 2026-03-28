<template>
   <Head title="Ecommerce Orders" />
   
   <DefaultLayout>
      <div class="row">
         <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
               <div class="mb-1">
                  <span class="h5">Order <span class="text-success">#{{order.code}}</span></span>
                  <span v-if="order.status === 0" class="badge bg-label-secondary me-2 ms-2">Pending</span>
                  <span v-if="order.status === 1" class="badge bg-label-secondary me-2 ms-2">Paid</span>
                  <span v-if="order.status === 2" class="badge bg-label-secondary me-2 ms-2">Failed</span>
                  <span v-if="order.status === 3" class="badge bg-label-secondary me-2 ms-2">Refunded</span>
                  <span v-if="order.status === 4" class="badge bg-label-secondary me-2 ms-2">Partially Refunded</span>
                  <span v-if="order.payment_status === 0" class="badge bg-primary me-2 ms-2">Pending Payment Confirmation</span>
                  <span v-if="order.payment_status === 1" class="badge bg-primary me-2 ms-2">Pending Complete</span>
                  <span v-if="order.payment_status === 2" class="badge bg-primary me-2 ms-2">Pending Failed</span>
                  <span v-if="order.payment_status === 3" class="badge bg-primary me-2 ms-2">Pending Cancenlled</span>
               </div>
               <p class="mb-0 small text-primary">{{ $filters.date_DAY_MONTH_YEAR(order.created_at) ?? '-' }}</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
               <Link :href="route('customer.orders.index')" class="btn btn-light">
                  Back
               </Link>
            </div>
         </div>
         
         <div class="col-lg-8 col-sm-12">
            <div class="mb-3">
               <div class="card">
                  <div class="row card-header mx-0 px-3">
                     <div class="d-md-flex justify-content-start align-items-center me-auto col-md-auto">
                        <h5 class="card-tile mb-0">Order Details</h5>
                     </div>
                     <div class="d-md-flex justify-content-between align-items-center col-md-auto ms-auto px-3">
                        <h6 class="m-0">
                           <a href="#">Payment Details</a>
                        </h6>
                     </div>
                  </div>
                  <div class="justify-content-between">
                     <div class="justify-content-center align-items-center table-responsive">
                        <table class="table">
                           <thead>
                           <tr>
                              <th>PRODUCT</th>
                              <th class="text-end">QTY</th>
                              <th class="text-end">PRICE</th>
                              <th class="text-end">TOTAL</th>
                           </tr>
                           </thead>
                           <tbody>
                           <tr v-for="(item, index) in orderItems">
                              <td>
                                 <div class="d-flex justify-content-start align-items-center product-name">
                                    <div class="avatar-wrapper">
                                       <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">
                                          <img :src="item.product.media[0] ? item.product.media[0].original_url : productDummyImage"
                                               :alt="item.product.slug" class="rounded" >
                                       </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                       <h6 class="text-nowrap mb-0">
                                          {{ item.product.name }}
                                          <span v-if="item.variant" class="text-primary small">({{ item.variant.name }}0</span>
                                       </h6>
                                       <span class="text-truncate small details">{{ item.product.sku }}</span>
                                    </div>
                                 </div>
                              </td>
                              <td class="text-end">{{ item.quantity }}</td>
                              <td class="text-end">{{ $filters.toMoneyFormat(item.base_total * 0.01) }}</td>
                              <td class="text-end">{{ $filters.toMoneyFormat(item.line_total * 0.01) }}</td>
                           </tr>
                           </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-end align-items-center m-6 mb-2">
                           <div class="order-calculations">
                              <div class="d-flex justify-content-start mb-2">
                                 <span class="w-px-100 text-heading">Subtotal:</span>
                                 <h6 class="mb-0 ms-auto">{{ $filters.toMoneyFormat(order.sub_total * 0.01) }}</h6>
                              </div>
                              <div v-if="order.discount_amount > 0" class="d-flex justify-content-start mb-2">
                                 <span class="w-px-100 text-heading">Discount:</span>
                                 <h6 class="mb-0 ms-auto">{{ $filters.toMoneyFormat(order.discount_amount * 0.01) }}</h6>
                              </div>
                              <div v-if="order.tax_amount > 0" class="d-flex justify-content-start mb-2">
                                 <span class="w-px-100 text-heading">Tax:</span>
                                 <h6 class="mb-0 ms-auto">{{ $filters.toMoneyFormat(order.tax_amount * 0.01) }}</h6>
                              </div>
                              <div class="d-flex justify-content-start mb-2">
                                 <span class="w-px-100 text-heading">Total:</span>
                                 <h6 class="mb-0 ms-auto">{{ $filters.toMoneyFormat(order.grand_total * 0.01) }}</h6>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-lg-4 col-sm-12">
            <div class="mb-3">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-tile mb-0">Customer Details</h5>
                  </div>
                  <div class="card-body">
                     <div class="d-flex justify-content-start align-items-center mb-6">
                        <div class="avatar me-3 rounded-circle bg-label-secondary d-flex align-content-center justify-content-center">
                        </div>
                        <div class="d-flex flex-column">
                           <a href="javascript:void(0)" class="text-body text-nowrap">
                              <h6 class="mb-0">{{ order.customer ? order.customer.name : order.customer_name }}</h6>
                           </a>
                           <!--                           <span>Customer ID: #58909</span>-->
                        </div>
                     </div>
                     <div class="d-flex justify-content-between">
                        <h6 class="mb-1">Contact info</h6>
                     </div>
                     <p class=" mb-1">Email: {{ order.customer ? order.customer.email : order.customer_email }}</p>
                     <p class=" mb-1">Phone: {{ order.customer ? order.customer.phone : order.customer_phone }}</p>
                  </div>
               </div>
            </div>
            
            <div v-if="shipping.length > 0" class="mb-3">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-tile mb-0">Shipping Details</h5>
                  </div>
                  <div class="card-body">
                     <p class=" mb-1">{{ shipping[0].first_name + ' ' + shipping[0].last_name }}</p>
                     <p class="mb-1">{{ shipping[0].email ?? '' }}</p>
                     <p class="mb-1">{{ shipping[0].phone ?? '' }}</p>
                     <p class="mb-1">{{ shipping[0].state ?? '' }}, {{ shipping[0].city }}</p>
                     <p class="mb-1">{{ shipping[0].postal_code ?? '' }}</p>
                  </div>
               </div>
            </div>
            
            <div v-if="billing.length > 0" class="mb-3">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-tile mb-0">Billing Details</h5>
                  </div>
                  <div class="card-body">
                     <p class=" mb-1">{{ billing[0].first_name + ' ' + billing[0].last_name }}</p>
                     <p class="mb-1">{{ billing[0].email ?? '' }}</p>
                     <p class="mb-1">{{ billing[0].phone ?? '' }}</p>
                     <p class="mb-1">{{ billing[0].state ?? '' }}, {{ billing[0].city }}</p>
                     <p class="mb-1">{{ billing[0].postal_code ?? '' }}</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue"
import {Head, Link} from "@inertiajs/vue3"
import axios from "axios";
import dummyImage from "@/dummy_450x450.jpg";

export default {
   components: {DefaultLayout, Head, Link},
   props: {
      order: {
         type: Object,
         required: true,
      }
   },
   data() {
      return {
         orderItems: [],
         shipping: [],
         billing: [],
         productDummyImage: dummyImage,
      }
   },
   mounted() {
      this.fetchOrderItems();
      this.extractShippingDetailsFromAddresses();
   },
   methods: {
      fetchOrderItems() {
         axios.get(route('customer.datatable.order-items'), {
            params: {
               filter: {
                  order_id: this.order.id,
               }
            }
         })
            .then(({data}) => {
               this.orderItems = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the order items!", "Error");
         })
      },
      extractShippingDetailsFromAddresses() {
         this.shipping = this.order.addresses.filter(address => address.is_shipping === true)
         console.log(this.shipping)
         this.billing = this.order.addresses.filter(address => address.is_billing === true)
      }
   }
}
</script>

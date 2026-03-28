<template>
   <li :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/ecommerce') }">
      <a href="#" class="menu-link menu-toggle">
         <i class='bx bx-store-alt menu-icon tf-icons'></i>
         <div class="text-truncate">Ecommerce</div>
      </a>
      <ul class="menu-sub">
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/dashboard') }">
            <Link :href="route('admin.ecm.dashboard')" class="menu-link">
               <div class="text-truncate">Dashboard</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/ecommerce/product') }">
            <a href="#" class="menu-link menu-toggle">
               <div class="text-truncate">Products List</div>
            </a>
            <ul class="menu-sub">
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/products') }">
                  <Link :href="route('admin.ecm.products.index')" class="menu-link">
                     <div class="text-truncate">Products</div>
                  </Link>
               </li>
               <li v-if="productReviewsEnabled" :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/product-reviews') }">
                  <Link :href="route('admin.ecm.reviews.index')" class="menu-link">
                     <div class="text-truncate">Manage Reviews</div>
                  </Link>
               </li>
            </ul>
         </li>
         <li v-if="inventoryEnabled" :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/ecommerce/inventory') }">
            <a href="#" class="menu-link menu-toggle">
               <div class="text-truncate">Inventory</div>
            </a>
            <ul class="menu-sub">
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/inventory/warehouses') }">
                  <Link :href="route('admin.ecm.inventory.warehouses.index')" class="menu-link">
                     <div class="text-truncate">Warehouses</div>
                  </Link>
               </li>
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/inventory/adjustments') }">
                  <Link href="#" class="menu-link">
                     <div class="text-truncate">Receipts</div>
                  </Link>
               </li>
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/inventory/adjustments') }">
                  <Link href="#" class="menu-link">
                     <div class="text-truncate">Deliveries</div>
                  </Link>
               </li>
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/inventory/adjustments') }">
                  <Link href="#" class="menu-link">
                     <div class="text-truncate">Adjustments</div>
                  </Link>
               </li>
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/inventory/movement-logs') }">
                  <Link href="#" class="menu-link">
                     <div class="text-truncate">Movement Logs</div>
                  </Link>
               </li>
            </ul>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/orders') }">
            <Link :href="route('admin.ecm.orders.index')" class="menu-link">
               <div class="text-truncate">Orders</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/payments') }">
            <Link :href="route('admin.ecm.payments.index')" class="menu-link">
               <div class="text-truncate">Payments</div>
            </Link>
         </li>
         <li v-if="shipmentEnabled" :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/shipments') }">
            <Link href="#" class="menu-link">
               <div class="text-truncate">Shipments</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/ecommerce/settings') }">
            <Link :href="route('admin.ecm.settings.index')" class="menu-link">
               <div class="text-truncate">Settings</div>
            </Link>
         </li>
      </ul>
   </li>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
   components: {Link},
   computed: {
      productReviewsEnabled() {
         return this.$page.props.allSettings.find(s => s.key === 'enable_product_reviews' && s.value === 'YES');
      },
      inventoryEnabled() {
         return this.$page.props.allSettings.find(s => s.key === 'enable_inventory_tracking' && s.value === 'YES');
      },
      shipmentEnabled() {
         return this.$page.props.allSettings.find(s => s.key === 'enable_shipment_tracking' && s.value === 'YES');
      },
   },
}
</script>

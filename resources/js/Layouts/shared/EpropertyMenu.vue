<template>
   <li :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/eproperty') }">
      <a href="#" class="menu-link menu-toggle">
         <i class='bx bx-building-house menu-icon tf-icons'></i>
         <div class="text-truncate">Eproperty</div>
      </a>
      <ul class="menu-sub">
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/dashboard') }">
            <Link :href="route('admin.epr.dashboard')" class="menu-link">
               <div class="text-truncate">Dashboard</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/eproperty/properties') || $page.url.startsWith('/wp-admin/eproperty/property')}">
            <a href="#" class="menu-link menu-toggle">
               <div class="text-truncate">Properties</div>
            </a>
            <ul class="menu-sub">
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/properties') }">
                  <Link :href="route('admin.epr.properties.index')" class="menu-link">
                     <div class="text-truncate">Properties List</div>
                  </Link>
               </li>
<!--               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/property-units') }">-->
<!--                  <Link :href="route('admin.epr.property-units.index')" class="menu-link">-->
<!--                     <div class="text-truncate">Property Units</div>-->
<!--                  </Link>-->
<!--               </li>-->
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/property-inquiries') }">
                  <Link :href="route('admin.epr.property-inquiries.index')" class="menu-link">
                     <div class="text-truncate">Property Inquiries</div>
                  </Link>
               </li>
               <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/property-reviews') }">
                  <Link :href="route('admin.epr.property-reviews.index')" class="menu-link">
                     <div class="text-truncate">Property Reviews</div>
                  </Link>
               </li>
            </ul>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/eproperty/settings') }">
            <Link :href="route('admin.epr.settings.index')" class="menu-link">
               <div class="text-truncate">Settings</div>
            </Link>
         </li>
      </ul>
   </li>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
   components: {Link}
}
</script>

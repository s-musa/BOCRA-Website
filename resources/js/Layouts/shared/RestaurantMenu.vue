<template>
   <li :class="{ 'menu-item': true, 'active open': $page.url.startsWith('/wp-admin/restaurant') }">
      <a href="#" class="menu-link menu-toggle">
         <i class='bx bx-restaurant menu-icon tf-icons'></i>
         <div class="text-truncate">Restaurant</div>
      </a>
      <ul class="menu-sub">
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/restaurant/dashboard') }">
            <Link :href="route('admin.rst.dashboard')" class="menu-link">
               <div class="text-truncate">Dashboard</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/restaurant/index') }">
            <Link :href="route('admin.rst.index')" class="menu-link">
               <div class="text-truncate">Restaurant</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/restaurant/rooms') }">
            <Link :href="route('admin.rst.rooms.index')" class="menu-link">
               <div class="text-truncate">Rooms</div>
            </Link>
         </li>
         <li :class="{ 'menu-item': true, 'active': $page.url.startsWith('/wp-admin/restaurant/settings') }">
            <Link :href="route('admin.rst.settings.index')" class="menu-link">
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

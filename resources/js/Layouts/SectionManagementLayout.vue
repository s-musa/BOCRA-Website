<template>
   <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
         <!-- Page content -->
         <div class="layout-page p-0">
            <Navbar />
            <div class="content-wrapper">
               <div class="container-fluid flex-grow-1 container-p-y">
                  <slot />
               </div>
               <Footer />
            </div>
         </div>
         <div class="layout-overlay layout-menu-toggle"></div>
      </div>
   </div>
</template>

<script>
import "@plugins/main.js";
import Navbar from './shared/SectionManagementNav.vue';
import Footer from './shared/Footer.vue';

export default {
   components: { Navbar, Footer },
};
</script>

<style>
/* Add your layout-specific styles here if needed */
</style>

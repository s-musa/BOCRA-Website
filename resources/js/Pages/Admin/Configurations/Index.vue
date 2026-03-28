<template>
   <Head title="System Settings"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">System Settings</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item">
                  Settings
               </li>
            </ol>
         </nav>
         
         <div class="col-12">
            <div class="mb-3">
               <ul class="nav nav-pills mb-5" role="tablist">
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'categories' }" @click="activeTab = 'categories'">
                        Categories
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'product-types' }" @click="activeTab = 'product-types'">
                        Product Types
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'tags' }" @click="activeTab = 'tags'">
                        Tags
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'features' }" @click="activeTab = 'features'">
                        Pricing Plan Features
                     </button>
                  </li>
               </ul>
               
               <div v-if="activeTab === 'categories'">
                  <categories></categories>
               </div>
               <div v-if="activeTab === 'product-types'">
                  <product-types></product-types>
               </div>
               <div v-if="activeTab === 'tags'">
                  <tags></tags>
               </div>
               <div v-if="activeTab === 'features'">
                  <features></features>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Link, Head} from "@inertiajs/vue3";
import Categories from "./Categories.vue";
import ProductTypes from "./ProductTypes.vue";
import Tags from "./Tags.vue";
import Features from "./PricingPlanFeatures.vue";

export default {
   components: {DefaultLayout, Head, Link, Categories, ProductTypes, Tags, Features},
   data() {
      return {
         activeTab: 'categories'
      }
   }
}
</script>

<style scoped>
.nav-pills .nav-item .nav-link:not(.active) {
   border: 1px solid #e9ecef;
}
</style>

<template>
   <Head title="Modules"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">Modules</h4>
         <nav class="mb-0">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Module
               </li>
            </ol>
         </nav>
         
         <div class="col-12">
            <p class="tet-muted">
               This feature will help you install different pre-designed modules within the CMS.
            </p>
            <div class="mb-5">
               <v-select class="d-sm-none w-100 mb-7 bg-white" id="moduleTabs" v-model="activeTab" :clearable="false" :options="modules"
                         label="name" :reduce="option => option.slug"
               />
               <ul class="nav nav-pills mb-0 d-none d-sm-flex" role="tablist">
                  <li v-for="item in modules" class="nav-item me-2 border rounded" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === item.slug }" @click="activeTab = item.slug">
                        {{ item.name }}
                     </button>
                  </li>
               </ul>
            </div>
         </div>
         
         <div class="col-12">
            <div class="mb-3">
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import axios from "axios";

export default {
   components: {DefaultLayout, Head, Link},
   props: {
      modules: {
         type: Array,
         required: true,
         default: () => [],
      },
      totalInstalled: {
         type: Number,
         required: true,
      }
   },
   data() {
      return {
         activeTab: '',
         currencies: [],
         countries: [],
      }
   },
   created() {
      if(this.modules) {
         this.findCurrencies();
         this.findCountries();
      }
   },
   methods: {
      async findCurrencies() {
         try {
            const response = await axios.get(route('datatable.currencies'))
            this.currencies = response.data
         } catch (error) {
            console.error(error);
            this.$toast.error('Failed to load currencies', 'Error');
         }
      },
      async findCountries() {
         try {
            const response = await axios.get(route('datatable.countries'))
            this.countries = response.data
         } catch (error) {
            console.error(error);
            this.$toast.error('Failed to load countries', 'Error');
         }
      },
   }
}
</script>

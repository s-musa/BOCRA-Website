<template>
   <div class="container-fluid p-0">
      <div class="authentication-wrapper authentication-cover">
         <a href="/" class="app-brand auth-cover-brand gap-2">
            <span class="app-brand-logo demo">
               <img :src="logo" alt="logo" class="w-auto" style="height: 100px;">
            </span>
         </a>
         <div class="authentication-inner row m-0">
            <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center p-5"
               :style="backgroundStyle">
               <div class="bg-overlay"></div>
            </div>
            
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
               <slot />
            </div>
         </div>
      </div>
   </div>
</template>

<script setup>
import "@plugins/main.js";
import "../../../scss/pages/page-auth.scss";
import {usePage} from "@inertiajs/vue3";
import backgroundImage from '@/cms-login-image.jpg';
import { computed } from "vue";

const logo = usePage().props.logoUrl;

const backgroundStyle = computed(() => ({
   background: `url(${backgroundImage}) center / cover no-repeat`,
}));
</script>

<style>
.bg-overlay {
   position: absolute;
   background-color: rgba(0, 0, 0, 0.2);
   left: 0;
   right: 0;
   top: 0;
   bottom: 0;
   width: inherit !important;
   height: 100%;
}
</style>

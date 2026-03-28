<template>
   <Head title="Customisation"/>
   
   <DefaultLayout>
      <div class="row">
         <h3 class="mb-0">Website Customisation</h3>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Customisations
               </li>
            </ol>
         </nav>
         
         <div class="col-12">
            <div class="mb-3">
               <v-select class="d-sm-none w-100 mb-7 bg-white" id="customisationTabs" v-model="activeTab" :clearable="false" :options="tabs"
                         label="name" :reduce="option => option.value"
               />
               <ul class="nav nav-pills mb-0 d-none d-sm-flex" role="tablist">
                  <li v-for="item in tabs" class="nav-item me-2 border rounded" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === item.value }" @click="activeTab = item.value">
                        {{ item.name }}
                     </button>
                  </li>
               </ul>
            </div>
         </div>
         <div class="col-lg-9 col-md-8 col-12 d-md-block d-none">
            <div class="mb-5">
               <iframe v-if="activeTab !== 'general'" :key="iframeKey" :src="iframeRoute" class="iframe"></iframe>
               <div v-else class="tab-content">
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-4 col-12">
                              <div class="mb-3 ">
                                 <label class="form-label mb-3">Section Width Style <span class="small text-muted text-wrap">(default width)</span></label>
                                 <v-select id="sectionWidthStyle" v-model="generalForm.section_width_style" :options="sectionWidths"
                                           label="name" :reduce="option => option.value"
                                 />
                              </div>
                           </div>
                           <div v-if="generalForm.section_width_style === 'container-fluid'" class="col-md-4 col-12">
                              <div class="form-group mb-3">
                                 <label class="form-label mb-3">Width</label>
                                 <input v-model="generalForm.section_width" type="number" class="form-control">
                              </div>
                           </div>
                           
                           <div class="col-12 my-3">
                              <button type="button" class="btn btn-primary"
                                      @click.prevent="submitGeneralForm" :disabled="generalForm.processing">
                                 Update
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div v-if="activeTab !== 'general'" class="col-lg-3 col-md-4 col-12">
            <div class="tab-content">
               <div v-show="activeTab === 'colors'">
                  <div class="card mb-3">
                     <div class="card-header py-3">
                        <h5 class="card-title mb-0">
<!--                           <i class="bx bxs-brush bx-md"></i>-->
                           Colors
                        </h5>
                     </div>
                  </div>
                  <div class="card">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-12">
                              <div class="mb-3 ">
                                 <label class="form-label mb-3">Primary Color <span class="small text-muted text-wrap">(default color)</span></label>
                                 <input v-model="form.primary_color" type="color" class="form-control form-control-color">
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="form-group mb-3">
                                 <label class="form-label w-100 mb-3">Primary Color Light
                                    <span class="small text-muted text-wrap d-block">(lighter variation of the primary color, can be used for backgrounds)</span></label>
                                 <input v-model="form.primary_color_light" type="color" class="form-control form-control-color">
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="mb-3">
                                 <label class="form-label w-100 mb-3">Secondary Color</label>
                                 <input v-model="form.secondary_color" type="color" class="form-control form-control-color">
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="mb-3">
                                 <label class="form-label w-100 mb-3">Secondary Color Light
                                    <span class="small text-muted text-wrap d-block">(lighter variation of the secondary color, can be used for backgrounds)</span></label>
                                 <input v-model="form.secondary_color_light" type="color" class="form-control form-control-color">
                              </div>
                           </div>
                           <div class="col-12 mt-3 mb-2">
                              <button v-if="!currentCustomisation" type="button" class="btn btn-primary w-100" @click.prevent="submitCustomisations">Save</button>
                              <button v-else type="button" class="btn btn-primary w-100" @click.prevent="updateCustomisations">Save</button>
                           </div>
                           <div class="col-12 my-3">
                              <button v-if="isNotDefault()" type="button" class="btn btn-outline-secondary w-100" @click.prevent="setDefaultCustomisation">
                                 Use Defaults
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div v-show="activeTab === 'buttons'">
                  <buttons
                     :customisation="currentCustomisation"
                     @ButtonStyleUpdated="handleCustomisationUpdated"
                  ></buttons>
               </div>
               <div v-show="activeTab === 'header'">
                  <website-header
                     :customisation="currentCustomisation"
                     @HeaderUpdated="handleCustomisationUpdated"
                  ></website-header>
               </div>
               <div v-show="activeTab === 'footer'">
                  <website-footer
                     :customisation="currentCustomisation"
                     @FooterUpdated="handleCustomisationUpdated"
                  ></website-footer>
               </div>
               <div v-show="activeTab === 'page-banner'">
                  <page-banner
                     :customisation="currentCustomisation"
                     @PageBannerUpdated="handleCustomisationUpdated"
                  ></page-banner>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import axios from "axios";
import { Chrome } from "@ckpack/vue-color";
import Colors from "./Colors.vue"
import Buttons from "./Buttons.vue"
import WebsiteHeader from "./WebsiteHeader.vue"
import WebsiteFooter from "./WebsiteFooter.vue"
import PageBanner from "./PageBanner.vue"

export default {
   components: { DefaultLayout, Head, Link, Buttons, WebsiteHeader, WebsiteFooter, PageBanner },
   data() {
      return {
         form: useForm({
            id: '',
            primary_color: '',
            primary_color_light: '',
            secondary_color: '',
            secondary_color_light: '',
         }),
         generalForm: useForm({
            id: null,
            section_width_style: null,
            section_width: 100,
         }),
         currentCustomisation: [],
         iframeRoute: route('home'),
         iframeKey: 0,
         defaultCustomisation: {
            primary_color: '#163925',
            primary_color_light: '#104a29',
            secondary_color: '#bf8840',
            secondary_color_light: '#efc38b',
         },
         activeTab: 'general',
         tabs: [
            {value: 'general', name: 'General'},
            {value: 'colors', name: 'Colors'},
            {value: 'buttons', name: 'Buttons'},
            {value: 'header', name: 'Header'},
            {value: 'footer', name: 'Footer'},
            {value: 'page-banner', name: 'Page Banner'},
         ],
         sectionWidths: [
            {value: 'container', name: 'Boxed'},
            {value: 'container-fluid', name: 'Full Width'},
         ],
      }
   },
   mounted() {
      this.fetchCustomisations();
   },
   methods: {
      fetchCustomisations() {
         axios.get('/datatable/customisations')
            .then(({data}) => {
               this.currentCustomisation = data;
               if(this.currentCustomisation) {
                  this.populateGeneralForm();
                  this.populateForm();
               }
            })
      },
      populateGeneralForm() {
         this.generalForm.id = this.currentCustomisation.hashid;
         this.generalForm.section_width_style = this.currentCustomisation.section_width_style;
         this.generalForm.section_width = this.currentCustomisation.section_width;
      },
      populateForm() {
         this.form.id = this.currentCustomisation.hashid;
         this.form.primary_color = this.currentCustomisation.primary_color;
         this.form.primary_color_light = this.currentCustomisation.primary_color_light;
         this.form.secondary_color = this.currentCustomisation.secondary_color;
         this.form.secondary_color_light = this.currentCustomisation.secondary_color_light;
      },
      handleCustomisationUpdated(updatedData) {
         this.currentCustomisation = {
            ...this.currentCustomisation,
            ...updatedData
         }
         this.fetchCustomisations();
         setTimeout( () => {
            this.iframeKey++;
         }, 1000)
      },
      submitCustomisations() {
         this.form.post(route('admin.customisations.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.fetchCustomisations();
               setTimeout( () => {
                  this.iframeKey++;
               }, 1000)
               this.$toast.success('Website customisations saved successfully!', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('Something went wrong! Try again!');
            }
         })
      },
      updateCustomisations() {
         this.form.patch(route('admin.customisations.update', this.currentCustomisation.hashid), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.fetchCustomisations();
               setTimeout( () => {
                  this.iframeKey++;
               }, 1000)
               this.$toast.success('Website customisations updated successfully!', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('Something went wrong! Try again!');
            }
         })
      },
      submitGeneralForm() {
         this.generalForm.patch(route('admin.customisations.general-styles', this.currentCustomisation.hashid), {
            onSuccess: () => {
               this.generalForm.reset();
               this.generalForm.clearErrors();
               this.fetchCustomisations();
               setTimeout( () => {
               }, 1000)
               this.$toast.success('Website customisations updated successfully!', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('Something went wrong! Try again!');
            }
         })
      },
      isNotDefault() {
         const d = this.defaultCustomisation;
         const c = this.currentCustomisation;
         return (
            c?.primary_color !== d.primary_color ||
            c?.primary_color_light !== d.primary_color_light ||
            c?.secondary_color !== d.secondary_color ||
            c?.secondary_color_light !== d.secondary_color_light
         );
      },
      setDefaultCustomisation() {
         this.form.primary_color = this.defaultCustomisation.primary_color;
         this.form.primary_color_light = this.defaultCustomisation.primary_color_light;
         this.form.secondary_color = this.defaultCustomisation.secondary_color;
         this.form.secondary_color_light = this.defaultCustomisation.secondary_color_light;
      },
   },
}
</script>

<style scoped>
.iframe {
   width: 100%;
   height: 580px;
   border-radius: 5px;
   border: none;
   overflow: hidden;
}
.tab-content {
   padding: 0 !important;
   background-color: transparent;
   border: none;
   box-shadow: none;
}
.primary-btn {
   background-color: var(--ecbz-primary);
   color: #ffffff;
   font-weight: 700;
   padding: 12px 25px;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   font-size: 1rem;
   text-align: center;
   transition: all 0.8s ease;
   position: relative;
}
.primary-btn.btn-outline {
   border: 2px solid var(--ecbz-primary);
   border-radius: 30px;
   background-color: transparent;
   color: var(--ecbz-primary);
   transition: all 0.6s ease;
}
.primary-btn.btn-outline:hover {
   background-color: var(--ecbz-primary);
   color: #ffffff;
}
.form-check {
   margin-bottom: 0;
}
</style>

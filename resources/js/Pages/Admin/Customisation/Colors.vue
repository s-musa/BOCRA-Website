<template>
   <div class="card mb-3">
      <div class="card-header py-3">
         <h5 class="card-title mb-0">
            <i class="bx bxs-brush bx-md"></i>
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
               <button v-if="!customisation" type="button" class="btn btn-primary w-100" @click.prevent="submitCustomisations">Save</button>
               <button v-else type="button" class="btn btn-primary w-100" @click.prevent="updateCustomisations">Save</button>
            </div>
            <div class="col-12 my-3">
               <button v-if="isNotDefault" type="button" class="btn btn-outline-secondary w-100" @click.prevent="setDefaultCustomisation">
                  Use Defaults
               </button>
            </div>
         </div>
      </div>
   </div>
</template>

<script >
import {useForm} from "@inertiajs/vue3";
import axios from "axios";

export default {
   name: "Colors",
   props: {
      customisation: {
         type: Object,
         required: true,
      },
      defaultCustomisation: {
         type: Object,
         required: true,
      },
   },
   data() {
      return {
         form: useForm({
            id: '',
            primary_color: '',
            primary_color_light: '',
            secondary_color: '',
            secondary_color_light: '',
         }),
      }
   },
   watch: {
      customisation: {
         handler(newVal) {
            if (newVal) {
               this.populateForm()
            }
         },
         immediate: true, // run on mount as well
         deep: true,
      },
   },
   computed: {
      isNotDefault() {
         const d = this.defaultCustomisation;
         const c = this.customisation;
         return (
            c?.primary_color !== d.primary_color ||
            c?.primary_color_light !== d.primary_color_light ||
            c?.secondary_color !== d.secondary_color ||
            c?.secondary_color_light !== d.secondary_color_light
         );
      },
   },
   methods: {
      populateForm() {
         this.form.id = this.customisation.hashid;
         this.form.primary_color = this.customisation.primary_color;
         this.form.primary_color_light = this.customisation.primary_color_light;
         this.form.secondary_color = this.customisation.secondary_color;
         this.form.secondary_color_light = this.customisation.secondary_color_light;
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
         this.form.patch(route('admin.customisations.update', this.customisation.hashid), {
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
      isNotDefault() {
         const d = this.defaultCustomisation;
         const c = this.customisation;
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
   }
}
</script>

<template>
   <div>
      <div class="card mb-3">
         <div class="card-header py-3">
            <h5 class="card-title mb-0">
               Website Header
            </h5>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <div class="mb-3">
                     <label for="headerStyle" class="form-label-md mb-1">Header Style</label>
                     <div class="text-muted mb-3 lh-1">The main header style of your website with the page menus and logo</div>
                     <v-select id="headerStyle" v-model="form.header_style" :options="styles"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.header_style" class="text-danger">{{ form.errors.header_style }}</div>
                  </div>
                  <div v-if="form.header_style !== 'header-transparent'" class="mb-3">
                     <label for="headerBackground" class="form-label-md mb-1">Header Background Color</label>
                     <v-select id="headerBackground" v-model="form.header_bg" :options="headerBackgrounds"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.header_bg" class="text-danger">{{ form.errors.header_bg }}</div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="mb-3 mt-10">
                     <div class="form-check form-switch mb-0">
                        <input class="form-check-input" v-model="form.top_header" type="checkbox" id="topHeader">
                        <label class="form-check-label" for="topHeader">Tagline</label>
                     </div>
                     <span class="text-muted lh-1">Add a tagline/top navigation bar just above the header</span>
                  </div>
               </div>
               <div v-if="form.top_header" class="col-12">
                  <div class="mb-3">
                     <label for="taglineBgStyles" class="form-label-md mb-1">Background Color</label>
                     <v-select id="taglineBgStyles" v-model="form.top_header_bg" :options="topHeaderBackgrounds"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.top_header_bg" class="text-danger">{{ form.errors.top_header_bg }}</div>
                  </div>
               </div>
               <div class="col-12 my-3">
                  <button v-if="!customisation" type="button" class="btn btn-outline-secondary w-100"
                          @click.prevent="submit" :disabled="form.processing">
                     Save
                  </button>
                  <button v-else type="button" class="btn btn-primary"
                          @click.prevent="update" :disabled="form.processing">
                     Update
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import {useForm} from "@inertiajs/vue3";

export default {
   name: 'Header',
   emits: ['HeaderUpdated'],
   props: {
      customisation: {
         type: Object,
         required: true,
      },
   },
   data() {
      return {
         form: useForm({
            id: null,
            header_style: null,
            header_bg: null,
            top_header: false,
            top_header_bg: null,
         }),
         formData: [],
         styles: [
            {value:'default', name: 'Default'},
            {value:'header-transparent', name: 'Transparent Header'},
         ],
         headerBackgrounds: [
            {value:'default', name: 'Default'},
            {value:'bg-gray', name: 'Gray'},
            {value:'bg-black', name: 'Black'},
            {value:'bg-dark', name: 'Dark Gray'},
            {value:'bg-primary', name: 'Primary Color'},
            {value:'bg-secondary', name: 'Secondary Color'},
         ],
         topHeaderBackgrounds: [
            {value:'bg-light', name: 'Default'},
            {value:'bg-primary', name: 'Primary Color'},
            {value:'bg-secondary', name: 'Secondary Color'},
         ],
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
      'form.top_header': function(newVal) {
         if(!newVal) {
            this.form.top_header_bg = null;
         }
      }
   },
   methods: {
      populateForm() {
         this.form.id = this.customisation.hashid;
         this.form.header_style = this.customisation.header_style;
         this.form.header_bg = this.customisation.header_bg;
         this.form.top_header = this.customisation.top_header;
         this.form.top_header_bg = this.customisation.top_header_bg;
      },
      populateFormWithSavedData(data) {
         this.form.id = data.hashid;
         this.form.header_style = data.header_style;
         this.form.header_bg = this.customisation.header_bg;
         this.form.top_header = data.top_header;
         this.form.top_header_bg = data.top_header_bg;
         
         this.formData = [];
      },
      submit() {},
      update() {
         this.formData = this.form.data;
         this.form.patch(route('admin.customisations.header-styles', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.populateFormWithSavedData(this.formData);
               this.$emit('HeaderUpdated', this.formData);
               this.$toast.success('Header Customisation saved!', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('Something went wrong! Try again!');
            }
         })
      },
   }
}
</script>

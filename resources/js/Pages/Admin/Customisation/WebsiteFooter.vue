<template>
   <div>
      <div class="card mb-3">
         <div class="card-header py-3">
            <h5 class="card-title mb-0">
               Website Footer
            </h5>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <div class="mb-3">
                     <label for="footerStyle" class="form-label-md mb-1">Footer Style</label>
                     <div class="text-muted mb-3 lh-1">The main footer of your website</div>
                     <v-select id="footerStyle" v-model="form.footer_style" :options="styles"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.footer_style" class="text-danger">{{ form.errors.footer_style }}</div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="mb-3">
                     <label for="footerBgStyles" class="form-label-md mb-1">Background Color</label>
                     <v-select id="footerBgStyles" v-model="form.footer_bg_color" :options="footerBackgrounds"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.footer_bg_color" class="text-danger">{{ form.errors.footer_bg_color }}</div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="mb-3">
                     <label for="footerText" class="form-label-md mb-1">Footer Text</label>
                     <textarea  id="footerText" v-model="form.footer_text" class="form-control"/>
                     <div v-if="form.errors.footer_text" class="text-danger">{{ form.errors.footer_text }}</div>
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
   name: 'Footer',
   props: {
      customisation: {
         type: Object,
         required: true,
      },
   },
   emits: ['FooterUpdated'],
   data() {
      return {
         form: useForm({
            id: null,
            footer_style: null,
            footer_bg_color: null,
            footer_text: null,
         }),
         formData: [],
         styles: [
            {value:'default', name: 'Default'},
            {value:'footer-with-menu', name: 'Footer With Menu', description: 'Footer that has menu items,'},
         ],
         footerBackgrounds: [
            {value:'bg-dark', name: 'Default'},
            {value:'bg-primary', name: 'Primary color'},
            {value:'bg-secondary', name: 'Secondary color'},
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
   },
   methods: {
      populateForm() {
         if(this.customisation) {
            this.form.id = this.customisation.hashid;
            this.form.footer_style = this.customisation.footer_style
            this.form.footer_bg_color = this.customisation.footer_bg_color;
            this.form.footer_text = this.customisation.footer_text;
         }
      },
      populateFormWithSavedData(data) {
         this.form.id = data.hashid;
         this.form.footer_style = data.footer_style;
         this.form.footer_bg_color = data.footer_bg_color;
         this.form.footer_text = data.footer_text;
         
         this.formData = [];
      },
      submit() {},
      update() {
         this.formData = this.form.data;
         this.form.patch(route('admin.customisations.footer-styles', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.populateFormWithSavedData(this.formData);
               this.$emit('FooterUpdated', this.formData);
               this.$toast.success('Footer Customisation saved!', 'Success');
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

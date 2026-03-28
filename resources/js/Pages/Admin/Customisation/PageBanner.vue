<template>
   <div>
      <div class="card mb-3">
         <div class="card-header py-3">
            <h5 class="card-title mb-0">
               Page Banner
            </h5>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <div class="mb-3">
                     <label for="headerStyle" class="form-label-md mb-1">Banner Style</label>
                     <div class="text-muted mb-3 lh-1">The main page banner style of your website</div>
                     <v-select id="headerStyle" v-model="form.banner_style" :options="styles"
                               label="name" :reduce="option => option.value"
                     >
                        <template #option="{ name, description }">
                           <div class="d-flex flex-column">
                              <span>{{ name }}</span>
                              <small class="text-success">{{ description }}</small>
                           </div>
                        </template>
                        <template #selected-option="{ name, description }">
                           <div class="d-flex flex-column">
                              <span>{{ name }}</span>
                              <small class="text-success">{{ description }}</small>
                           </div>
                        </template>
                     </v-select>
                     <div v-if="form.errors.banner_style" class="text-danger">{{ form.errors.banner_style }}</div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="mb-3">
                     <label for="pageBannerBg" class="form-label-md mb-1">Background Color</label>
                     <v-select id="pageBannerBg" v-model="form.banner_bg" :options="bannerBackgrounds"
                               label="name" :reduce="option => option.value"
                     />
                     <div v-if="form.errors.banner_bg" class="text-danger">{{ form.errors.banner_bg }}</div>
                  </div>
               </div>
               <div class="col-12">
                  <div class="mb-3">
                     <label for="pageBannerImage" class="form-label-md mb-1">Banner Image</label>
                     <Dropzone v-model="form.banner_image" :multiple="false" :existing="form.banner_image ? form.banner_image : []"
                               :upload-url="`/wp-admin/medias/banner-image/${customisation.id}`"
                               :delete-url="`/wp-admin/medias/delete-banner-image/${customisation.id}`"
                     />
                     <div v-if="form.errors.banner_image" class="text-danger">{{ form.errors.banner_image }}</div>
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
   name: 'PageBanner',
   props: {
      customisation: {
         type: Object,
         required: true,
      },
   },
   emits: ['PageBannerUpdated'],
   data() {
      return {
         form: useForm({
            id: null,
            banner_style: null,
            banner_bg: null,
            banner_image: null,
         }),
         formData: [],
         styles: [
            {value:'default', name: 'Default'},
            {
               value: 'banner-with-no-image',
               name: 'Banner With No Background Image',
               description: 'This page banner is similar to the default one but it does not display the background image.'
            },
            {
               value: 'banner-layout-vertical',
               name: 'Page Banner Layout Vertical',
               description: 'Page banner with the content displayed in a vertical layout.'
            },
            {value: 'banner-layout-vertical-no-image', name: 'Page Banner Layout Vertical With No Image'},
            {
               value: 'banner-with-no-breadcrumb',
               name: 'Page Banner Without Breadcrumb',
               description: 'Page banner similar to the vertical layout but with no breadcrumb displayed'
            },
         ],
         bannerBackgrounds: [
            {value:'bg-gradient-overlay', name: 'Default'},
            {value:'bg-blue-overlay', name: 'Blue overlay'},
            {value:'bg-primary-gradient-overlay', name: 'Primary color'},
            {value:'bg-secondary-gradient-overlay', name: 'Secondary color'},
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
         immediate: true,
         deep: true,
      },
   },
   methods: {
      populateForm() {
         this.form.id = this.customisation.hashid;
         this.form.banner_style = this.customisation.banner_style;
         this.form.banner_bg = this.customisation.banner_bg;
         const bannerImage = this.customisation.media?.find(md => md.collection_name === 'banner-image') ?? null;
         if (bannerImage) {
            this.form.banner_image = [{
               id: bannerImage.model_id,
               name: bannerImage.file_name,
               size: bannerImage.size,
               url: bannerImage.original_url, // or full_url depending on Spatie MediaLibrary
               type: bannerImage.mime_type,
               isExisting: true, // flag so you know it’s already uploaded
            }];
         } else {
            this.form.banner_image = null;
         }
      },
      populateFormWithSavedData(data) {
         this.form.id = data.hashid;
         this.form.banner_style = data.banner_style;
         this.form.banner_bg = data.banner_bg;
         
         this.formData = [];
      },
      submit() {},
      update() {
         this.formData = this.form.data;
         this.form.patch(route('admin.customisations.banner-styles', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.populateFormWithSavedData(this.formData);
               this.$emit('PageBannerUpdated', this.formData);
               this.$toast.success('Page Banner Customisation saved!', 'Success');
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

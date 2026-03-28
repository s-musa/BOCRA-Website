<template>
   <form @submit.prevent="submitForm">
      <div class="row">
         <!-- Section Type Selection -->
         <div class="col-md-4 col-12">
            <div class="mb-3">
               <label for="sectionType" class="form-label-md mb-2 d-md-flex d-block">
                  Section Type
                  <span v-if="showMapToggle" class="form-check form-switch ms-auto py-md-0 py-3">
                     <input v-model="formData.section_has_map" class="form-check-input" type="checkbox" id="hasMap">
                     <label class="form-check-label" for="hasMap"> Include a map </label>
                  </span>
               </label>
               <v-select
                  id="sectionType"
                  v-model="formData.type"
                  :options="sectionTypes"
                  label="name"
                  :reduce="option => option.value"
               >
                  <template #option="{ name, description }">
                     <div class="d-flex flex-column">
                        <span>{{ name }}</span>
                        <small class="text-primary">{{ description }}</small>
                     </div>
                  </template>
               </v-select>
            </div>
         </div>
         
         <!-- Dynamic Form Fields Based on Section Type -->
         <component
            :is="currentFormComponent"
            v-if="formData.type"
            v-model="formData"
            :pages="pages"
            :ecm-categories="ecmCategories"
         />
         
         <!-- Submit Button -->
         <div class="col-12 mt-5">
            <button type="submit" class="btn btn-primary" :disabled="processing">
               {{ processing ? 'Processing...' : (isEdit ? 'Update Section' : 'Create Section') }}
            </button>
         </div>
      </div>
   </form>
</template>

<script>
import HeroSectionForm from "./form-types/HeroSectionForm.vue";
import SectionWithImageForm from "./form-types/SectionWithImageForm.vue";
import SectionWithCardsForm from "./form-types/SectionWithCardsForm.vue";
import SectionWithContactForm from "./form-types/SectionWithContactForm.vue";
import SectionWithServicesForm from "./form-types/SectionWithServicesForm.vue";
import SectionWithProjectsForm from "./form-types/SectionWithProjectsForm.vue";
import GenericSectionForm from "./form-types/GenericSectionForm.vue";

export default {
   components: {
      HeroSectionForm,
      SectionWithImageForm,
      SectionWithCardsForm,
      SectionWithContactForm,
      SectionWithServicesForm,
      SectionWithProjectsForm,
      GenericSectionForm
   },
   props: {
      pageId: {type: Number, required: true},
      sectionTypes: {type: Array, required: true},
      initialData: {type: Object, default: () => ({})},
      isEdit: {type: Boolean, default: false}
   },
   data() {
      return {
         formData: {
            page_id: this.pageId,
            type: null,
            title: null,
            sub_title: null,
            details: null,
            component_type: null,
            section_image_first: false,
            include_contact_cards: false,
            has_cta_buttons: false,
            cta_buttons: [],
            section_cards: [],
            hero_slides: [],
            section_has_image: false,
            section_has_map: false,
            section_has_bg: false,
            section_background_type: null,
            section_background_color: null,
            section_style: null,
            background_image: null,
            media: null,
            map_link: null,
            category_id: null,
            ...this.initialData
         },
         pages: [],
         ecmCategories: [],
         processing: false
      }
   },
   computed: {
      currentFormComponent() {
         const formMap = {
            'hero-section': 'HeroSectionForm',
            'section-with-image': 'SectionWithImageForm',
            'section-with-cards': 'SectionWithCardsForm',
            'section-with-contact-form': 'SectionWithContactForm',
            'section-with-services': 'SectionWithServicesForm',
            'section-with-projects': 'SectionWithProjectsForm',
         };
         return formMap[this.formData.type] || 'GenericSectionForm';
      },
      showMapToggle() {
         return ['section-with-contact-form', 'section-with-contact-cards'].includes(this.formData.type);
      }
   },
   watch: {
      'formData.type': function(newVal) {
         this.formData.section_has_image = ['hero-section', 'section-with-image', 'why-us-section'].includes(newVal);
         if (!this.formData.section_has_image) this.formData.media = null;
      }
   },
   mounted() {
      this.fetchPages();
      this.fetchEcommerceProductCategories();
   },
   methods: {
      submitForm() {
         this.$emit('submit', this.formData);
      },
      fetchPages() {
         axios.get(route('datatable.pages'), {
            params: {filter: {'published': true}}
         }).then(({data}) => {
            this.pages = data.data;
         }).catch(() => {
            this.$toast.error("An error occurred while fetching pages!", "Error");
         })
      },
      fetchEcommerceProductCategories() {
         const ecommerce = this.$page.props.modules.find(m => m.slug === 'ecommerce' && m.installed && m.enabled);
         if(ecommerce) {
            axios.get(route('admin.ecm_datatable.categories'), {
               params: {filter: {'active': true}}
            }).then(({data}) => {
               this.ecmCategories = data.data;
            }).catch(() => {
               this.$toast.error("An error occurred while fetching categories!", "Error");
            })
         }
      }
   }
}
</script>

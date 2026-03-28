<template>
   <Head title="Page Sections"/>
   
   <DefaultLayout>
   <div class="row">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
         <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1">{{ page.title }} Page Sections</h4>
            <p class="mb-0">Manage the page sections that are being displayed on the website</p>
         </div>
         <div class="d-flex align-content-center flex-wrap gap-4">
            <Link :href="route('admin.pages.index')" class="btn btn-light">
               Back
            </Link>
            <Link :href="route('admin.pages.sections.create', page.hashid)" class="btn btn-primary d-none d-sm-inline-block">
               Add Section
            </Link>
            <Link :href="route('admin.pages.sections.create', page.hashid)" class="btn btn-primary btn-icon d-sm-none">
               <i class="bx bx-plus"></i>
            </Link>
         </div>
      </div>
      
      <div class="col-xl-12 col-md-12">
         <div class="mb-3">
            <div class="accordion">
               <draggable :list="pageSections" class="drag-area" @change="handleDraggableChange">
                  <SectionAccordionItem
                     v-for="(section, index) in pageSections"
                     :key="section.id"
                     :section="section"
                     :index="index"
                     :is-open="openAccordion === `${section.id}`"
                     @toggle="toggleAccordion(`${section.id}`, section)"
                     @delete="deleteSection"
                  />
               </draggable>
            </div>
         </div>
      </div>
   </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import SectionAccordionItem from "./components/SectionAccordionItem.vue";
import _debounce from "lodash/debounce.js";

export default {
   components: {DefaultLayout, Head, Link, SectionAccordionItem},
   props: {
      page: {type: Object, required: true},
   },
   data() {
      return {
         pageSections: [],
         openAccordion: '',
      }
   },
   mounted() {
      this.fetchSections();
   },
   methods: {
      toggleAccordion(sectionKey, section) {
         this.openAccordion = this.openAccordion === sectionKey ? null : sectionKey;
      },
      fetchSections() {
         axios.get(route('datatable.sections'), {
            params: {filter: {'page_id': this.page.id}}
         }).then(({data}) => {
            this.pageSections = data.data;
         }).catch((error) => {
            this.$toast.error("An error occurred while fetching the page sections!", "Error");
         })
      },
      handleDraggableChange: _debounce(function(event) {
         if (event.moved) {
            const orderedIds = this.pageSections.map((section, index) => ({
               id: section.id,
               order: index + 1
            }));
            
            this.$inertia.post('/wp-admin/sections/update-section-order', {sections: orderedIds}, {
               onSuccess: () => {
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Sections order updated successfully', 'Success');
                  }, 400)
               }
            })
         }
      }, 500),
      deleteSection(section) {
         this.$toast.question(`Are you sure?`, 'Delete Section!').then(() => {
            this.$inertia.delete(route('admin.pages.sections.delete', section.id), {
               onSuccess: () => {
                  this.$toast.success('Page section deleted successfully!', 'Success');
                  this.openAccordion = '';
                  setTimeout(() => {
                     this.fetchSections();
                  }, 300)
               }
            })
         });
      },
   }
}
</script>

<style scoped>
.accordion-item {
   border-radius: 0.375rem;
   border-bottom: none;
}
.crd-custom {
   border: 1px solid rgb(228.48, 230.16, 231.84);
   box-shadow:none;
}
.table thead {
   background-color: rgba(228.48, 230.16, 231.84, 0.3);
}
.table thead tr th {
   padding: 0.65rem;
}
.table tbody tr td {
   padding: 0.55rem;
}
</style>

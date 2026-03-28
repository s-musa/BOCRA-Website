<template>
   <Head title="Create Section"/>
   
   <DefaultLayout>
      <div class="row">
         <div class="d-flex justify-content-between align-items-center mb-6">
            <div>
               <h4 class="mb-1">Create New Section</h4>
               <p class="mb-0">for {{ page.title }} page</p>
            </div>
            <Link :href="route('admin.pages.sections.index', page.hashid)" class="btn btn-light">
               Back to Sections
            </Link>
         </div>
         
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <SectionForm
                     :page-id="page.id"
                     :section-types="sectionTypes"
                     @submit="handleSubmit"
                  />
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import SectionForm from "./components/SectionForm.vue";

export default {
   components: {DefaultLayout, Head, Link, SectionForm},
   props: {
      page: {type: Object, required: true},
      sectionTypes: {type: Array, required: true},
   },
   methods: {
      handleSubmit(formData) {
         const form = useForm(formData);
         form.post(route('admin.pages.sections.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.$toast.success('Page section created successfully', 'Success');
               this.$inertia.visit(route('admin.pages.sections.index', this.page.id));
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      }
   }
}
</script>

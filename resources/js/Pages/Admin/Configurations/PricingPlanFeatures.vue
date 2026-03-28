<template>
   <div class="row">
      <div class="col-lg-4 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header d-flex align-items-center pt-3">
                  <div class="card-title mb-0">
                     <h5 class="m-0">Add New Pricing Plan Feature</h5>
                  </div>
               </div>
               <div class="card-body">
                  <div class="mb-3">
                     <label for="name" class="form-label-md mb-1">Name</label>
                     <input id="name" type="text" v-model="form.name" class="form-control">
                     <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                  </div>
                  <div class="mb-3">
                     <label class="row d-flex">
                        <span class="col">
                           <span class="fw-bold me-3">Active</span>
                        </span>
                        <span class="col-auto">
                           <label class="form-check form-switch">
                              <input v-model="form.is_included" class="form-check-input" type="checkbox">
                           </label>
                        </span>
                     </label>
                  </div>
                  <div class="mb-3 mt-5 text-end">
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createPricingPlanFeature" :disabled="form.processing">
                        Submit
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-8 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header flex-column flex-md-row">
                  <div class="row row-gap-1">
                     <div class="col-md-5 col-9">
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Features"
                               @input="applyFilter" v-model="appendParams.filter.name">
                     </div>
                  </div>
               </div>
               
               <VueTable
                  :fields="fields"
                  api-url="datatable/features"
                  :append-params="appendParams"
                  ref="featuresTable"
               >
                  <template #status="props">
                     <span v-if="props.rowData.is_included" class="badge bg-success">
                        Active
                     </span>
                     <span v-else-if="!props.rowData.is_included" class="badge bg-danger">
                        Deactivated
                     </span>
                     <span v-else class="badge bg-secondary">
                        Unknown
                     </span>
                  </template>
                  <template v-slot:actions="props">
                     <div class="dropdown">
                        <button class="btn align-text-top py-1" data-bs-toggle="dropdown">
                           <i class="bx bx-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                           <a class="dropdown-item" href="#" @click.prevent="editPricingPlanFeature(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deletePricingPlanFeature(props.rowData)">
                              <i class="bx bx-trash me-2"></i>Delete
                           </a>
                        </div>
                     </div>
                  </template>
               </VueTable>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-pricing-plan-feature-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-pricing-plan-feature-modal-label" aria-hidden="true" ref="editPricingPlanFeatureModal"
      >
         <div class="modal-dialog modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-pricing-plan-feature-modal-label">Edit Pricing Plan Feature</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updatePricingPlanFeature">
                     <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="text" v-model="editForm.name" class="form-control">
                        <div v-if="editForm.errors.name" class="text-danger">{{ editForm.errors.name }}</div>
                     </div>
                     <div class="mb-3">
                        <label class="row d-flex">
                           <span class="col">
                              <span class="fw-bold me-3">Active</span>
                           </span>
                           <span class="col-auto">
                              <label class="form-check form-switch">
                                 <input v-model="editForm.is_included" class="form-check-input" type="checkbox">
                              </label>
                           </span>
                        </label>
                     </div>
                  </form>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                          @click="editFormCleanUp" :disabled="editForm.processing">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary"
                          @click.prevent="updatePricingPlanFeature" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import { Modal } from 'bootstrap';
import _debounce from "lodash/debounce.js";

export default {
   data() {
      return {
         fields: [
            {
               name: 'name',
               title: 'Name',
               titleClass: '75%',
               dataClass: '75%',
            },
            {
               name: '__slot:status',
               title: 'STATUS',
               titleClass: '20%',
               dataClass: '20%',
            },
            {
               name: '__slot:actions',
               title: 'ACTIONS',
               titleClass: '5%',
               dataClass: '5%',
            },
         ],
         appendParams: {
            filter: {
               name: '',
            }
         },
         form: useForm({
            name: '',
            is_included: true,
         }),
         editForm: useForm({
            id: '',
            name: '',
            is_included: true,
         }),
      };
   },
   methods: {
      createPricingPlanFeature() {
         this.form.post(route('admin.settings.features.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.featuresTable.reloadTable();
               this.$toast.success('Pricing Plan Feature Created Successfully', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editPricingPlanFeature(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.is_included = rowData.is_included;
         const modalElement = this.$refs.editPricingPlanFeatureModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updatePricingPlanFeature() {
         this.editForm.patch(route('admin.settings.features.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.featuresTable.reloadTable();
               const modalElement = this.$refs.editPricingPlanFeatureModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Pricing Plan Feature Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deletePricingPlanFeature(feature) {
         this.$toast.question('Are you sure?', `Deleting ${feature.name}`).then(() => {
            this.$inertia.delete(route('admin.settings.features.destroy', feature.id), {
               onSuccess: () => {
                  this.$toast.success('Pricing Plan Feature deleted', 'Success');
                  this.$refs.featuressTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the feature!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.featuresTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
      },
   },
}
</script>

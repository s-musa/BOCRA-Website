<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Pricing Plans"
                      @input="applyFilter" v-model="appendParams.filter.name">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreatePricingPlanModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Pricing Plan
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreatePricingPlanModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/plans"
         :append-params="appendParams"
         ref="pricingPlansTable"
      >
         <template #status="props">
            <span v-if="props.rowData.active" class="badge bg-success">
               Active
            </span>
            <span v-else-if="!props.rowData.active" class="badge bg-danger">
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
                  <a class="dropdown-item" href="#" @click.prevent="editPricingPlan(props.rowData)">
                     <i class="fs-5 bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item text-danger disabled" href="#" @click.prevent="deletePricingPlan(props.rowData)">
                     <i class="fs-5 bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div class="modal fade" id="create-pricing-plan-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-pricing-plan-modal-label" aria-hidden="true" ref="createPricingPlanModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-pricing-plan-modal-label">Add Pricing Plan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="createPricingPlan">
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="name" class="form-label-md mb-1">Name</label>
                           <input id="name" type="text" v-model="form.name" class="form-control">
                           <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="tagline" class="form-label-md mb-1">Tagline</label>
                           <input id="tagline" type="text" v-model="form.tagline" class="form-control">
                           <div v-if="form.errors.tagline" class="text-danger">{{ form.errors.tagline }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="price" class="form-label-md mb-1">Price</label>
                           <money v-model="form.price" id="price"></money>
                           <div v-if="form.errors.price" class="text-danger">{{ form.errors.price }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="billing_period" class="form-label-md mb-1">Billing Period</label>
                           <input id="billing_period" type="text" v-model="form.billing_period" class="form-control">
                           <div v-if="form.errors.billing_period" class="text-danger">{{ form.errors.billing_period }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="billing_period_label" class="form-label-md mb-1">Billing Period Label</label>
                           <input id="billing_period_label" type="text" v-model="form.billing_period_label" class="form-control">
                           <div v-if="form.errors.billing_period_label" class="text-danger">{{ form.errors.billing_period_label }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="pricingFeatures" class="form-label-md mb-1">Features</label>
                           <v-select id="pricingFeatures" multiple v-model="form.features" :options="fetchedFeatures"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.features" class="text-danger">{{ form.errors.features }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="button_text" class="form-label-md mb-1">Button Text</label>
                           <input id="button_text" type="text" v-model="form.button_text" class="form-control">
                           <div v-if="form.errors.button_text" class="text-danger">{{ form.errors.button_text }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="page_id" class="form-label-md mb-1">Page</label>
                           <v-select id="page_id" v-model="form.page_id" :options="pages"
                                     label="title" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.page_id" class="text-danger">{{ form.errors.page_id }}</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="description" class="form-label-md mb-1">Description</label>
                        <editor v-model="form.description" id="editor" class="editor-control" />
                        <div v-if="form.errors.description" class="text-danger">{{ form.errors.description }}</div>
                     </div>
                     <div class="mb-3">
                        <label class="row d-flex">
                           <span class="col">
                              <span class="fw-bold me-3">Active</span>
                           </span>
                           <span class="col-auto">
                              <label class="form-check form-switch">
                                 <input v-model="form.active" class="form-check-input" type="checkbox">
                              </label>
                           </span>
                           <span class="form-check-description">When enabled, the pricing plan will be displayed on the website.</span>
                        </label>
                     </div>
                  </div>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                          @click="formCleanUp" :disabled="form.processing">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary"
                          @click.prevent="createPricingPlan" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-pricing-plan-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-pricing-plan-modal-label" aria-hidden="true" ref="editPricingPlanModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-pricing-plan-modal-label">Edit Pricing Plan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="editFormCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="editForm" class="row" @submit.prevent="updatePricingPlan">
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="name" class="form-label-md mb-1">Name</label>
                           <input id="name" type="text" v-model="editForm.name" class="form-control">
                           <div v-if="editForm.errors.name" class="text-danger">{{ editForm.errors.name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="slug" class="form-label-md mb-1">Slug</label>
                           <input id="slug" type="text" v-model="editForm.slug" class="form-control">
                           <div v-if="editForm.errors.slug" class="text-danger">{{ editForm.errors.slug }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="tagline" class="form-label-md mb-1">Tagline</label>
                           <input id="tagline" type="text" v-model="editForm.tagline" class="form-control">
                           <div v-if="editForm.errors.tagline" class="text-danger">{{ editForm.errors.tagline }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="price" class="form-label-md mb-1">Price</label>
                           <money v-model="editForm.price" id="price"></money>
                           <div v-if="editForm.errors.price" class="text-danger">{{ editForm.errors.price }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="billing_period" class="form-label-md mb-1">Billing Period</label>
                           <input id="billing_period" type="text" v-model="editForm.billing_period" class="form-control">
                           <div v-if="editForm.errors.billing_period" class="text-danger">{{ editForm.errors.billing_period }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="billing_period_label" class="form-label-md mb-1">Billing Period Label</label>
                           <input id="billing_period_label" type="text" v-model="editForm.billing_period_label" class="form-control">
                           <div v-if="editForm.errors.billing_period_label" class="text-danger">{{ editForm.errors.billing_period_label }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="pricingFeatures" class="form-label-md mb-1">Features</label>
                           <v-select id="pricingFeatures" multiple v-model="editForm.features" :options="fetchedFeatures"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.features" class="text-danger">{{ editForm.errors.features }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="button_text" class="form-label-md mb-1">Button Text</label>
                           <input id="button_text" type="text" v-model="editForm.button_text" class="form-control">
                           <div v-if="editForm.errors.button_text" class="text-danger">{{ editForm.errors.button_text }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="page_id" class="form-label-md mb-1">Page</label>
                           <v-select id="page_id" v-model="editForm.page_id" :options="pages"
                                     label="title" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.page_id" class="text-danger">{{ editForm.errors.page_id }}</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="description" class="form-label-md mb-1">Description</label>
                        <editor v-model="editForm.description" id="editor" class="editor-control" />
                        <div v-if="editForm.errors.description" class="text-danger">{{ editForm.errors.description }}</div>
                     </div>
                     <div class="mb-3">
                        <label class="row d-flex">
                           <span class="col">
                              <span class="fw-bold me-3">Active</span>
                           </span>
                           <span class="col-auto">
                              <label class="form-check form-switch">
                                 <input v-model="editForm.active" class="form-check-input" type="checkbox">
                              </label>
                           </span>
                           <span class="form-check-description">When enabled, the pricing plan will be displayed on the website.</span>
                        </label>
                     </div>
                  </div>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                          @click="editFormCleanUp" :disabled="editForm.processing">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary"
                          @click.prevent="updatePricingPlan" :disabled="editForm.processing">
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
import Editor from '@/Components/global/Editor.vue'
import axios from "axios";
import Money from "@components/global/Money.vue";

export default {
   components: {
      Money,
      Editor,
   },
   data() {
      return {
         fields: [
            {
               name: 'name',
               title: 'NAME',
            },
            {
               name: 'slug',
               title: 'SLUG',
            },
            {
               name: '__slot:status',
               title: 'STATUS',
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
            name: null,
            tagline: null,
            description: null,
            price: 0,
            billing_period: null,
            billing_period_label: null,
            button_text: '',
            button_url: null,
            page_id: null,
            featured: true,
            active: true,
            features: [],
         }),
         editForm: useForm({
            id: null,
            name: null,
            slug: null,
            tagline: null,
            description: null,
            price: 0,
            billing_period: null,
            billing_period_label: null,
            button_text: '',
            button_url: null,
            page_id: null,
            featured: true,
            active: true,
            features: [],
         }),
         fetchedFeatures: [],
         pages: [],
      };
   },
   mounted() {
      this.fetchFeatures();
      this.fetchPages();
   },
   methods: {
      fetchFeatures() {
         axios.get(route('datatable.features'), {
            params: {
               filter: {
                  'is_included': true,
               }
            }
         })
         .then(({data}) => {
            this.fetchedFeatures = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the features!", "Error");
         })
      },
      fetchPages() {
         axios.get(route('datatable.pages'), {
            params: {
               filter: {
                  'published': true,
               }
            }
         })
         .then(({data}) => {
            this.pages = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the pages!", "Error");
         })
      },
      showCreatePricingPlanModal() {
         const modalElement = this.$refs.createPricingPlanModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createPricingPlan() {
         this.form.post(route('admin.components.pricing-plans.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.pricingPlansTable.reloadTable();
               const modalElement = this.$refs.createPricingPlanModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Pricing Plan Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editPricingPlan(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.slug = rowData.slug;
         this.editForm.tagline = rowData.tagline;
         this.editForm.price = rowData.price * 0.01;
         this.editForm.billing_period = rowData.billing_period;
         this.editForm.billing_period_label = rowData.billing_period_label;
         this.editForm.description = rowData.description;
         this.editForm.button_text = rowData.button_text;
         this.editForm.button_url = rowData.button_url;
         this.editForm.page_id = rowData.page_id;
         this.editForm.active = rowData.active;
         this.editForm.featured = rowData.featured;
         this.editForm.features =rowData.features?.map(feature => feature.id) ?? [];
         
         const modalElement = this.$refs.editPricingPlanModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updatePricingPlan() {
         this.editForm.patch(route('admin.components.pricing-plans.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.pricingPlansTable.reloadTable();
               const modalElement = this.$refs.editPricingPlanModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Pricing Plan Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deletePricingPlan(plan) {
         this.$toast.question('Are you sure?', `Deleting ${plan.name}`).then(() => {
            this.$inertia.delete(route('admin.components.pricing-plans.destroy', plan.hashid), {
               onSuccess: () => {
                  this.$toast.success('Pricing Plan deleted', 'Success');
                  this.$refs.pricingPlansTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the plan!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.pricingPlansTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
         this.editForm.media = null;
      },
      // mediaModalCleanUp() {
      //    this.mediaForm.reset();
      //    this.mediaForm.clearErrors();
      //    this.mediaForm.file = null;
      //    this.selectedMedia = [];
      // },
   },
}
</script>

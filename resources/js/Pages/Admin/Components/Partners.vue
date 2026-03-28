<template>
   <div class="row">
      <div class="col-lg-4 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header d-flex align-items-center pt-3">
                  <div class="card-title mb-0">
                     <h5 class="m-0">Add New Partner</h5>
                  </div>
               </div>
               <div class="card-body">
                  <div class="mb-3">
                     <label for="name" class="form-label-md mb-1">Name</label>
                     <input id="name" type="text" v-model="form.name" class="form-control">
                     <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                  </div>
                  <div class="mb-3">
                     <label for="partnerMedia" class="form-label-md mb-1">Partner Image</label>
                     <input id="partnerMedia" @change="handleMediaUpload" type="file" accept="image/*" class="form-control">
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
                        <span class="form-check-description">When enabled, the partner will be displayed in the system.</span>
                     </label>
                  </div>
                  <div class="mb-3 mt-5 text-end">
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createPartner" :disabled="form.processing">
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
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Partners"
                               @input="applyFilter" v-model="appendParams.filter.name">
                     </div>
                  </div>
               </div>
               
               <VueTable
                  :fields="fields"
                  api-url="datatable/partners"
                  :append-params="appendParams"
                  ref="partnersTable"
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
                           <a class="dropdown-item" href="#" @click.prevent="editPartner(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item" href="#" @click.prevent="openPartnerMediaModal(props.rowData)">
                              <i class="bx bx-image-add me-2"></i>Change Image
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deletePartner(props.rowData)">
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
      <div class="modal fade" id="edit-partner-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-partner-modal-label" aria-hidden="true" ref="editPartnerModal"
      >
         <div class="modal-dialog modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-partner-modal-label">Edit Partner</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updatePartner">
                     <div class="mb-3">
                        <label for="name" class="form-label-md mb-1">Name</label>
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
                                 <input v-model="editForm.active" class="form-check-input" type="checkbox">
                              </label>
                           </span>
                           <span class="form-check-description">When enabled, the partner will be displayed in the system.</span>
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
                          @click.prevent="updatePartner" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Partner Media Upload Modal -->
      <div class="modal fade" id="partner-media-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="partner-media-upload-modal-label" aria-hidden="true" ref="partnerMediaUploadModal">
         <div class="modal-dialog  modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="logo-upload-modal-label">Upload New Image</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="mediaModalCleanUp" :disabled="mediaForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="uploadMedia">
                     <div class="mb-3">
                        <h6>Current Image</h6>
                        <div v-if="selectedMedia.id" class="card card-img p-5">
                           <img :src="selectedMedia.original_url" alt="image" style="width:250px; height:auto;">
                        </div>
                        <p v-else class="text-muted">
                           No Image found for this partner
                        </p>
                     </div>
                     <div class="mb-3">
                        <label for="title" class="form-label">New Image</label>
                        <input @change="handleMediaChange" id="partnerImage" type="file" accept="image/*"
                           required class="form-control mb-3"/>
                        <div v-if="mediaForm.errors.file" class="text-danger">{{ mediaForm.errors.file }}</div>
                     </div>
                     <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                        Upload Image
                     </button>
                  </form>
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

export default {
   components: {
      Editor,
   },
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
            media: null,
            active: true,
         }),
         editForm: useForm({
            id: '',
            name: '',
            active: true,
         }),
         mediaForm: useForm({
            partner_id: '',
            file: null,
         }),
         selectedMedia: [],
      };
   },
   methods: {
      handleMediaUpload(event) {
         const file = event.target.files[0];
         if (file) {
            this.form.media = file;
         }
      },
      handleMediaChange(event) {
         const file = event.target.files[0];
         if (file) {
            this.mediaForm.file = file;
         }
      },
      createPartner() {
         this.form.post(route('admin.components.partners.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.partnersTable.reloadTable();
               this.$toast.success('Partner Created Successfully', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editPartner(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.active = rowData.active;
         const modalElement = this.$refs.editPartnerModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updatePartner() {
         this.editForm.patch(route('admin.components.partners.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.partnersTable.reloadTable();
               const modalElement = this.$refs.editPartnerModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Partner Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openPartnerMediaModal(partner) {
         const image = partner.media.find(m => m.collection_name === 'partner-image')
         if (image) {
            this.selectedMedia = image;
         }
         this.mediaForm.partner_id = partner.id;
         const modalElement = this.$refs.partnerMediaUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      uploadMedia() {
         this.mediaForm.post(route('admin.medias.upload.partner-image'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            onSuccess: () => {
               this.mediaForm.reset();
               this.mediaForm.clearErrors();
               this.$refs.partnersTable.reloadTable();
               this.$toast.success('Image changed', 'Updated');
               const modalElement = this.$refs.partnerMediaUploadModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      deletePartner(partner) {
         this.$toast.question('Are you sure?', `Deleting ${partner.name}`).then(() => {
            this.$inertia.delete(route('admin.components.partners.destroy', partner.id), {
               onSuccess: () => {
                  this.$toast.success('Partner deleted', 'Success');
                  this.$refs.partnersTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the partner!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.partnersTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
      },
      editFormCleanUp() {
         this.form.reset();
         this.form.clearErrors();
      },
      mediaModalCleanUp() {
         this.mediaForm.reset();
         this.mediaForm.clearErrors();
         this.mediaForm.file = null;
         this.selectedMedia = [];
      },
   },
}
</script>

<template>
   <div class="row">
      <div class="col-lg-4 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header d-flex align-items-center pt-3">
                  <div class="card-title mb-0">
                     <h5 class="m-0">Add New Client</h5>
                  </div>
               </div>
               <div class="card-body">
                  <div class="mb-3">
                     <label for="name" class="form-label-md mb-1">Name</label>
                     <input id="name" type="text" v-model="form.name" class="form-control">
                     <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                  </div>
                  <div class="mb-3">
                     <label for="clientMedia" class="form-label-md mb-1">Image</label>
                     <Dropzone @files-added="handleMediaUpload" :multiple="false" imgWidth="100%"
                               selectFileStrategy="replace"/>
                     <div v-if="form.errors.media" class="text-danger">{{ form.errors.media }}</div>
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
                        <span class="form-check-description">When enabled, the client will be displayed in the system.</span>
                     </label>
                  </div>
                  <div class="mb-3 mt-5 text-end">
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createClient" :disabled="form.processing">
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
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Clients"
                               @input="applyFilter" v-model="appendParams.filter.name">
                     </div>
                  </div>
               </div>
               
               <VueTable
                  :fields="fields"
                  api-url="datatable/clients"
                  :append-params="appendParams"
                  ref="clientsTable"
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
                           <a class="dropdown-item" href="#" @click.prevent="editClient(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deleteClient(props.rowData)">
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
      <div class="modal fade" id="edit-client-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-client-modal-label" aria-hidden="true" ref="editClientModal"
      >
         <div class="modal-dialog modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-client-modal-label">Edit Clients</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updateClient">
                     <div class="mb-3">
                        <label for="name" class="form-label-md mb-1">Name</label>
                        <input id="name" type="text" v-model="editForm.name" class="form-control">
                        <div v-if="editForm.errors.name" class="text-danger">{{ editForm.errors.name }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="name" class="form-label-md mb-1">Image</label>
                        <Dropzone v-model="editForm.media" :multiple="false"
                                  :existing="editForm.media ? editForm.media : []"
                                  :upload-url="`/wp-admin/medias/company-clients/${editForm.client_id}`"
                                  selectFileStrategy="replace" imgWidth="100%" imgHeight="h-auto"
                                  :showSelectButton="false"/>
                        <div v-if="editForm.errors.media" class="text-danger">{{ editForm.errors.media }}</div>
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
                           <span class="form-check-description">When enabled, the client will be displayed in the system.</span>
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
                          @click.prevent="updateClient" :disabled="editForm.processing">
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
import Dropzone from "@components/global/Dropzone.vue";

export default {
   components: {
      Dropzone,
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
            client_id: '',
            media: [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }],
            active: true,
         }),
         mediaForm: useForm({
            client_id: '',
            file: null,
         }),
         selectedMedia: [],
      };
   },
   methods: {
      handleMediaUpload(fileObject) {
         if (Array.isArray(fileObject)) {
            this.form.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.form.media = fileObject.file ?? null
         }
      },
      handleMediaChange(event) {
         const file = event.target.files[0];
         if (file) {
            this.mediaForm.file = file;
         }
      },
      createClient() {
         this.form.post(route('admin.components.clients.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.clientsTable.reloadTable();
               this.$toast.success('Client Created Successfully', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editClient(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.active = rowData.active;
         this.editForm.client_id = rowData.id;
         const file = rowData.media?.find(md => md.collection_name === 'client-image') ?? null;
         if (file) {
            this.editForm.media = [{
               id: file.id,
               model_id: rowData.id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true, // flag so you know it’s already uploaded
            }];
         } else {
            this.editForm.media = [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }];
         }
         
         const modalElement = this.$refs.editClientModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateClient() {
         this.editForm.patch(route('admin.components.clients.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.clientsTable.reloadTable();
               const modalElement = this.$refs.editClientModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Client Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteClient(client) {
         this.$toast.question('Are you sure?', `Deleting ${client.name}`).then(() => {
            this.$inertia.delete(route('admin.components.clients.destroy', client.id), {
               onSuccess: () => {
                  this.$toast.success('Client deleted', 'Success');
                  this.$refs.clientsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the client!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.clientsTable.reloadTable();
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
   },
}
</script>

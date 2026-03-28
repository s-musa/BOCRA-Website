<template>
   <div class="row">
      <div class="col-lg-4 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header d-flex align-items-center pt-3">
                  <div class="card-title mb-0">
                     <h5 class="m-0">Add New Tag</h5>
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
                              <input v-model="form.active" class="form-check-input" type="checkbox">
                           </label>
                        </span>
                        <span class="form-check-description">When enabled, the tag can be used in projects, news and gallery.</span>
                     </label>
                  </div>
                  <div class="mb-3 mt-5 text-end">
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createTag" :disabled="form.processing">
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
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Tags"
                               @input="applyFilter" v-model="appendParams.filter.name">
                     </div>
                  </div>
               </div>
               
               <VueTable
                  :fields="fields"
                  api-url="datatable/tags"
                  :append-params="appendParams"
                  ref="tagsTable"
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
                           <a class="dropdown-item" href="#" @click.prevent="editTag(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deleteTag(props.rowData)">
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
      <div class="modal fade" id="edit-tag-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-tag-modal-label" aria-hidden="true" ref="editTagModal"
      >
         <div class="modal-dialog modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-tag-modal-label">Edit Tag</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updateTag">
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
                           <span class="form-check-description">When enabled, the tag can be used in projects, news and gallery.</span>
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
                          @click.prevent="updateTag" :disabled="editForm.processing">
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
            active: true,
         }),
         editForm: useForm({
            id: '',
            name: '',
            active: true,
         }),
      };
   },
   methods: {
      createTag() {
         this.form.post(route('admin.settings.tags.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.tagsTable.reloadTable();
               this.$toast.success('Tag Created Successfully', 'Success');
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editTag(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.active = rowData.active;
         const modalElement = this.$refs.editTagModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateTag() {
         this.editForm.patch(route('admin.settings.tags.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.tagsTable.reloadTable();
               const modalElement = this.$refs.editTagModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Tag Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteTag(tag) {
         this.$toast.question('Are you sure?', `Deleting ${tag.name}`).then(() => {
            this.$inertia.delete(route('admin.settings.tags.destroy', tag.id), {
               onSuccess: () => {
                  this.$toast.success('Tag deleted', 'Success');
                  this.$refs.tagssTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the tag!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.tagsTable.reloadTable();
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
   },
}
</script>

<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search..."
                      @input="applyFilter" v-model="appendParams.filter.title">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateSkillModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Skill
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateSkillModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/skills"
         :append-params="appendParams"
         ref="skillsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editSkill(props.rowData)">
                     <i class="bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteSkill(props.rowData)">
                     <i class="bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div
         class="modal fade"
         id="create-skill-modal"
         data-bs-backdrop="static"
         tabindex="-1"
         aria-labelledby="create-skill-modal-label"
         aria-hidden="true"
         ref="createSkillModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-skill-modal-label">Add</h5>
                  <button
                     type="button"
                     class="btn-close"
                     data-bs-dismiss="modal"
                     aria-label="Close"
                     @click="formCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="createSkill">
                     <div class="mb-3">
                        <label for="question" class="form-label-md mb-1">Title</label>
                        <input id="question" type="text" v-model="form.title" class="form-control">
                        <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                     </div>
                     
                     <div class="mb-3">
                        <label for="answer" class="form-label-md mb-1">Details</label>
                        <editor v-model="form.details" id="editor" class="editor-control" />
                        <div v-if="form.errors.details" class="text-danger">{{ form.errors.details }}</div>
                     </div>
                     
                     <div class="mb-3">
                        <label class="row d-flex">
                           <span class="col"><span class="fw-bold me-3">Active</span></span>
                           <span class="col-auto">
                                 <label class="form-check form-switch">
                                    <input v-model="form.active" class="form-check-input" type="checkbox">
                                 </label>
                              </span>
                           <span class="form-check-description">When enabled, it will be displayed in the system.</span>
                        </label>
                     </div>
                  </form>
               </div>
               <div class="modal-footer pt-5">
                  <button
                     type="button"
                     class="btn btn-secondary me-2"
                     data-bs-dismiss="modal"
                     @click="formCleanUp"
                  >
                     Close
                  </button>
                  <button
                     type="button"
                     class="btn btn-primary"
                     @click.prevent="createSkill"
                  >
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div
         class="modal fade"
         id="edit-skill-modal"
         data-bs-backdrop="static"
         tabindex="-1"
         aria-labelledby="edit-skill-modal-label"
         aria-hidden="true"
         ref="editSkillModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-skill-modal-label">Edit</h5>
                  <button
                     type="button"
                     class="btn-close"
                     data-bs-dismiss="modal"
                     aria-label="Close"
                     @click="formCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updateSkill">
                     <div class="mb-3">
                        <label for="question" class="form-label-md mb-1">Title</label>
                        <input id="question" type="text" v-model="form.title" class="form-control">
                        <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                     </div>
                     
                     <div class="mb-3">
                        <label for="answer" class="form-label-md mb-1">Details</label>
                        <editor v-model="form.details" id="editor" class="editor-control" />
                        <div v-if="form.errors.details" class="text-danger">{{ form.errors.details }}</div>
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
                           <span class="form-check-description">When enabled, it will be displayed in the system.</span>
                        </label>
                     </div>
                  </form>
               </div>
               <div class="modal-footer pt-5">
                  <button
                     type="button"
                     class="btn btn-secondary me-2"
                     data-bs-dismiss="modal"
                     @click="formCleanUp"
                  >
                     Close
                  </button>
                  <button
                     type="button"
                     class="btn btn-primary"
                     @click.prevent="updateSkill"
                  >
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
import Editor from "@components/global/Editor.vue";

export default {
   components: {Editor},
   data() {
      return {
         fields: [
            {
               name: 'title',
               title: 'TITLE',
            },
            {
               name: '__slot:status',
               title: 'STATUS',
            },
            {
               name: '__slot:actions',
               title: 'ACTIONS',
               titleClass: 'w-5',
               dataClass: 'w-5',
            },
         ],
         appendParams: {
            filter: {
               title: '',
            }
         },
         form: useForm({
            id: '',
            title: '',
            details: '',
            active: true,
         }),
      };
   },
   methods: {
      showCreateSkillModal() {
         const modalElement = this.$refs.createSkillModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createSkill() {
         this.form.post(route('admin.components.skills.store'), {
            onSuccess: () => {
               this.form.reset(); // Reset the form on success
               this.form.clearErrors();
               this.$refs.skillsTable.reloadTable();
               const modalElement = this.$refs.createSkillModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Skill Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editSkill(rowData) {
         this.form.id = rowData.hashid;
         this.form.title = rowData.title;
         this.form.details = rowData.details;
         this.form.active = rowData.active;
         this.$refs.skillsTable.reloadTable();
         
         const modalElement = this.$refs.editSkillModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateSkill() {
         this.form.patch(route('admin.components.skills.update', this.form.id), {
            onSuccess: () => {
               this.form.reset(); // Reset the form on success
               this.form.clearErrors();
               this.$refs.skillsTable.reloadTable();
               const modalElement = this.$refs.editSkillModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Skill Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteSkill(skill) {
         this.$toast.question('Are you sure?', `Deleting ${skill.question}`).then(() => {
            this.$inertia.delete(route('admin.components.skills.destroy', skill.hashid), {
               onSuccess: () => {
                  this.$toast.success('Skill deleted successfully!', 'Success');
                  this.$refs.skillsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the skill!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.skillsTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset()
      },
   },
}
</script>

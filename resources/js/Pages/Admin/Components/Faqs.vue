<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Faqs"
                      @input="applyFilter" v-model="appendParams.filter.question">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateFaqModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Faq
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateFaqModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/faqs"
         :append-params="appendParams"
         ref="faqsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editFaq(props.rowData)">
                     <i class="bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteFaq(props.rowData)">
                     <i class="bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div
         class="modal fade"
         id="create-faq-modal"
         data-bs-backdrop="static"
         tabindex="-1"
         aria-labelledby="create-faq-modal-label"
         aria-hidden="true"
         ref="createFaqModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-faq-modal-label">Add Faq</h5>
                  <button
                     type="button"
                     class="btn-close"
                     data-bs-dismiss="modal"
                     aria-label="Close"
                     @click="formCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="createFaq">
                     <div class="mb-3">
                        <label for="question" class="form-label-md mb-1">Question</label>
                        <input id="question" type="text" v-model="form.question" class="form-control">
                        <div v-if="form.errors.question" class="text-danger">{{ form.errors.question }}</div>
                     </div>
                     
                     <div class="mb-3">
                        <label for="answer" class="form-label-md mb-1">Answer</label>
                        <textarea id="answer" rows="5" v-model="form.answer" class="form-control"></textarea>
                        <div v-if="form.errors.answer" class="text-danger">{{ form.errors.answer }}</div>
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
                           <span class="form-check-description">When enabled, the faq will be displayed in the system.</span>
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
                     @click.prevent="createFaq"
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
         id="edit-faq-modal"
         data-bs-backdrop="static"
         tabindex="-1"
         aria-labelledby="edit-faq-modal-label"
         aria-hidden="true"
         ref="editFaqModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-faq-modal-label">Edit Faq</h5>
                  <button
                     type="button"
                     class="btn-close"
                     data-bs-dismiss="modal"
                     aria-label="Close"
                     @click="formCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updateFaq">
                     <div class="mb-3">
                        <label for="question" class="form-label-md mb-1">Question</label>
                        <input id="question" type="text" v-model="form.question" class="form-control">
                        <div v-if="form.errors.question" class="text-danger">{{ form.errors.question }}</div>
                     </div>
                     
                     <div class="mb-3">
                        <label for="answer" class="form-label-md mb-1">Answer</label>
                        <textarea id="answer" rows="5" v-model="form.answer" class="form-control"></textarea>
                        <div v-if="form.errors.answer" class="text-danger">{{ form.errors.answer }}</div>
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
                           <span class="form-check-description">When enabled, the faq will be displayed in the system.</span>
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
                     @click.prevent="updateFaq"
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
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/vue3";
import { Modal } from 'bootstrap';
import _debounce from "lodash/debounce.js";

export default {
   data() {
      return {
         fields: [
            {
               name: 'question',
               title: 'QUESTION',
               titleClass: 'w-25',
               dataClass: 'w-25',
            },
            {
               name: 'answer',
               title: 'ANSWER',
               titleClass: 'w-auto',
               dataClass: 'w-auto',
            },
            {
               name: '__slot:status',
               title: 'STATUS',
               titleClass: 'w-5',
               dataClass: 'w-5',
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
               question: '',
            }
         },
         form: useForm({
            id: '',
            question: '',
            answer: '',
            active: true,
         }),
      };
   },
   methods: {
      showCreateFaqModal() {
         const modalElement = this.$refs.createFaqModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createFaq() {
         this.form.post(route('admin.components.faqs.store'), {
            onSuccess: () => {
               this.form.reset(); // Reset the form on success
               this.form.clearErrors();
               this.$refs.faqsTable.reloadTable();
               const modalElement = this.$refs.createFaqModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Faq Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editFaq(rowData) {
         this.form.id = rowData.hashid;
         this.form.question = rowData.question;
         this.form.answer = rowData.answer;
         this.form.active = rowData.active;
         this.$refs.faqsTable.reloadTable();
         
         const modalElement = this.$refs.editFaqModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateFaq() {
         this.form.patch(route('admin.components.faqs.update', this.form.id), {
            onSuccess: () => {
               this.form.reset(); // Reset the form on success
               this.form.clearErrors();
               this.$refs.faqsTable.reloadTable();
               const modalElement = this.$refs.editFaqModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Faq Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteFaq(faq) {
         this.$toast.question('Are you sure?', `Deleting ${faq.question}`).then(() => {
            this.$inertia.delete(route('admin.components.faqs.destroy', faq.hashid), {
               onSuccess: () => {
                  this.$toast.success('FAQ deleted successfully!', 'Success');
                  this.$refs.faqsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the FAQ!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.faqsTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset()
      },
   },
}
</script>

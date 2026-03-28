<template>
   <div class="row">
      <div class="col-md-4 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header d-flex align-items-center pt-3">
                  <div class="card-title mb-0">
                     <h5 class="m-0">Add New Member</h5>
                  </div>
               </div>
               <div class="card-body">
                  <div class="mb-3">
                     <label for="title" class="form-label-md mb-1">Name</label>
                     <input id="title" type="text" v-model="form.name" class="form-control">
                     <div v-if="form.errors.name" class="text-danger">{{ form.errors.title }}</div>
                  </div>
                  <div class="mb-3">
                     <label for="position" class="form-label-md mb-1">Position</label>
                     <input id="position" type="text" v-model="form.position" class="form-control">
                     <div v-if="form.errors.position" class="text-danger">{{ form.errors.position }}</div>
                  </div>
                  <div class="mb-3">
                     <label for="details" class="form-label-md mb-1">Details</label>
                     <textarea v-model="form.details" id="details" class="form-control" rows="5"></textarea>
                     <div v-if="form.errors.details" class="text-danger">{{ form.errors.details }}</div>
                  </div>
                  <div class="mb-3">
                     <label for="memberMedia" class="form-label-md mb-1">Member Image</label>
                     <input id="memberMedia" @change="handleMediaUpload" type="file" accept="image/*" class="form-control">
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
                        <span class="form-check-description">When enabled, the board member will be displayed in the system.</span>
                     </label>
                  </div>
                  <div class="mb-3 mt-5 text-end">
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createBoardMember" :disabled="form.processing">
                        Submit
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-8 col-12">
         <div class="mb-3">
            <div class="card">
               <div class="card-header flex-column flex-md-row">
                  <div class="row row-gap-1">
                     <div class="col-md-5 col-9">
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Members"
                               @input="applyFilter" v-model="appendParams.filter.name">
                     </div>
                     <div class="col-md-6 col-3 ms-auto">
                        <div class="flex-wrap text-end">
                           <div class="card-action">
                              <button type="button" class="btn btn-primary d-none d-sm-inline-block" @click="sortBoardMembers">
                                 <i class="bx bx-sort me-2"></i>
                                 Sort Members
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               
               <VueTable :fields="fields" api-url="datatable/board-members" :append-params="appendParams"
                         ref="boardMembersTable">
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
                           <a class="dropdown-item" href="#" @click.prevent="editBoardMember(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item" href="#" @click.prevent="openBoardMemberMediaModal(props.rowData)">
                              <i class="bx bx-image-add me-2"></i>Change Image
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deleteBoardMember(props.rowData)">
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
      <div class="modal fade" id="edit-board-member-modal" data-bs-backdrop="static" tabindex="-1"
         aria-labelledby="edit-board-member-modal-label" aria-hidden="true" ref="editBoardMemberModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-board-member-modal-label">Edit Board Member</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                     @click="editFormCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="updateBoardMember">
                     <div class="mb-3">
                        <label for="title" class="form-label-md">Name</label>
                        <input id="title" type="text" v-model="editForm.name" class="form-control">
                        <div v-if="editForm.errors.name" class="text-danger">{{ form.errors.name }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="position" class="form-label-md">Position</label>
                        <input id="position" type="text" v-model="editForm.position" class="form-control">
                        <div v-if="editForm.errors.position" class="text-danger">{{ editForm.errors.position }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="details" class="form-label-md">Details</label>
                        <textarea id="details" v-model="editForm.details" class="form-control" rows="5"></textarea>
                        <div v-if="editForm.errors.details" class="text-danger">{{ editForm.errors.details }}</div>
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
                           <span class="form-check-description">When enabled, the board member will be displayed in the system.</span>
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
                          @click.prevent="updateBoardMember" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Board Media Upload Modal -->
      <div class="modal fade" id="board-member-media-upload-modal" data-bs-backdrop="static" tabindex="-1"
         aria-labelledby="board-member-media-upload-modal-label" aria-hidden="true" ref="boardMemberMediaUploadModal"
      >
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
                           No Image found for this board member
                        </p>
                     </div>
                     <div class="mb-3">
                        <label for="title" class="form-label-md">New Image</label>
                        <input @change="handleMediaChange" id="boardMemberImage" type="file" accept="image/*"
                           required class="form-control mb-3"
                        />
                        <div v-if="mediaForm.errors.file" class="text-danger">{{ mediaForm.errors.file }}</div>
                     </div>
                     <button type="button" class="btn btn-success" @click.prevent="uploadMedia"
                             :disabled="mediaForm.processing">
                        Upload Image
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Board Media Sorting Modal -->
      <div class="modal fade" id="board-member-sorting-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="board-member-sorting-modal-label" aria-hidden="true" ref="boardMemberSortingModal"
      >
         <div class="modal-dialog  modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="board-member-sorting-label">Sort Board Members</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="memberSortCleanUp"
                  ></button>
               </div>
               
               <div class="modal-body">
                  <form id="sortingForm" @submit.prevent="updateBoardMemberSorts">
                     <draggable
                        :list="boardMembers"
                        class="drag-area"
                        @change="handleDraggableChange"
                     >
                        <div v-for="(item, index) in boardMembers" class="card bg-muted border-0 mb-3">
                           <div class="card-body p-3">
                              <div class="d-flex">
                                    <span class="me-5 drag-button">
                                       <i class="bx bx-move"></i>
                                    </span>
                                 <span class="fw-normal"><span>{{ index + 1 }}.</span> {{ item.name }}</span>
                              </div>
                           </div>
                        </div>
                     </draggable>
                  </form>
               </div>
               
               <div class="modal-footer pt-5 me-auto">
                  <button type="button" class="btn btn-primary"
                          @click.prevent="updateBoardMemberSorts">
                     Update
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
import Editor from "@components/global/Editor.vue";
import axios from "axios";

export default {
   components: {Editor},
   data() {
      return {
         fields: [
            {
               name: 'name',
               title: 'NAME',
            },
            {
               name: 'position',
               title: 'POSITION',
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
            name: '',
            position: '',
            details: null,
            active: true,
            media: null,
         }),
         editForm: useForm({
            id: '',
            name: '',
            position: '',
            details: null,
            active: true,
         }),
         mediaForm: useForm({
            board_member_id: '',
            file: null,
         }),
         selectedMedia: [],
         boardMembers: [],
         sortedMembersIds: [],
      };
   },
   created() {
      this.fetchBoardMembers();
   },
   methods: {
      fetchBoardMembers() {
         axios.get(route('datatable.board-members'))
            .then(({data}) => {
               this.boardMembers = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the board-members!", "Error");
         })
      },
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
      createBoardMember() {
         this.form.post(route('admin.components.board-members.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.boardMembersTable.reloadTable();
               this.$toast.success('Board Member Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editBoardMember(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.name = rowData.name;
         this.editForm.position = rowData.position;
         this.editForm.details = rowData.details;
         this.editForm.active = rowData.active;
         this.$refs.boardMembersTable.reloadTable();
         
         const modalElement = this.$refs.editBoardMemberModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateBoardMember() {
         this.editForm.patch(route('admin.components.board-members.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.$refs.boardMembersTable.reloadTable();
               const modalElement = this.$refs.editBoardMemberModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Board Member Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteBoardMember(boardMember) {
         this.$toast.question('Are you sure?', `Deleting ${boardMember.name}`).then(() => {
            this.$inertia.delete(route('admin.components.board-members.destroy', boardMember.hashid), {
               onSuccess: () => {
                  this.$toast.success('Board member deleted successfully!', 'Success');
                  this.$refs.boardMembersTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the board member!', 'Error');
               }
            })
         })
      },
      openBoardMemberMediaModal(member) {
         const image = member.media.find(m => m.collection_name === 'board-image')
         if (image) {
            this.selectedMedia = image;
         }
         this.mediaForm.board_member_id = member.id;
         const modalElement = this.$refs.boardMemberMediaUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      uploadMedia() {
         this.mediaForm.post(route('admin.medias.upload.board-member-image'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            onSuccess: () => {
               this.mediaForm.reset();
               this.mediaForm.clearErrors();
               this.$refs.boardMembersTable.reloadTable();
               this.$toast.success('Image changed', 'Updated');
               const modalElement = this.$refs.boardMemberMediaUploadModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      sortBoardMembers() {
         const modalElement = this.$refs.boardMemberSortingModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      handleDraggableChange: _debounce(function(event) {
         if (event.moved) {
            // console.log('Moved item:', event.moved.element);
            const orderedIds = this.boardMembers.map((member, index) => ({
               id: member.id,
               order: index + 1
            }));
            this.sortedMembersIds.push(orderedIds)
         }
      }, 500),
      updateBoardMemberSorts() {
         const members = this.sortedMembersIds;
         
         if(!members.length) {
            this.$toast.info('Board Members Sortable List is empty');
            return;
         }
         this.$inertia.post(route('admin.pages.update-member-order'), { members: members }, {
            onSuccess: () => {
               this.fetchBoardMembers();
               const modalElement = this.$refs.boardMemberSortingModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.sortedMembersIds = [];
               
               setTimeout(() => {
                  this.$toast.success('Members order updated successfully', 'Success');
                  this.$refs.boardMembersTable.reloadTable();
               }, 300)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Something went wrong', 'Error');
            }
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.boardMembersTable.reloadTable();
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
      mediaModalCleanUp() {
         this.mediaForm.reset();
         this.mediaForm.clearErrors();
         this.mediaForm.file = null;
         this.selectedMedia = [];
      },
      memberSortCleanUp() {
         this.sortedMembersIds = [];
      }
   }
}
</script>

<style scoped>
.drag-button, .drag-button:hover {
   cursor: grab !important;
}
</style>

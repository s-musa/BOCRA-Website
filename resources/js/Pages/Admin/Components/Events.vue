<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Events"
                      @input="applyFilter" v-model="appendParams.filter.title">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateEventModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Event
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateEventModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/events"
         :append-params="appendParams"
         ref="eventsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editEvent(props.rowData)">
                     <i class="fs-5 bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openEventGalleryModal(props.rowData)">
                     <i class="fs-5 bx bx-image-add me-2"></i>Event Gallery
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteEvent(props.rowData)">
                     <i class="fs-5 bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Create Modal -->
      <div class="modal fade" id="create-event-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-event-modal-label" aria-hidden="true" ref="createEventModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-event-modal-label">Add Event</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="createEvent">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="form.title" class="form-control">
                           <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label class="form-label" for="date">Date</label>
                           <date-picker
                              id="date" form-class="form-control" :value="form.date" :max-date="new Date()"
                              @on-change="function(dateObj, dateStr) {
                                 form.date = dateStr
                              }"
                           ></date-picker>
                           <div v-if="form.errors.date" class="text-danger">{{ form.errors.date }}</div>
                        </div>
                     </div>
                     <div class="col-md-8 col-12">
                        <div class="mb-3">
                           <label for="eventTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="eventTags" multiple v-model="form.tags" :options="availableTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.tags" class="text-danger">{{ form.errors.tags }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="categoryId" class="form-label-md mb-1">Category</label>
                           <v-select id="categoryId" v-model="form.category_id" :options="categories"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.category_id" class="text-danger">{{ form.errors.category_id }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="hostedBy" class="form-label-md mb-1">Hosted By</label>
                           <input id="hostedBy" type="text" v-model="form.hosted_by" class="form-control">
                           <div v-if="form.errors.hosted_by" class="text-danger">{{ form.errors.hosted_by }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="location" class="form-label-md mb-1">Location</label>
                           <input id="location" type="text" v-model="form.location" class="form-control">
                           <div v-if="form.errors.location" class="text-danger">{{ form.errors.location }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="eventMedia" class="form-label-md mb-1">Event Image</label>
                           <Dropzone @files-added="handleMediaUpload" :multiple="false"
                                     selectFileStrategy="replace"/>
                           <div v-if="form.errors.media" class="text-danger">{{ form.errors.media }}</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="details" class="form-label-md mb-1">Details</label>
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
                           <span class="form-check-description">When enabled, the event will be displayed in the system.</span>
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
                          @click.prevent="createEvent" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-event-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-event-modal-label" aria-hidden="true" ref="editEventModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="edit-event-modal-label">Edit Event</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="editFormCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="updateEvent">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="editForm.title" class="form-control">
                           <div v-if="editForm.errors.title" class="text-danger">{{ editForm.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label class="form-label" for="date">Date</label>
                           <date-picker
                              id="date" form-class="form-control" :value="editForm.date" :max-date="new Date()"
                              @on-change="function(dateObj, dateStr) {
                                 editForm.date = dateStr
                              }"
                           ></date-picker>
                           <div v-if="editForm.errors.date" class="text-danger">{{ editForm.errors.date }}</div>
                        </div>
                     </div>
                     <div class="col-md-8 col-12">
                        <div class="mb-3">
                           <label for="eventTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="eventTags" multiple v-model="editForm.tags" :options="availableTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.tags" class="text-danger">{{ editForm.errors.tags }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="categoryId" class="form-label-md mb-1">Category</label>
                           <v-select id="categoryId" v-model="editForm.category_id" :options="categories"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.category_id" class="text-danger">{{ editForm.errors.category_id }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="hostedBy" class="form-label-md mb-1">Hosted By</label>
                           <input id="hostedBy" type="text" v-model="editForm.hosted_by" class="form-control">
                           <div v-if="editForm.errors.hosted_by" class="text-danger">{{ editForm.errors.hosted_by }}</div>
                        </div>
                     </div>
                     <div class="col-md-4 col-12">
                        <div class="mb-3">
                           <label for="location" class="form-label-md mb-1">Location</label>
                           <input id="location" type="text" v-model="editForm.location" class="form-control">
                           <div v-if="editForm.errors.location" class="text-danger">{{ editForm.errors.location }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="eventMedia" class="form-label-md mb-1">Event Image</label>
                           <Dropzone v-model="editForm.media" :multiple="false"
                                     :existing="editForm.media ? editForm.media : []"
                                     :upload-url="`/wp-admin/medias/events/${editForm.event_id}`"
                                     :delete-url="`/wp-admin/media/events/${editForm.event_id}/delete`"
                                     selectFileStrategy="replace"
                                     :showSelectButton="false"/>
                           <div v-if="editForm.errors.media" class="text-danger">{{ editForm.errors.media }}</div>
                        </div>
                     </div>
                     <div class="mb-3">
                        <label for="details" class="form-label-md mb-1">Details</label>
                        <editor v-model="editForm.details" id="editor" class="editor-control" />
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
                           <span class="form-check-description">When enabled, the event will be displayed in the system.</span>
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
                          @click.prevent="updateEvent" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Event Gallery Upload Modal -->
      <div class="modal fade" id="event-gallery-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="event-gallery-upload-modal-label" aria-hidden="true" ref="eventGalleryUploadModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="logo-upload-modal-label">Upload Gallery</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Gallery</label>
                           <Dropzone v-model="galleryForm.file" :multiple="true" :existing="galleryForm.files ? galleryForm.files : []"
                                     :showSelectButton="false"
                                     previewPosition="outside"
                                     selectFileStrategy="merge"
                                     :upload-url="`/wp-admin/medias/events/${galleryForm.event_id}/gallery`"
                                     :delete-url="`/wp-admin/medias/:id`"
                           />
                           <div v-if="galleryForm.errors.file" class="text-danger">{{ galleryForm.errors.file }}</div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import { Modal } from 'bootstrap';
import _debounce from "lodash/debounce.js";
import Editor from '@/Components/global/Editor.vue'

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
               name: 'user.name',
               title: 'PREPARED BY',
            },
            {
               name: 'date',
               title: 'DATE',
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
               title: '',
            }
         },
         form: useForm({
            id: null,
            title: null,
            date: null,
            category_id: null,
            hosted_by: null,
            location: null,
            map_url: null,
            details: null,
            media: null,
            tags: [],
            active: true,
         }),
         editForm: useForm({
            id: null,
            event_id: null,
            title: null,
            date: null,
            category_id: null,
            hosted_by: null,
            location: null,
            map_url: null,
            details: null,
            media: null,
            tags: [],
            active: true,
         }),
         galleryForm: useForm({
            event_id: '',
            files: [],
            file: null,
         }),
         availableTags: [],
         categories: [],
      };
   },
   mounted() {
      this.fetchTags();
      this.fetchCategories();
   },
   methods: {
      fetchTags() {
         axios.get(route('data.tags'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         }).then(({data}) => {
            this.availableTags = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the tags!", "Error");
         })
      },
      fetchCategories() {
         axios.get(route('datatable.categories'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         }).then(({data}) => {
            this.categories = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the categirues!", "Error");
         })
      },
      handleMediaUpload(fileObject) {
         if (Array.isArray(fileObject)) {
            this.form.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.form.media = fileObject.file ?? null
         }
      },
      showCreateEventModal() {
         const modalElement = this.$refs.createEventModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createEvent() {
         this.form.post(route('admin.components.events.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.eventsTable.reloadTable();
               const modalElement = this.$refs.createEventModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Event Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editEvent(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.event_id = rowData.id;
         this.editForm.title = rowData.title;
         this.editForm.date = rowData.date;
         this.editForm.category_id = rowData.category_id;
         this.editForm.hosted_by = rowData.hosted_by;
         this.editForm.location = rowData.location;
         this.editForm.map_url = rowData.map_url;
         this.editForm.details = rowData.details;
         this.editForm.active = rowData.active;
         this.editForm.tags =rowData.tags?.map(tag => tag.id) ?? [];
         
         const file = rowData.media?.find(md => md.collection_name === 'event-image') ?? null;
         if (file) {
            this.editForm.media = [{
               id: file.model_id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true, // flag so you know it’s already uploaded
            }];
         } else {
            this.editForm.media = null;
         }
         
         const modalElement = this.$refs.editEventModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateEvent() {
         this.editForm.patch(route('admin.components.events.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.eventsTable.reloadTable();
               const modalElement = this.$refs.editEventModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Event Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openEventGalleryModal(event) {
         this.galleryForm.event_id = event.id;
         const images = event.media.filter(m => m.collection_name === 'event-gallery')
         if (images.length > 0) {
            this.galleryForm.files = images.map(img => ({
               id: img.id,
               model_id: img.model_id,
               name: img.file_name,
               size: img.size,
               url: img.original_url, // or full_url
               type: img.mime_type,
               isExisting: true,
            }))
         } else {
            this.galleryForm.files = []
         }
         
         const modalElement = this.$refs.eventGalleryUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      deleteEvent(event) {
         this.$toast.question('Are you sure?', `Deleting ${event.title}`).then(() => {
            this.$inertia.delete(route('admin.components.events.destroy', event.id), {
               onSuccess: () => {
                  this.$toast.success('Event deleted', 'Success');
                  this.$refs.eventsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the event!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.eventsTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
         this.$refs.eventsTable.reloadTable();
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
         this.editForm.media = null;
         this.$refs.eventsTable.reloadTable();
      },
   }
}
</script>

<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Blogs"
                      @input="applyFilter" v-model="appendParams.filter.title">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateBlogModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Blog
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateBlogModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/blogs"
         :append-params="appendParams"
         ref="blogsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editBlog(props.rowData)">
                     <i class="fs-5 bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openBlogGalleryModal(props.rowData)">
                     <i class="fs-5 bx bx-image-add me-2"></i>Blog Gallery
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteBlog(props.rowData)">
                     <i class="fs-5 bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div class="modal fade" id="create-blog-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-blog-modal-label" aria-hidden="true" ref="createBlogModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-blog-modal-label">Add Blog</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="createBlog">
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
                           <label for="blogTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="blogTags" multiple v-model="form.tags" :options="availableTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.tags" class="text-danger">{{ form.errors.tags }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="blogMedia" class="form-label-md mb-1">Blog Image</label>
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
                           <span class="form-check-description">When enabled, the blog will be displayed in the system.</span>
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
                          @click.prevent="createBlog" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-blog-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-blog-modal-label" aria-hidden="true" ref="editBlogModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="edit-blog-modal-label">Edit Blog</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="editFormCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="updateBlog">
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
                           <label for="blogTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="blogTags" multiple v-model="editForm.tags" :options="availableTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.tags" class="text-danger">{{ editForm.errors.tags }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="blogMedia" class="form-label-md mb-1">Blog Image</label>
                           <Dropzone v-model="editForm.media" :multiple="false"
                                     :existing="editForm.media ? editForm.media : []"
                                     :upload-url="`/wp-admin/medias/blogs/${editForm.blog_id}`"
                                     :delete-url="`/wp-admin/media/blogs/${editForm.blog_id}/delete`"
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
                           <span class="form-check-description">When enabled, the blog will be displayed in the system.</span>
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
                          @click.prevent="updateBlog" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Blog Gallery Upload Modal -->
      <div class="modal fade" id="blog-gallery-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="blog-gallery-upload-modal-label" aria-hidden="true" ref="blogGalleryUploadModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="logo-upload-modal-label">Upload Gallery</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                     upload-url="/wp-admin/medias/gallery/upload"
                                     :headers="{
                                        'X-Owner-Type': galleryForm.owner_type,
                                        'X-Owner-Id': galleryForm.owner_id,
                                        'X-Collection': galleryForm.collection,
                                      }"
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
   components: {
      Editor,
   },
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
            details: null,
            media: null,
            tags: [],
            active: true,
         }),
         editForm: useForm({
            id: null,
            blog_id: null,
            title: null,
            date: null,
            details: null,
            media: null,
            tags: [],
            active: true,
         }),
         availableTags: [],
         galleryForm: useForm({
            owner_type: 'App\\Models\\Blog',
            owner_id: null,
            collection: 'blog-gallery',
            file: null,
            files: [],
         }),
      };
   },
   mounted() {
      this.fetchTags();
   },
   methods: {
      fetchTags() {
         axios.get(route('data.tags'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         })
            .then(({data}) => {
               this.availableTags = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the tags!", "Error");
         })
      },
      handleMediaUpload(fileObject) {
         if (Array.isArray(fileObject)) {
            this.form.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.form.media = fileObject.file ?? null
         }
      },
      showCreateBlogModal() {
         const modalElement = this.$refs.createBlogModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createBlog() {
         this.form.post(route('admin.components.blogs.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.blogsTable.reloadTable();
               const modalElement = this.$refs.createBlogModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Blog Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editBlog(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.blog_id = rowData.id;
         this.editForm.title = rowData.title;
         this.editForm.date = rowData.date;
         this.editForm.details = rowData.details;
         this.editForm.active = rowData.active;
         this.editForm.tags =rowData.tags?.map(tag => tag.id) ?? [];
         
         const file = rowData.media?.find(md => md.collection_name === 'blog-image') ?? null;
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
         
         const modalElement = this.$refs.editBlogModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateBlog() {
         this.editForm.patch(route('admin.components.blogs.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.blogsTable.reloadTable();
               const modalElement = this.$refs.editBlogModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Blog Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openBlogGalleryModal(blog) {
         this.galleryForm.owner_id = blog.id;
         const images = blog.media.filter(m => m.collection_name === 'blog-gallery')
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
         
         const modalElement = this.$refs.blogGalleryUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      deleteBlog(blog) {
         this.$toast.question('Are you sure?', `Deleting ${blog.title}`).then(() => {
            this.$inertia.delete(route('admin.components.blogs.destroy', blog.id), {
               onSuccess: () => {
                  this.$toast.success('Blog deleted', 'Success');
                  this.$refs.blogsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the blog!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.blogsTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
         this.$refs.blogsTable.reloadTable();
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
         this.editForm.media = null;
         this.$refs.blogsTable.reloadTable();
      },
   }
}
</script>

<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Projects"
                      @input="applyFilter" v-model="appendParams.filter.title">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateProjectModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Project
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateProjectModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/projects"
         :append-params="appendParams"
         ref="projectsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editProject(props.rowData)">
                     <i class="fs-5 bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openProjectGalleryModal(props.rowData)">
                     <i class="fs-5 bx bx-image-add me-2"></i>Project Gallery
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="manageProjectSEO(props.rowData)">
                     <i class="bx bx-search-alt me-2 fs-5"></i> Project SEO
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteProject(props.rowData)">
                     <i class="fs-5 bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div class="modal fade" id="create-project-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-project-modal-label" aria-hidden="true" ref="createProjectModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-project-modal-label">Add Project</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="createProject">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="form.title" class="form-control">
                           <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-5 col-12">
                        <div class="mb-3">
                           <label for="category" class="form-label-md mb-1">Category</label>
                           <v-select id="category" v-model="form.category_id" :options="categories"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.category_id" class="text-danger">{{ form.errors.category_id }}</div>
                        </div>
                     </div>
                     <div class="col-md-7 col-12">
                        <div class="mb-3">
                           <label for="projectTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="projectTags" multiple v-model="form.project_tags" :options="fetchedTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.project_tags" class="text-danger">{{ form.errors.project_tags }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="shortDescription" class="form-label-md mb-1">Short Project Description</label>
                           <div class="form-text small text-muted mb-2">This will be displayed on cards and carousels. A brief description of what the project is about.</div>
                           <textarea v-model="form.short_description" id="shortDetails" class="form-control" rows="8" />
                           <div v-if="form.errors.short_description" class="text-danger">{{ form.errors.short_description }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="projectMedia" class="form-label-md mb-1">Project Image</label>
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
                           <span class="form-check-description">When enabled, the project will be displayed in the system.</span>
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
                          @click.prevent="createProject" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-project-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-project-modal-label" aria-hidden="true" ref="editProjectModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-project-modal-label">Edit Project</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="editFormCleanUp" :disabled="editForm.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="editForm" class="row" @submit.prevent="updateProject">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="editForm.title" class="form-control">
                           <div v-if="editForm.errors.title" class="text-danger">{{ editForm.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="slug" class="form-label-md mb-1">Project Slug</label>
                           <input id="slug" type="text" v-model="editForm.slug" class="form-control">
                           <div v-if="editForm.errors.slug" class="text-danger">{{ editForm.errors.slug }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="category" class="form-label-md mb-1">Category</label>
                           <v-select id="category" v-model="editForm.category_id" :options="categories"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.category_id" class="text-danger">{{ editForm.errors.category_id }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="projectTags" class="form-label-md mb-1">Tags</label>
                           <v-select id="projectTags" multiple v-model="editForm.project_tags" :options="fetchedTags"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.project_tags" class="text-danger">{{ editForm.errors.project_tags }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="shortDescription" class="form-label-md mb-1">Short Project Description</label>
                           <div class="form-text small text-muted mb-2">This will be displayed on cards and carousels. A brief description of what the project is about.</div>
                           <textarea v-model="editForm.short_description" id="shortDetails" class="form-control" rows="8" />
                           <div v-if="editForm.errors.short_description" class="text-danger">{{ editForm.errors.short_description }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="projectMedia" class="form-label-md mb-1">Project Image</label>
                           <Dropzone v-model="editForm.media" :multiple="false"
                                     :existing="editForm.media ? editForm.media : []"
                                     :upload-url="`/wp-admin/medias/projects/${editForm.project_id}`"
                                     :delete-url="`/wp-admin/media/projects/${editForm.project_id}/delete`"
                                     selectFileStrategy="replace"
                                     :showSelectButton="false"/>
                           <div v-if="editForm.errors.media" class="text-danger">{{ editForm.errors.media }}</div>
                        </div>
                     </div>
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="details" class="form-label-md mb-1">Details</label>
                           <editor v-model="editForm.details" id="editor" class="editor-control" />
                           <div v-if="editForm.errors.details" class="text-danger">{{ editForm.errors.details }}</div>
                        </div>
                     </div>
                     <div class="col-12">
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
                              <span class="form-check-description">When enabled, the project will be displayed on the website.</span>
                           </label>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                          @click="editFormCleanUp" :disabled="editForm.processing">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary"
                          @click.prevent="updateProject" :disabled="editForm.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Project Gallery Upload Modal -->
      <div class="modal fade" id="project-gallery-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="project-gallery-upload-modal-label" aria-hidden="true" ref="projectGalleryUploadModal"
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
                                     :upload-url="`/wp-admin/medias/projects/${galleryForm.project_id}/gallery`"
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
      
      <!-- New Project SEO Modal -->
      <div class="modal fade" id="create-project-seo-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-project-seo-modal-label" aria-hidden="true" ref="createProjectSeoModal"
      >
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="create-project-seo-modal-label">Manage SEO</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click.prevent="projectSeoFormCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="storeProjectSeo">
                     <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" type="text" v-model="projectSeoForm.title" class="form-control">
                        <div v-if="projectSeoForm.errors.title" class="text-danger">{{ projectSeoForm.errors.title }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="description" class="form-label-md mb-2">Description</label>
                        <textarea id="description" rows="3" v-model="projectSeoForm.description" class="form-control"></textarea>
                        <div v-if="projectSeoForm.errors.description" class="text-danger">{{ projectSeoForm.errors.description }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="keywords" class="form-label-md mb-1">Keywords</label>
                        <div class="mb-2 small text-muted">Each keyword should be separated with a commas</div>
                        <input id="keywords" type="text" v-model="projectSeoForm.keywords" class="form-control" placeholder="e.g: website, school">
                        <div v-if="projectSeoForm.errors.keywords" class="text-danger">{{ projectSeoForm.errors.keywords }}</div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="projectSeoFormCleanUp"
                          :disabled="projectSeoForm.processing">
                     Close
                  </button>
                  
                  <button type="button" class="btn btn-primary" @click.prevent="storeProjectSeo" :disabled="projectSeoForm.processing">
                     {{ projectSeoForm.processing ? 'Processing...' : 'Submit' }}
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
               title: '',
            }
         },
         form: useForm({
            id: null,
            title: null,
            category_id: null,
            details: null,
            short_description: null,
            media: null,
            active: true,
            project_tags: [],
         }),
         editForm: useForm({
            id: null,
            project_id: null,
            title: null,
            slug: null,
            category_id: null,
            details: null,
            short_description: null,
            media: null,
            active: true,
            project_tags: [],
         }),
         galleryForm: useForm({
            owner_type: 'App\\Models\\Project',
            owner_id: null,
            project_id: null,
            collection: 'project-gallery',
            file: null,
            files: [],
         }),
         projectSeoForm: useForm({
            project_id: '',
            title: null,
            description: null,
            keywords: null,
         }),
         projectGallery: [],
         categories: [],
         fetchedTags: [],
      };
   },
   mounted() {
      this.fetchCategories();
      this.fetchTags();
   },
   methods: {
      fetchCategories() {
         axios.get(route('datatable.categories'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         })
         .then(({data}) => {
            this.categories = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the categories!", "Error");
         })
      },
      fetchTags() {
         axios.get(route('data.tags'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         })
         .then(({data}) => {
            this.fetchedTags = data.data;
         }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the tags!", "Error");
         })
      },
      handleMediaUpload(fileObject) {
         // If single file
         if (Array.isArray(fileObject)) {
            this.form.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.form.media = fileObject.file ?? null
         }
      },
      // handleMediaChange(event) {
      //    const file = event.target.files[0];
      //    if (file) {
      //       this.mediaForm.file = file;
      //    }
      // },
      showCreateProjectModal() {
         const modalElement = this.$refs.createProjectModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createProject() {
         this.form.post(route('admin.components.projects.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.projectsTable.reloadTable();
               const modalElement = this.$refs.createProjectModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Project Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editProject(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.project_id = rowData.id;
         this.editForm.title = rowData.title;
         this.editForm.slug = rowData.slug;
         this.editForm.category_id = rowData.category_id;
         this.editForm.details = rowData.details;
         this.editForm.short_description = rowData.short_description;
         this.editForm.active = rowData.active;
         this.editForm.project_tags =rowData.tags?.map(tag => tag.id) ?? [];
         
         const file = rowData.media?.find(md => md.collection_name === 'project-image') ?? null;
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
         
         const modalElement = this.$refs.editProjectModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateProject() {
         this.editForm.patch(route('admin.components.projects.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.editForm.media = null;
               this.$refs.projectsTable.reloadTable();
               const modalElement = this.$refs.editProjectModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Project Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openProjectGalleryModal(project) {
         this.galleryForm.morph_id = project.id;
         this.galleryForm.project_id = project.id;
         const images = project.media.filter(m => m.collection_name === 'project-gallery')
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
         
         const modalElement = this.$refs.projectGalleryUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      manageProjectSEO(rowData) {
         
         if(rowData && rowData.meta_seo) {
            this.projectSeoForm.title = rowData.meta_seo.title;
            this.projectSeoForm.description = rowData.meta_seo.description;
            this.projectSeoForm.keywords = rowData.meta_seo.keywords;
         } else {
            this.projectSeoForm.reset();
         }
         
         this.projectSeoForm.project_id = rowData.hashid;
         
         const modalElement = this.$refs.createProjectSeoModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storeProjectSeo() {
         this.projectSeoForm.post(route('admin.projects.meta-seo', this.projectSeoForm.project_id), {
            onSuccess: () => {
               this.projectSeoForm.reset();
               this.projectSeoForm.clearErrors();
               
               this.$refs.projectsTable.reloadTable();
               
               const modalElement = this.$refs.createProjectSeoModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               
               this.$toast.success('SEO configured successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      deleteProject(project) {
         this.$toast.question('Are you sure?', `Deleting ${project.title}`).then(() => {
            this.$inertia.delete(route('admin.components.projects.destroy', project.id), {
               onSuccess: () => {
                  this.$toast.success('Project deleted', 'Success');
                  this.$refs.projectsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the project!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.projectsTable.reloadTable();
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

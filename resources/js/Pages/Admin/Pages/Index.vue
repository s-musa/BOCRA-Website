<template>
   <Head title="Website Pages"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">Website Pages</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Pages
               </li>
            </ol>
         </nav>
         
         <div class="col-xxl-12">
            <div class="card">
               <div class="card-header flex-column flex-md-row">
                  <div class="row row-gap-1">
                     <div class="col-md-3 col-9">
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2"
                               placeholder="Search Pages"
                               @input="applyFilter" v-model="appendParams.filter.title">
                     </div>
                     <div class="col-md-6 col-3 ms-auto">
                        <div class="flex-wrap text-end">
                           <div class="card-action">
                              <button type="button" class="btn btn-primary d-none d-sm-inline-block" @click="createPage">
                                 <i class="bx bx-plus-circle me-2"></i>
                                 Add Page
                              </button>
                              
                              <button type="button" class="btn btn-primary btn-icon d-sm-none" @click="createPage">
                                 <i class="bx bx-plus"></i>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <VueTable
                  :fields="fields"
                  api-url="datatable/pages"
                  :append-params="appendParams"
                  ref="pagesTable"
               >
                  <template #title="props">
                     <div>{{ props.rowData.title }}</div>
                     <small v-if="props.rowData.type.name !== 'CMS'" class="text-muted">
                        Type: <span class="text-primary">{{ props.rowData.type.name}}</span>
                     </small>
                  </template>
                  <template #status="props">
                     <span v-if="props.rowData.published" class="badge bg-success">
                        Published
                     </span>
                     <span v-else-if="!props.rowData.published" class="badge bg-danger">
                        Draft
                     </span>
                     <span v-else class="badge bg-secondary">
                        Unknown
                     </span>
                  </template>
                  
                  <template #actions="props">
                     <div class="dropdown">
                        <button class="btn align-text-top py-1" data-bs-toggle="dropdown">
                           <i class="bx bx-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                           <a class="dropdown-item" href="#" @click.prevent="editPage(props.rowData)">
                              <i class="bx bx-edit-alt me-2 fs-5"></i> Edit
                           </a>
                           <Link v-if="props.rowData.type.name === 'CMS' || props.rowData.type.name === 'Landing Page'" class="dropdown-item"
                                 :href="'/wp-admin/pages/' + props.rowData.hashid + '/manage-sections'">
                              <i class="bx bx-detail me-2 fs-5"></i> Page Sections
                           </Link>
                           <a class="dropdown-item" href="#" @click.prevent="managePageSEO(props.rowData)">
                              <i class="bx bx-search-alt me-2 fs-5"></i> Page SEO
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deletePage(props.rowData)"
                              :class="{disabled : props.rowData.type.name !== 'CMS'}">
                              <i class="bx bx-trash me-2 fs-5"></i> Delete
                           </a>
                        </div>
                     </div>
                  </template>
               </VueTable>
            </div>
         </div>
         
         <!-- Create Modal -->
         <div class="modal fade" id="create-page-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="create-page-modal-label" aria-hidden="true" ref="createPageModal"
         >
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="create-page-modal-label">Add Page</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click.prevent="formCleanUp"
                             :disabled="form.processing">
                     </button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" class="row" @submit.prevent="storePage">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="title" class="form-label-md mb-1">Title</label>
                              <input id="title" type="text" v-model="form.title" class="form-control">
                              <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageType" class="form-label-md mb-1">Page Type</label>
                              <v-select id="pageType" v-model="form.page_type_id" :options="pageTypes"
                                        label="name" :reduce="option => option.id"
                              />
                              <div v-if="form.errors.page_type_id" class="text-danger">{{ form.errors.page_type_id }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="parentId" class="form-label-md mb-1">Parent Page</label>
                              <v-select id="parentId" v-model="form.parent_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                              <div v-if="form.errors.parent_id" class="text-danger">{{ form.errors.parent_id }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12 d-md-block d-sm-none"></div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <div class="row">
                                 <div class="col-12">
                                    <div class="mb-3">
                                       <label for="pageBannerStyle" class="form-label-md mb-1">Page Banner Style</label>
                                       <v-select id="pageBannerStyle" v-model="form.banner_style" :options="pageBannerStyles"
                                                 label="name" :reduce="option => option.value"
                                       >
                                          <template #option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-success">{{ description }}</small>
                                             </div>
                                          </template>
                                          <template #selected-option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-success">{{ description }}</small>
                                             </div>
                                          </template>
                                       </v-select>
                                       <div v-if="form.errors.banner_style" class="text-danger">{{ form.errors.banner_style }}</div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="mb-3">
                                       <label for="pageBannerBgColor" class="form-label-md mb-1">Page Banner Background Color</label>
                                       <v-select id="pageBannerBgColor" v-model="form.banner_background_color" :options="bannerBackgroundColors"
                                                 label="name" :reduce="option => option.value"
                                       />
                                       <div v-if="form.errors.banner_background_color" class="text-danger">{{ form.errors.banner_background_color }}</div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <div class="form-label-md mb-1">Page Banner Image</div>
                              <Dropzone @files-added="handleFiles" :multiple="true"
                                        selectFileStrategy="merge" :allowSelectOnPreview="true" />
                              <div v-if="form.errors.media" class="text-danger">{{ form.errors.media }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="content" class="form-label-md mb-1">Description</label>
                              <editor v-model="form.description" class="editor-control mx-0" id="editor"></editor>
                              <div v-if="form.errors.description" class="text-danger">{{ form.errors.description }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label class="row d-flex">
                                 <span class="col-1">
                                    <label class="form-check form-switch">
                                       <input v-model="form.published" class="form-check-input" type="checkbox">
                                    </label>
                                 </span>
                                 <span class="col-auto">
                                    <span class="fw-bold me-3">Publish</span>
                                 </span>
                                 <span class="form-check-description">When enabled, the page will appear on the website</span>
                              </label>
                              <div v-if="form.errors.published" class="text-danger">{{ form.errors.published }}</div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="formCleanUp"
                             :disabled="form.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="storePage" :disabled="form.processing">
                        {{ form.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Modal -->
         <div class="modal fade" id="edit-page-modal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="edit-page-modal-label"
              aria-hidden="true" ref="editPageModal"
         >
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-page-modal-label">Edit Page</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="editFormCleanUp"
                             :disabled="editForm.processing">
                     </button>
                  </div>
                  <div class="modal-body">
                     <form id="editForm" class="row" @submit.prevent="updatePage">
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="name" class="form-label-md mb-1">Title</label>
                              <input id="name" type="text" v-model="editForm.title" class="form-control">
                              <div v-if="editForm.errors.title" class="text-danger">{{ editForm.errors.title }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="slug" class="form-label-md mb-1">Page Slug</label>
                              <input id="slug" type="text" v-model="editForm.slug" class="form-control">
                              <div v-if="editForm.errors.slug" class="text-danger">{{ editForm.errors.slug }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="parentId" class="form-label-md mb-1">Parent Page</label>
                              <v-select id="parentId" v-model="editForm.parent_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                              <div v-if="editForm.errors.parent_id" class="text-danger">{{ editForm.errors.parent_id }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageType" class="form-label-md mb-1">Page Type</label>
                              <v-select id="pageType" v-model="editForm.page_type_id" :options="pageTypes"
                                        label="name" :reduce="option => option.id"
                              />
                              <div v-if="editForm.errors.page_type_id" class="text-danger">{{ editForm.errors.page_type_id }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12 d-md-block d-sm-none"></div>
                        <div v-if="editForm.title !== 'Home'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <div class="row">
                                 <div class="col-12">
                                    <div class="mb-3">
                                       <label for="pageBannerStyle" class="form-label-md mb-1">Page Banner Style</label>
                                       <v-select id="pageBannerStyle" v-model="editForm.banner_style" :options="pageBannerStyles"
                                                 label="name" :reduce="option => option.value"
                                       >
                                          <template #option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-success">{{ description }}</small>
                                             </div>
                                          </template>
                                          <template #selected-option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-success">{{ description }}</small>
                                             </div>
                                          </template>
                                       </v-select>
                                       <div v-if="editForm.errors.banner_style" class="text-danger">{{ editForm.errors.banner_style }}</div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="mb-3">
                                       <label for="pageBannerBgColor" class="form-label-md mb-1">Page Banner Background Color</label>
                                       <v-select id="pageBannerBgColor" v-model="editForm.banner_background_color" :options="bannerBackgroundColors"
                                                 label="name" :reduce="option => option.value"
                                       />
                                       <div v-if="editForm.errors.banner_background_color" class="text-danger">{{ editForm.errors.banner_background_color }}</div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div v-if="editForm.title !== 'Home'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <div class="form-label-md mb-1">Page Image</div>
                              <Dropzone v-model="editForm.banner_image" :multiple="false" :existing="editForm.banner_image ? editForm.banner_image : []"
                                        :showSelectButton="false" :max-file-size="5"
                                        img-width="100%" img-height="130px"
                                        previewPosition="inside" selectFileStrategy="replace"
                                        @preview-removed="handlePreviewRemoved"
                                        :upload-url="`/wp-admin/medias/pages/${editForm.page_id}/page-image`"
                              />
                              <div v-if="editForm.errors.banner_image" class="text-danger">{{ editForm.errors.banner_image }}</div>
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="description" class="form-label">Description</label>
                           <editor v-model="editForm.description" class="editor-control mx-0" id="description"></editor>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label class="row d-flex">
                                 <span class="col-1">
                                    <label class="form-check form-switch">
                                       <input v-model="editForm.published" class="form-check-input" type="checkbox">
                                    </label>
                                 </span>
                                 <span class="col">
                                    <span class="fw-bold me-3">Publish</span>
                                 </span>
                                 <span class="form-check-description">When enabled, the page will appear on the website.</span>
                              </label>
                              <div v-if="editForm.errors.published" class="text-danger">{{ editForm.errors.published }}</div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="editFormCleanUp"
                             :disabled="editForm.processing">
                        Close
                     </button>
                     
                     <button type="button" class="btn btn-primary" @click.prevent="updatePage" :disabled="editForm.processing">
                        {{ editForm.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- New Page SEO Modal -->
         <div class="modal fade" id="create-page-seo-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="create-page-seo-modal-label" aria-hidden="true" ref="createPageSeoModal"
         >
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="create-page-seo-modal-label">Manage SEO</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click.prevent="pageSeoFormCleanUp"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="storePageSeo">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="pageSeoForm.title" class="form-control">
                           <div v-if="pageSeoForm.errors.title" class="text-danger">{{ pageSeoForm.errors.title }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="description" class="form-label-md mb-1-md mb-2">Description</label>
                           <textarea id="description" rows="3" v-model="pageSeoForm.description" class="form-control"></textarea>
                           <div v-if="pageSeoForm.errors.description" class="text-danger">{{ pageSeoForm.errors.description }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="keywords" class="form-label-md mb-1-md mb-1">Keywords</label>
                           <div class="mb-2 small text-muted">Each keyword should be separated with a commas</div>
                           <input id="keywords" type="text" v-model="pageSeoForm.keywords" class="form-control" placeholder="e.g: website, school">
                           <div v-if="pageSeoForm.errors.keywords" class="text-danger">{{ pageSeoForm.errors.keywords }}</div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="pageSeoFormCleanUp"
                             :disabled="pageSeoForm.processing">
                        Close
                     </button>
                     
                     <button type="button" class="btn btn-primary" @click.prevent="storePageSeo" :disabled="pageSeoForm.processing">
                        {{ pageSeoForm.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import {Head, Link, useForm} from "@inertiajs/vue3";
import {Modal} from "bootstrap";
import _debounce from "lodash/debounce.js";
import DefaultLayout from "@layouts/DefaultLayout.vue";
import axios from "axios";
import Editor from "@components/global/Editor.vue";
import Dropzone from "@components/global/Dropzone.vue";

export default {
   components: {Dropzone, Editor, DefaultLayout, Head, Link},
   data() {
      return {
         fields: [
            {
               name: '__slot:title',
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
               titleClass: 'text-end w-5',
               dataClass: 'text-end w-5',
            },
         ],
         appendParams: {
            filter: {
               title: '',
            }
         },
         form: useForm({
            title: '',
            parent_id: '',
            page_type_id: '',
            menu_order: '',
            description: '',
            banner_style: '',
            banner_background_color: '',
            published: false,
            media: [],
         }),
         editForm: useForm({
            id: '',
            page_id: '',
            parent_id: '',
            title: '',
            slug: '',
            page_type_id: '',
            menu_order: '',
            description: '',
            banner_style: '',
            banner_background_color: '',
            published: false,
            banner_image: [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }],
         }),
         pageSeoForm: useForm({
            page_id: '',
            title: null,
            description: null,
            keywords: null,
         }),
         pages: [],
         pageTypes: [],
         pageBannerStyles: [
            {value: 'default', name: 'Default', description: 'Default page banner design'},
            {
               value: 'banner-with-no-image',
               name: 'Banner With No Background Image',
               description: 'This page banner is similar to the default one but it does not display the background image.'
            },
            {
               value: 'banner-layout-vertical',
               name: 'Page Banner Layout Vertical',
               description: 'Page banner with the content displayed in a vertical layout.'
            },
            {value: 'banner-layout-vertical-no-image', name: 'Page Banner Layout Vertical With No Image'},
            {
               value: 'banner-with-no-breadcrumb',
               name: 'Page Banner Without Breadcrumb',
               description: 'Page banner similar to the vertical layout but with no breadcrumb displayed'
            },
         ],
         bannerBackgroundColors: [
            {value: 'bg-gradient-overlay', name: 'Default'},
            {value: 'bg-primary-gradient-overlay', name: 'Primary'},
            {value: 'bg-secondary-gradient-overlay', name: 'Secondary'},
            {value: 'bg-blue-gradient-overlay', name: 'Blue'},
            {value: 'bg-light', name: 'Light'},
            {value: 'bg-dark', name: 'Dark'},
         ],
      }
   },
   mounted() {
      this.fetchPages();
      this.fetchPageTypes();
   },
   methods: {
      fetchPageTypes() {
         axios.get(route('datatable.page-types'))
            .then(({data}) => {
               this.pageTypes = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the page types!", "Error");
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
      createPage() {
         const modalElement = this.$refs.createPageModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      handleFiles(fileObj) {
         if (Array.isArray(fileObj)) {
            this.form.media = fileObj.map(f => f.file).filter(Boolean);
         } else {
            if (!this.form.media) this.form.media = [];
            if (fileObj.file) this.form.media.push(fileObj.file);
         }
      },
      storePage() {
         this.form.post(route('admin.pages.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.$refs.pagesTable.reloadTable();
               const modalElement = this.$refs.createPageModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Page Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editPage(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.page_id = rowData.id;
         this.editForm.parent_id = rowData.parent_id;
         this.editForm.title = rowData.title;
         this.editForm.slug = rowData.slug;
         this.editForm.page_type_id = rowData.page_type_id;
         this.editForm.description = rowData.description;
         this.editForm.banner_style = rowData.banner_style;
         this.editForm.banner_background_color = rowData.banner_background_color;
         this.editForm.published = rowData.published;
         const file = rowData.media?.find(md => md.collection_name === 'page-image') ?? null;
         if (file) {
            this.editForm.banner_image = [{
               id: file.id,
               model_id: rowData.id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true,
            }];
         } else {
            this.editForm.banner_image = [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }];
         }
         
         const modalElement = this.$refs.editPageModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updatePage() {
         this.editForm.patch(route('admin.pages.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.$refs.pagesTable.reloadTable();
               const modalElement = this.$refs.editPageModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Page Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.error('Error updating page: ', errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      handlePreviewRemoved(previewObject, rowData) {
         // Extract URL from previewObject
         let url = previewObject;
         if (typeof previewObject === 'object' && previewObject.src) {
            url = previewObject.src;
         }
         
         // Find matching image in existingImages
         const matchingImage = this.existingImages.find(
            (img) => img.url === url
         );
         
         if (matchingImage && matchingImage.id) {
            this.$inertia.delete(
               this.route("admin.medias.delete.page-image", {
                  page: rowData.id,
                  media: matchingImage.id,
               }),
               {
                  preserveScroll: true,
                  onSuccess: () => {
                     const index = this.existingImages.findIndex(
                        (img) => img.id === matchingImage.id
                     );
                     if (index > -1) {
                        this.existingImages.splice(index, 1);
                     }
                     this.$toast.success("Image deleted successfully", "Success");
                  },
                  onError: (error) => {
                     console.error("Failed to delete image:", error);
                     this.$toast.error("Failed to delete image", "Error");
                  },
               }
            );
         } else {
            console.error("Could not find matching image ID for URL:", url);
         }
      },
      managePageSEO(rowData) {
         if (rowData && rowData.meta_seo) {
            this.pageSeoForm.title = rowData.meta_seo.title;
            this.pageSeoForm.description = rowData.meta_seo.description;
            this.pageSeoForm.keywords = rowData.meta_seo.keywords;
         } else {
            this.pageSeoForm.reset();
         }
         
         this.pageSeoForm.page_id = rowData.hashid;
         
         const modalElement = this.$refs.createPageSeoModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storePageSeo() {
         this.pageSeoForm.post(route('admin.pages.meta-seo.store', this.pageSeoForm.page_id), {
            onSuccess: () => {
               this.pageSeoForm.reset();
               this.pageSeoForm.clearErrors();
               
               this.$refs.pagesTable.reloadTable();
               
               const modalElement = this.$refs.createPageSeoModal;
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
      deletePage(page) {
         this.$toast.question('Are you sure?', `Deleting ${page.title}`).then(() => {
            this.$inertia.delete(route('admin.pages.destroy', page.hashid), {
               onSuccess: () => {
                  this.$toast.success('Page deleted successfully!', 'Success');
                  this.$refs.pagesTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the page!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.pagesTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset()
         this.form.clearErrors()
      },
      editFormCleanUp() {
         this.editForm.reset()
         this.editForm.clearErrors()
      },
      pageSeoFormCleanUp() {
         this.pageSeoForm.reset();
         this.pageSeoForm.clearErrors();
      },
   },
}
</script>

<style scoped>
</style>

<template>
   <Head title="SEO Settings"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">SEO Settings</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  SEO Configurations
               </li>
            </ol>
         </nav>
         
         <div class="col-xxl-12">
            <div class="card">
               <div class="card-body border-bottom">
                  <div class="row align-items-center">
                     <div class="col-10">
                        <h5 class="mb-0">Generate Sitemap File</h5>
                        <div class="markdown text-secondary">
                           Visit this link to view the sitemap file: <a href="/page-sitemap" target="_blank" class="ms-2 text-primary text-decoration-underline">SITEMAP</a>
                        </div>
                     </div>
<!--                     <div class="col-auto ms-md-auto mt-md-0 ms-0 mt-3 d-print-none">-->
<!--                        <div class="btn-list">-->
<!--                           <button class="btn btn-primary d-none d-sm-inline-block" :disabled="form.processing" @click="generateSiteMap">-->
<!--                              Generate-->
<!--                           </button>-->
<!--                           <button class="btn btn-primary d-sm-none btn-icon" :disabled="form.processing" @click="generateSiteMap">-->
<!--                              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-world-download"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 12a9 9 0 1 0 -9 9" /><path d="M3.6 9h16.8" /><path d="M3.6 15h8.4" /><path d="M11.578 3a17 17 0 0 0 0 18" /><path d="M12.5 3c1.719 2.755 2.5 5.876 2.5 9" /><path d="M18 14v7m-3 -3l3 3l3 -3" /></svg>-->
<!--                           </button>-->
<!--                        </div>-->
<!--                     </div>-->
                  </div>
               </div>
               <div class="card-header flex-column flex-md-row">
                  <div class="row row-gap-1">
                     <div class="col-md-3 col-9">
                        <input type="search" id="search" class="form-control bg-muted-lt rounded-2"
                               placeholder="Search..."
                               @input="applyFilter" v-model="appendParams.filter.title">
                     </div>
                     <div class="col-md-6 col-3 ms-md-auto">
                        <div class="flex-wrap text-end">
                           <div class="card-action">
                              <button type="button" class="btn btn-primary d-none d-sm-inline-block" @click="createMetaSeo">
                                 <i class="bx bx-plus-circle me-2"></i>
                                 Add Meta
                              </button>
                              <button type="button" class="btn btn-primary btn-icon d-sm-none" @click="createMetaSeo">
                                 <i class="bx bx-plus"></i>
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <VueTable
                  :fields="fields"
                  api-url="datatable/meta-seos"
                  :append-params="appendParams"
                  ref="metaSeosTable"
               >
                  <template #module="props">
                     <span v-if="props.rowData.seo_able_type === 'App\\Models\\Page'">
                        Pages
                     </span>
                     <span v-else-if="props.rowData.seo_able_type === 'App\\Models\\Project'">
                        Blogs
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
                           <a class="dropdown-item" href="#" @click="editMetaSeo(props.rowData)">
                              <i class="bx bx-edit-alt me-2"></i>Edit
                           </a>
                           <a class="dropdown-item text-danger" href="#" @click.prevent="deleteMetaSeo(props.rowData)">
                              <i class="bx bx-trash me-2"></i>Delete
                           </a>
                        </div>
                     </div>
                  </template>
               </VueTable>
            </div>
         </div>
         
         <!-- Create Modal -->
         <div
            class="modal fade"
            id="create-meta-seo-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="create-meta-seo-modal-label"
            aria-hidden="true"
            ref="createMetaSeoModal"
         >
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="create-meta-seo-modal-label">Add Meta</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click.prevent="formCleanUp"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="storeMetaSeo">
                        <div class="mb-3">
                           <label for="seoAbleType" class="form-label">Module</label>
                           <v-select
                              id="seoAbleType"
                              v-model="form.seo_able_type"
                              :options="modules"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.seo_able_type" class="text-danger">{{ form.errors.seo_able_type }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="seoAbleId" class="form-label">Page</label>
                           <v-select
                              id="seoAbleId"
                              v-model="form.seo_able_id"
                              :options="moduleInstances"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="form.errors.seo_able_type" class="text-danger">{{ form.errors.seo_able_type }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="title" class="form-label">Title</label>
                           <input id="title" type="text" v-model="form.title" class="form-control">
                           <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="description" class="form-label-md mb-2">Description</label>
                           <textarea id="description" rows="3" v-model="form.description" class="form-control"></textarea>
                           <div v-if="form.errors.description" class="text-danger">{{ form.errors.description }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="keywords" class="form-label-md mb-1">Keywords</label>
                           <div class="mb-2 small text-muted">Each keyword should be separated with a commas</div>
                           <input id="keywords" type="text" v-model="form.keywords" class="form-control" placeholder="e.g: website, school">
                           <div v-if="form.errors.keywords" class="text-danger">{{ form.errors.keywords }}</div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
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
                        @click.prevent="storeMetaSeo"
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
            id="edit-meta-seo-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="edit-meta-seo-modal-label"
            aria-hidden="true"
            ref="editMetaSeoModal"
         >
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-meta-seo-modal-label">Edit Meta</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click.prevent="formCleanUp"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="updateMetaSeo">
                        <div class="mb-3">
                           <label for="seoAbleType" class="form-label">Module</label>
                           <v-select
                              id="seoAbleType"
                              v-model="editForm.seo_able_type"
                              :options="modules"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="editForm.errors.seo_able_type" class="text-danger">{{ editForm.errors.seo_able_type }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="seoAbleId" class="form-label">Page</label>
                           <v-select
                              id="seoAbleId"
                              v-model="editForm.seo_able_id"
                              :options="moduleInstances"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="editForm.errors.seo_able_id" class="text-danger">{{ editForm.errors.seo_able_id }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="title" class="form-label">Title</label>
                           <input id="title" type="text" v-model="editForm.title" class="form-control">
                           <div v-if="editForm.errors.title" class="text-danger">{{ editForm.errors.title }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="description" class="form-label-md mb-2">Description</label>
                           <textarea id="description" rows="3" v-model="editForm.description" class="form-control"></textarea>
                           <div v-if="editForm.errors.description" class="text-danger">{{ editForm.errors.description }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="keywords" class="form-label-md mb-1">Keywords</label>
                           <div class="mb-2 small text-muted">Each keyword should be separated with a commas</div>
                           <input id="keywords" type="text" v-model="editForm.keywords" class="form-control" placeholder="e.g: website, school">
                           <div v-if="editForm.errors.keywords" class="text-danger">{{ editForm.errors.keywords }}</div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button
                        type="button"
                        class="btn btn-secondary me-2"
                        data-bs-dismiss="modal"
                        @click="editFormCleanUp"
                     >
                        Close
                     </button>
                     <button
                        type="button"
                        class="btn btn-primary"
                        @click.prevent="updateMetaSeo"
                     >
                        Update
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {Modal} from "bootstrap";
import _debounce from "lodash/debounce.js";
import axios from "axios";

export default {
   components: {DefaultLayout, Head, Link},
   data() {
      return {
         fields: [
            {
               name: '__slot:module',
               title: 'MODULE',
            },
            {
               name: 'title',
               title: 'TITLE',
            },
            {
               name: 'description',
               title: 'DESCRIPTION',
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
            id: null,
            seo_able_type: null,
            seo_able_id: null,
            title: null,
            description: null,
            keywords: null,
         }),
         editForm: useForm({
            id: null,
            seo_able_type: null,
            seo_able_id: null,
            title: null,
            description: null,
            keywords: null,
         }),
         modules: [
            {value: 'App\\Models\\Page', name: 'Pages'},
            {value: 'App\\Models\\Project', name: 'Projects'},
         ],
         moduleInstances: [],
         pages: [],
         projects: [],
      }
   },
   watch: {
      'form.seo_able_type': function(newVal) {
         this.moduleInstances = [];
         if(newVal === "App\\Models\\Page") {
            this.moduleInstances = this.pages
         } else if (newVal === "App\\Models\\Project") {
            this.moduleInstances = this.projects
         } else {
            this.moduleInstances = []
         }
      },
      'editForm.seo_able_type': function(newVal) {
         this.moduleInstances = [];
         if(newVal === "App\\Models\\Page") {
            this.moduleInstances = this.pages
         } else if (newVal === "App\\Models\\Project") {
            this.moduleInstances = this.projects
         } else {
            this.moduleInstances = []
         }
      },
   },
   mounted() {
      this.fetchedPages();
      this.fetchedProjects();
   },
   methods: {
      fetchedPages() {
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
      fetchedProjects() {
         axios.get(route('datatable.projects'), {
            params: {
               filter: {
                  active: true,
               }
            }
         })
         .then(({ data }) => {
            this.projects = data.data;
         })
         .catch((error) => {
            this.$toast.error("An error occurred while fetching the projects!", "Error");
            console.error(error);
         });
      },
      createMetaSeo() {
         const modalElement = this.$refs.createMetaSeoModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storeMetaSeo() {
         this.form.post(route('admin.meta-seos.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.moduleInstances = [];
               this.$refs.metaSeosTable.reloadTable();
               const modalElement = this.$refs.createMetaSeoModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Meta SEO created successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editMetaSeo(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.title = rowData.title;
         this.editForm.description = rowData.description;
         this.editForm.keywords = rowData.keywords;
         this.editForm.seo_able_type = rowData.seo_able_type;
         this.editForm.seo_able_id = rowData.seo_able_id;
         
         const modalElement = this.$refs.editMetaSeoModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateMetaSeo() {
         this.editForm.patch(route('admin.meta-seos.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.moduleInstances = [];
               this.$refs.metaSeosTable.reloadTable();
               const modalElement = this.$refs.editMetaSeoModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Meta SEO updated successfully', 'Success')
            },
            onError: (error) => {
               console.log(error)
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteMetaSeo(rowData) {
         this.$toast.question('Are you sure? This process is irreversible!', `Deleting ${rowData.title}`).then(() => {
            this.$inertia.delete(route('admin.meta-seos.destroy', rowData.hashid), {
               onSuccess: () => {
                  this.$toast.success('Meta SEO deleted successfully!', 'Success');
                  this.$refs.metaSeosTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the SEO Meta!', 'Error');
               }
            })
         })
      },
      generateSiteMap() {
         this.form.post(route('admin.sitemap.generate'), {
            onSuccess: () => {
               setTimeout(() => {
                  this.$refs.metaSeosTable.reloadTable();
                  this.$toast.success('Sitemap file generated', 'Success')
               }, 800);
            },
            onError: (error) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.metaSeosTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.moduleInstances = [];
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
         this.moduleInstances = [];
      },
   },
}
</script>

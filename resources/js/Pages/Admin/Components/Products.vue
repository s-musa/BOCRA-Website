<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Products"
                      @input="applyFilter" v-model="appendParams.filter.name">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateProductModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Product
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateProductModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/products"
         :append-params="appendParams"
         ref="productsTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editProduct(props.rowData)">
                     <i class="bx bx-edit-alt me-2"></i>Edit
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openProductMediaModal(props.rowData)">
                     <i class="bx bx-image-add me-2"></i>Change Image
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteProduct(props.rowData)">
                     <i class="bx bx-trash me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Modal -->
      <div class="modal fade" id="create-product-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-product-modal-label" aria-hidden="true" ref="createProductModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-product-modal-label">Add Product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="createForm" class="row" @submit.prevent="createProduct">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="name" class="form-label-md mb-1">Name</label>
                           <input id="name" type="text" v-model="form.name" class="form-control">
                           <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="productType" class="form-label-md mb-1">Product Type</label>
                           <v-select id="productType" v-model="form.product_type_id" :options="types"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.product_type_id" class="text-danger">{{ form.errors.product_type_id }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="productMedia" class="form-label-md mb-1">Product Image</label>
                           <input id="productMedia" @change="handleMediaUpload" type="file" accept="image/*" class="form-control">
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
                           <span class="form-check-description">When enabled, the product will be displayed in the system.</span>
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
                          @click.prevent="createProduct" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-product-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-product-modal-label" aria-hidden="true" ref="editProductModal"
      >
         <div class="modal-dialog modal-lg modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-product-modal-label">Edit Product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click="formCleanUp" :disabled="form.processing"
                  ></button>
               </div>
               <div class="modal-body">
                  <div id="editForm" class="row" @submit.prevent="updateProduct">
                     <div class="col-12">
                        <div class="mb-3">
                           <label for="name" class="form-label-md mb-1">Name</label>
                           <input id="name" type="text" v-model="form.name" class="form-control">
                           <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="slug" class="form-label-md mb-1">Product Slug</label>
                           <input id="slug" type="text" v-model="form.slug" class="form-control">
                           <div v-if="form.errors.slug" class="text-danger">{{ form.errors.slug }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="productType" class="form-label-md mb-1">Product Type</label>
                           <v-select id="productType" v-model="form.product_type_id" :options="types"
                                     label="name" :reduce="option => option.id"
                           />
                           <div v-if="form.errors.product_type_id" class="text-danger">{{ form.errors.product_type_id }}</div>
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
                           <span class="form-check-description">When enabled, the product will be displayed in the system.</span>
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
                          @click.prevent="updateProduct" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Product Media Upload Modal -->
      <div class="modal fade" id="product-media-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="product-media-upload-modal-label" aria-hidden="true" ref="productMediaUploadModal"
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
                           No Image found for this product
                        </p>
                     </div>
                     <div class="mb-3">
                        <label for="title" class="form-label-md mb-1">New Image</label>
                        <input @change="handleMediaChange" id="productImage" type="file" accept="image/*"
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
               name: 'name',
               title: 'NAME',
            },
            {
               name: 'type.name',
               title: 'TYPE',
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
               name: '',
            }
         },
         form: useForm({
            id: null,
            name: null,
            slug: null,
            product_type_id: null,
            details: null,
            media: null,
            active: true,
         }),
         types: [],
         fetchedTags: [],
         mediaForm: useForm({
            product_id: '',
            file: null,
         }),
         selectedMedia: [],
      };
   },
   mounted() {
      this.fetchTypes();
   },
   methods: {
      fetchTypes() {
         axios.get(route('datatable.product-types'), {
            params: {
               filter: {
                  'active': true,
               }
            }
         })
            .then(({data}) => {
               this.types = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the types!", "Error");
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
      showCreateProductModal() {
         const modalElement = this.$refs.createProductModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createProduct() {
         this.form.post(route('admin.components.products.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.productsTable.reloadTable();
               const modalElement = this.$refs.createProductModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Product Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editProduct(rowData) {
         this.form.id = rowData.hashid;
         this.form.name = rowData.name;
         this.form.slug = rowData.slug;
         this.form.product_type_id = rowData.product_type_id;
         this.form.details = rowData.details;
         this.form.active = rowData.active;
         
         const modalElement = this.$refs.editProductModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateProduct() {
         this.form.patch(route('admin.components.products.update', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.form.media = null;
               this.$refs.productsTable.reloadTable();
               const modalElement = this.$refs.editProductModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Product Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openProductMediaModal(product) {
         const image = product.media.find(m => m.collection_name === 'product-image')
         if (image) {
            this.selectedMedia = image;
         }
         this.mediaForm.product_id = product.id;
         const modalElement = this.$refs.productMediaUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      uploadMedia() {
         this.mediaForm.post(route('admin.medias.upload.product-image'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            onSuccess: () => {
               this.mediaForm.reset();
               this.mediaForm.clearErrors();
               this.$refs.productsTable.reloadTable();
               this.$toast.success('Image changed', 'Updated');
               const modalElement = this.$refs.productMediaUploadModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      deleteProduct(product) {
         this.$toast.question('Are you sure?', `Deleting ${product.name}`).then(() => {
            this.$inertia.delete(route('admin.components.products.destroy', product.hashid), {
               onSuccess: () => {
                  this.$toast.success('Product deleted', 'Success');
                  this.$refs.productsTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the product!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.productsTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
      },
      mediaModalCleanUp() {
         this.mediaForm.reset();
         this.mediaForm.clearErrors();
         this.mediaForm.file = null;
         this.selectedMedia = [];
      },
   },
}
</script>

<template>
   <div class="card">
      <div class="card-header flex-column flex-md-row">
         <div class="row row-gap-1">
            <div class="col-md-3 col-9">
               <input type="search" id="search" class="form-control bg-muted-lt rounded-2" placeholder="Search Services"
                      @input="applyFilter" v-model="appendParams.filter.title">
            </div>
            <div class="col-md-6 col-3 ms-auto">
               <div class="flex-wrap text-end">
                  <div class="card-action">
                     <button type="button" class="btn btn-primary d-none d-sm-inline-block"
                             @click="showCreateServiceModal">
                        <i class="bx bx-plus-circle me-2"></i>
                        Add Service
                     </button>
                     <button type="button" class="btn btn-primary btn-icon d-sm-none"
                             @click="showCreateServiceModal">
                        <i class="bx bx-plus"></i>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <VueTable
         :fields="fields"
         api-url="datatable/services"
         :append-params="appendParams"
         ref="servicesTable"
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
                  <a class="dropdown-item" href="#" @click.prevent="editService(props.rowData)">
                     <i class="bx bx-edit-alt icon-20px me-2"></i>Edit
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openServiceFeatureModal(props.rowData)">
                     <i class="bx bx-category-alt icon-20px me-2"></i>Service Features
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openSubServiceModal(props.rowData)">
                     <i class="bx bx-sitemap icon-20px me-2"></i>Sub-Services
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="openServiceGalleryModal(props.rowData)">
                     <i class="bx bx-image-add icon-20px me-2"></i>Service Gallery
                  </a>
                  <a class="dropdown-item" href="#" @click.prevent="manageServiceSEO(props.rowData)">
                     <i class="bx bx-search-alt icon-20px me-2"></i> Service SEO
                  </a>
                  <a class="dropdown-item text-danger" href="#" @click.prevent="deleteService(props.rowData)">
                     <i class="bx bx-trash icon-20px me-2"></i>Delete
                  </a>
               </div>
            </div>
         </template>
      </VueTable>
      
      <!-- Create Modal -->
      <div class="modal fade" id="create-service-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-service-modal-label" aria-hidden="true" ref="createServiceModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-service-modal-label">Add Service</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="formCleanUp">
                  </button>
               </div>
               <div class="modal-body">
                  <form id="createForm" class="row" @submit.prevent="createService">
                     <div class="col-12 mb-3">
                        <label for="title" class="form-label-md mb-1">Title</label>
                        <input id="title" type="text" v-model="form.title" class="form-control">
                        <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="shortDescription" class="form-label-md mb-1">Short Service Description</label>
                           <div class="form-text small text-muted mb-2">This will be displayed on cards and carousels.</div>
                           <textarea v-model="form.short_description" id="shortDetails" class="form-control" rows="8" />
                           <div v-if="form.errors.short_description" class="text-danger">{{ form.errors.short_description }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12 mb-3">
                        <label for="serviceMedia" class="form-label-md mb-1">Service Image</label>
                        <Dropzone @files-added="handleMediaUpload" :multiple="false"
                                  selectFileStrategy="replace"/>
                        <div v-if="form.errors.media" class="text-danger">{{ form.errors.media }}</div>
                     </div>
                     <div class="col-12 mb-3">
                        <label for="details" class="form-label-md mb-1">Details</label>
                        <editor v-model="form.details" id="editor" class="editor-control" />
                        <div v-if="form.errors.details" class="text-danger">{{ form.errors.details }}</div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label class="row">
                              <span class="d-flex">
                                 <span class="fw-bold me-3">Active</span>
                                 <span class="d-flex justify-content-between align-items-end">
                                    <label class="form-check form-switch mb-0">
                                       <input v-model="form.active" class="form-check-input" type="checkbox">
                                    </label>
                                 </span>
                              </span>
                              <span class="form-check-description">When enabled, the service will be displayed in the website.</span>
                           </label>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="formCleanUp">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary" @click.prevent="createService" :disabled="form.processing">
                     Submit
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Edit Modal -->
      <div class="modal fade" id="edit-service-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="edit-service-modal-label" aria-hidden="true" ref="editServiceModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-service-modal-label">Edit Service</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close" @click="editFormCleanUp"
                  ></button>
               </div>
               <div class="modal-body">
                  <form id="editForm" class="row" @submit.prevent="updateService">
                     <div class="col-md-6 col-12 mb-3">
                        <label for="title" class="form-label-md mb-1">Title</label>
                        <input id="title" type="text" v-model="editForm.title" class="form-control">
                        <div v-if="editForm.errors.title" class="text-danger">{{ editForm.errors.title }}</div>
                     </div>
                     <div class="col-md-6 col-12 mb-3">
                        <label for="slug" class="form-label-md mb-1">Service Slug</label>
                        <input id="slug" type="text" v-model="editForm.slug" class="form-control">
                        <div v-if="editForm.errors.slug" class="text-danger">{{ editForm.errors.slug }}</div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="shortDescription" class="form-label-md mb-1">Short Project Description</label>
                           <div class="form-text small text-muted mb-2">This will be displayed on cards and carousels. A brief description of what the project is about.</div>
                           <textarea v-model="editForm.short_description" id="shortDetails" class="form-control" rows="8" />
                           <div v-if="editForm.errors.short_description" class="text-danger">{{ editForm.errors.short_description }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12 mb-3">
                        <label for="serviceMedia" class="form-label-md mb-1">Service Image</label>
                        <Dropzone v-model="editForm.media" :multiple="false"
                                  :existing="editForm.media ? editForm.media : []"
                                  :upload-url="`/wp-admin/medias/services/${editForm.service_id}`"
                                  :delete-url="`/wp-admin/media/services/${editForm.service_id}/delete`"
                                  selectFileStrategy="replace"
                                  :showSelectButton="false"/>
                        <div v-if="editForm.errors.media" class="text-danger">{{ editForm.errors.media }}</div>
                     </div>
                     <div class="col-12">
                        <div class="mb-4">
                           <label for="details" class="form-label-md mb-1">Details</label>
                           <editor v-model="editForm.details" id="editor" class="editor-control" />
                           <div v-if="editForm.errors.details" class="text-danger">{{ editForm.errors.details }}</div>
                        </div>
                     </div>
                     
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label class="row">
                              <span class="d-flex">
                                 <span class="fw-bold me-3">Active</span>
                                 <span class="d-flex justify-content-between align-items-end">
                                    <label class="form-check form-switch mb-0">
                                       <input v-model="editForm.active" class="form-check-input" type="checkbox">
                                    </label>
                                 </span>
                              </span>
                              <span class="form-check-description">When enabled, the service will be displayed in the website.</span>
                           </label>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer pt-5">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="editFormCleanUp"
                          :disabled="editForm.processing">
                     Close
                  </button>
                  <button type="button" class="btn btn-primary" @click.prevent="updateService" :disabled="editForm.processing">
                     Update
                  </button>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Service Gallery Upload Modal -->
      <div class="modal fade" id="service-media-upload-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="service-media-upload-modal-label" aria-hidden="true" ref="serviceMediaUploadModal"
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
                                     :allowSelectOnPreview="true"
                                     :max-files="15"
                                     :max-file-size="5"
                                     @preview-removed="handlePreviewRemoved"
                                     :upload-url="`/wp-admin/medias/services/${galleryForm.service_id}/gallery`"
                           />
                           <div v-if="galleryForm.errors.file" class="text-danger">{{ galleryForm.errors.file }}</div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Service Features Modal -->
      <div class="modal fade" id="service-feature-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="service-feature-modal-label" aria-hidden="true" ref="serviceFeatureModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-service-modal-label">Service Features</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close" @click="serviceFeatureFormCleanUp"></button>
               </div>
               <div class="modal-body">
                  <form id="serviceFeatureForm" class="row mb-5" @submit.prevent="storeServiceFeature">
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="serviceFeatureForm.title" class="form-control">
                           <div v-if="serviceFeatureForm.errors.title" class="text-danger">{{ serviceFeatureForm.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="icon" class="form-label-md mb-1">Icon</label>
                           <v-select id="icon" v-model="serviceFeatureForm.icon" :options="icons" label="name"
                                     :reduce="option => option.value" >
                              <template #option="option">
                                 <span class="d-block small">
                                    <i class="mdi fs-4 me-2" :class="`${option.value}`"></i>|<span class="ms-2">{{ option.name }}</span>
                                 </span>
                              </template>
                              <template #selected-option="option">
                                 <span class="d-block small">
                                    <i class="mdi fs-4 me-2" :class="`${option.value}`"></i>|<span class="ms-2">{{ option.name }}</span>
                                 </span>
                              </template>
                           </v-select>
                           <div v-if="serviceFeatureForm.errors.icon" class="text-danger">{{ serviceFeatureForm.errors.icon }}</div>
                        </div>
                     </div>
                     <div class="col-12 mb-3">
                        <label for="details" class="form-label-md">Details</label>
                        <editor v-model="serviceFeatureForm.details" id="editor" class="editor-control" />
                        <div v-if="serviceFeatureForm.errors.details" class="text-danger">{{ serviceFeatureForm.errors.details }}</div>
                     </div>
                     <div v-if="isEditingServiceFeature" class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-secondary me-2" @click.prevent="closeEditingFeature"
                                   :disabled="serviceFeatureForm.processing">
                              Exit Editing
                           </button>
                           <button type="button" class="btn btn-primary" @click.prevent="updateServiceFeature"
                                   :disabled="serviceFeatureForm.processing">
                              Update
                           </button>
                        </div>
                     </div>
                     <div v-else class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-primary" @click.prevent="storeServiceFeature"
                                   :disabled="serviceFeatureForm.processing">
                              Save
                           </button>
                        </div>
                     </div>
                  </form>
                  
                  <div class="col-12">
                     <div class="mt-5">
                        <label for="pricingFeatures" class="form-label-md mb-4 fw-bold fs-5">Service Features</label>
                        <div class="table-responsive">
                           <table class="table table-bordered table-hover table-center">
                              <thead class="bg-light-subtle">
                              <tr>
                                 <th class="fw-bold py-3">Title</th>
                                 <th class="fw-bold py-3 w__5">Icon</th>
                                 <th class="fw-bold py-3 w__5"></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="feature in selectedServiceFeatures">
                                 <td class="py-3 px-2" style="line-height: 0.2;">{{ feature.title }}</td>
                                 <td class="py-3 px-2 text-center" style="line-height: 0.2;">
                                    <i class="mdi fs-3" :class="`${feature.icon}`"></i>
                                 </td>
                                 <td class="py-3 px-2">
                                    <div class="dropdown">
                                       <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical"></i>
                                       </button>
                                       <div class="dropdown-menu dropdown-menu-end">
                                          <button type="button" class="dropdown-item" @click.prevent="editServiceFeature(feature)">
                                             <i class="bx bx-edit-alt icon-20px me-2"></i>Edit
                                          </button>
                                          <button type="button" class="dropdown-item text-danger" @click.prevent="deleteServiceFeature(feature)">
                                             <i class="bx bx-trash icon-20px me-2"></i> Delete
                                          </button>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-12">
                     <div class="mt-10">
                        <button type="button" class="btn btn-light" @click.prevent="closeServiceFeatureModal"
                                :disabled="serviceFeatureForm.processing">
                           Close Modal
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Sub-Service Modal -->
      <div class="modal fade" id="service-feature-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="service-feature-modal-label" aria-hidden="true" ref="subServiceModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-service-modal-label">New Sub-Service</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close" @click="subServiceFormCleanUp"></button>
               </div>
               <div class="modal-body">
                  <form id="serviceFeatureForm" class="row mb-5" @submit.prevent="storeSubService">
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="subServiceForm.title" class="form-control">
                           <div v-if="subServiceForm.errors.title" class="text-danger">{{ subServiceForm.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-12 mb-3">
                        <label for="details" class="form-label-md">Details</label>
                        <editor v-model="subServiceForm.details" id="editor" class="editor-control" />
                        <div v-if="subServiceForm.errors.details" class="text-danger">{{ subServiceForm.errors.details }}</div>
                     </div>
                     <div v-if="isEditingSubService" class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-light me-2" @click.prevent="closeEditingSubService(subServiceForm.service_id)"
                                   :disabled="subServiceForm.processing">
                              Exit Editing
                           </button>
                           <button type="button" class="btn btn-primary" @click.prevent="updateSubService"
                                   :disabled="subServiceForm.processing">
                              Update
                           </button>
                        </div>
                     </div>
                     <div v-else class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-primary" @click.prevent="storeSubService"
                                   :disabled="subServiceForm.processing">
                              Save
                           </button>
                        </div>
                     </div>
                  </form>
                  
                  <div class="col-12">
                     <div class="mt-5">
                        <label for="pricingFeatures" class="form-label-md mb-4 fw-bold fs-5">Sub-Services</label>
                        <div class="table-responsive">
                           <table class="table table-bordered table-hover table-center">
                              <thead class="bg-light-subtle">
                              <tr>
                                 <th class="fw-bold py-3">Title</th>
                                 <th class="fw-bold py-3 w__5"></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="subService in selectedSubServices">
                                 <td class="py-3 px-2">{{ subService.title }}</td>
                                 <td class="py-3 px-2">
                                    <div class="dropdown">
                                       <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical"></i>
                                       </button>
                                       <div class="dropdown-menu dropdown-menu-end">
                                          <button type="button" class="dropdown-item" @click.prevent="editSubService(subService)">
                                             <i class="bx bx-edit-alt icon-20px me-2"></i>Edit
                                          </button>
                                          <button type="button" class="dropdown-item" @click.prevent="openSubServiceFeatureModal(subService)">
                                             <i class="bx bx-category-alt icon-20px me-2"></i>Sub-Service Features
                                          </button>
                                          <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSubService(subService)">
                                             <i class="bx bx-trash icon-20px me-2"></i> Delete
                                          </button>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-12">
                     <div class="mt-10">
                        <button type="button" class="btn btn-light" @click.prevent="closeSubServiceModal"
                                :disabled="subServiceForm.processing">
                           Close Modal
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Sub-Service Features Modal -->
      <div class="modal fade" id="sub-service-feature-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="sub-service-feature-modal-label" aria-hidden="true" ref="subServiceFeatureModal"
      >
         <div class="modal-dialog modal-xl modal-body-simple">
            <div class="modal-content">
               <div class="modal-header pb-5">
                  <h5 class="modal-title" id="create-sub-service-feature-modal-label">Sub-Service Features</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"
                          aria-label="Close" @click="subServiceFeatureFormCleanUp"></button>
               </div>
               <div class="modal-body">
                  <form id="subServiceFeatureForm" class="row mb-5" @submit.prevent="storeSubServiceFeature">
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="title" class="form-label-md mb-1">Title</label>
                           <input id="title" type="text" v-model="subServiceFeatureForm.title" class="form-control">
                           <div v-if="subServiceFeatureForm.errors.title" class="text-danger">{{ subServiceFeatureForm.errors.title }}</div>
                        </div>
                     </div>
                     <div class="col-md-6 col-12">
                        <div class="mb-3">
                           <label for="icon" class="form-label-md mb-1">Icon</label>
                           <v-select id="icon" v-model="subServiceFeatureForm.icon" :options="icons" label="name"
                                     :reduce="option => option.value" >
                              <template #option="option">
                                 <span class="d-block small">
                                    <i class="mdi fs-4 me-2" :class="`${option.value}`"></i>|<span class="ms-2">{{ option.name }}</span>
                                 </span>
                              </template>
                              <template #selected-option="option">
                                 <span class="d-block small">
                                    <i class="mdi fs-4 me-2" :class="`${option.value}`"></i>|<span class="ms-2">{{ option.name }}</span>
                                 </span>
                              </template>
                           </v-select>
                           <div v-if="subServiceFeatureForm.errors.icon" class="text-danger">{{ subServiceFeatureForm.errors.icon }}</div>
                        </div>
                     </div>
                     <div class="col-12 mb-3">
                        <label for="details" class="form-label-md">Details</label>
                        <editor v-model="subServiceFeatureForm.details" id="editor" class="editor-control" />
                        <div v-if="subServiceFeatureForm.errors.details" class="text-danger">{{ subServiceFeatureForm.errors.details }}</div>
                     </div>
                     <div v-if="isEditingSubServiceFeature" class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-secondary me-2" @click.prevent="closeEditingSubServiceFeature"
                                   :disabled="subServiceFeatureForm.processing">
                              Exit Editing
                           </button>
                           <button type="button" class="btn btn-primary" @click.prevent="updateSubServiceFeature"
                                   :disabled="subServiceFeatureForm.processing">
                              Update
                           </button>
                        </div>
                     </div>
                     <div v-else class="col-12">
                        <div class="mb-4">
                           <button type="button" class="btn btn-primary" @click.prevent="storeSubServiceFeature"
                                   :disabled="subServiceFeatureForm.processing">
                              Save
                           </button>
                        </div>
                     </div>
                  </form>
                  
                  <div class="col-12">
                     <div class="mt-5">
                        <label for="pricingFeatures" class="form-label-md mb-4 fw-bold fs-5">Sub-Service Features</label>
                        <div class="table-responsive">
                           <table class="table table-bordered table-hover table-center">
                              <thead class="bg-light-subtle">
                              <tr>
                                 <th class="fw-bold py-3">Title</th>
                                 <th class="fw-bold py-3 w__5">Icon</th>
                                 <th class="fw-bold py-3 w__5"></th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="feature in selectedSubServiceFeatures">
                                 <td class="py-3 px-2" style="line-height: 0.2;">{{ feature.title }}</td>
                                 <td class="py-3 px-2 text-center" style="line-height: 0.2;">
                                    <i class="mdi fs-3" :class="`${feature.icon}`"></i>
                                 </td>
                                 <td class="py-3 px-2">
                                    <div class="dropdown">
                                       <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                          <i class="bx bx-dots-vertical"></i>
                                       </button>
                                       <div class="dropdown-menu dropdown-menu-end">
                                          <button type="button" class="dropdown-item" @click.prevent="editSubServiceFeature(feature)">
                                             <i class="bx bx-edit-alt icon-20px me-2"></i>Edit
                                          </button>
                                          <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSubServiceFeature(feature)">
                                             <i class="bx bx-trash icon-20px me-2"></i> Delete
                                          </button>
                                       </div>
                                    </div>
                                 </td>
                              </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-12">
                     <div class="mt-10">
                        <button type="button" class="btn btn-light" @click.prevent="closeSubServiceFeatureModal(subServiceFeatureForm.sub_service_id)"
                                :disabled="subServiceFeatureForm.processing">
                           Close Modal
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      
      <!-- New Service SEO Modal -->
      <div class="modal fade" id="create-service-seo-modal" data-bs-backdrop="static" tabindex="-1"
           aria-labelledby="create-service-seo-modal-label" aria-hidden="true" ref="createServiceSeoModal"
      >
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="create-service-seo-modal-label">Manage SEO</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                          @click.prevent="serviceSeoFormCleanUp"></button>
               </div>
               <div class="modal-body">
                  <form id="createForm" @submit.prevent="storeServiceSeo">
                     <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" type="text" v-model="serviceSeoForm.title" class="form-control">
                        <div v-if="serviceSeoForm.errors.title" class="text-danger">{{ serviceSeoForm.errors.title }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="description" class="form-label-md mb-2">Description</label>
                        <textarea id="description" rows="3" v-model="serviceSeoForm.description" class="form-control"></textarea>
                        <div v-if="serviceSeoForm.errors.description" class="text-danger">{{ serviceSeoForm.errors.description }}</div>
                     </div>
                     <div class="mb-3">
                        <label for="keywords" class="form-label-md mb-1">Keywords</label>
                        <div class="mb-2 small text-muted">Each keyword should be separated with a commas</div>
                        <input id="keywords" type="text" v-model="serviceSeoForm.keywords" class="form-control" placeholder="e.g: website, school">
                        <div v-if="serviceSeoForm.errors.keywords" class="text-danger">{{ serviceSeoForm.errors.keywords }}</div>
                     </div>
                  </form>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="serviceSeoFormCleanUp"
                          :disabled="serviceSeoForm.processing">
                     Close
                  </button>
                  
                  <button type="button" class="btn btn-primary" @click.prevent="storeServiceSeo" :disabled="serviceSeoForm.processing">
                     {{ serviceSeoForm.processing ? 'Processing...' : 'Submit' }}
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import {router, useForm} from "@inertiajs/vue3";
import { Modal } from 'bootstrap';
import _debounce from "lodash/debounce.js";
import Editor from '@/Components/global/Editor.vue'
import Dropzone from "@components/global/Dropzone.vue";
import axios from "axios";

export default {
   components: {
      Dropzone,
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
            slug: null,
            details: null,
            short_description: null,
            media: null,
            active: true,
         }),
         editForm: useForm({
            id: null,
            service_id: null,
            title: null,
            slug: null,
            details: null,
            short_description: null,
            media: null,
            active: null,
         }),
         galleryForm: useForm({
            owner_type: 'App\\Models\\Service',
            owner_id: null,
            service_id: null,
            collection: 'service-gallery',
            file: null,
            files: [],
         }),
         serviceFeatureForm: useForm({
            id: null,
            service_id: '',
            title: '',
            icon: '',
            details: '',
         }),
         subServiceForm: useForm({
            id: null,
            service_id: null,
            title: null,
            details: null,
            has_image: false,
            media: null,
         }),
         subServiceFeatureForm: useForm({
            id: null,
            sub_service_id: '',
            title: '',
            icon: '',
            details: '',
         }),
         serviceSeoForm: useForm({
            service_id: '',
            title: null,
            description: null,
            keywords: null,
         }),
         icons: [
            {value: 'mdi-lightbulb-on-outline', name: 'Lightbulb On'},
            {value: 'mdi-bullseye', name: 'Bullseye'},
            {value: 'mdi-bullseye-arrow', name: 'Bullseye Arrow'},
            {value: 'mdi-diamond-stone', name: 'Diamond Stone'},
            {value: 'mdi-handshake', name: 'Handshake Icon'},
            {value: 'mdi-shield-check', name: 'Shield Check Icon'},
            {value: 'mdi-trophy-award', name: 'Trophy Award Icon'},
            {value: 'mdi-wordpress', name: 'WordPress'},
            {value: 'mdi-cart', name: 'Cart'},
            {value: 'mdi-email', name: 'Mail'},
            {value: 'mdi-email-open-outline', name: 'Mail Open Outline'},
            {value: 'mdi-email-newsletter', name: 'Mail Newsletter'},
            {value: 'mdi-face-agent', name: 'Customer Care'},
            {value: 'mdi-send', name: 'Send'},
            {value: 'mdi-play-circle-outline', name: 'Play Circle Outline'},
            {value: 'mdi-google-circles-extended', name: 'Circles Extended'},
            {value: 'mdi-timer-outline', name: 'Timer Outline'},
            {value: 'mdi-finance', name: 'Chart Finance'},
            {value: 'mdi-advertisements', name: 'Advertisements'},
            {value: 'mdi-seal', name: 'Seal Icon'},
            {value: 'mdi-earth', name: 'Earth Icon'},
            {value: 'mdi-account-heart', name: 'Heart'},
            {value: 'mdi-clock-check', name: 'Clock Check'},
            {value: 'mdi-hard-hat', name: 'Hat'},
            {value: 'mdi-leaf', name: 'Leaf'},
            {value: 'mdi-speedometer', name: 'Speedometer Icon'},
            {value: 'mdi-account-group', name: 'Account Group Icon'},
            {value: 'mdi-tree', name: 'Tree Icon'},
            {value: 'mdi-chart-line', name: 'Chart Line Icon'},
            {value: 'mdi-home-outline', name: 'Home'},
            {value: 'mdi-map-marker', name: 'Location'},
            {value: 'mdi-security', name: 'Security'},
            {value: 'mdi-shield-check', name: 'Security Check'},
            {value: 'mdi-lightbulb-on-outline', name: 'Lighting'},
            {value: 'mdi-lamp-outline', name: 'Table Lamp'},
            {value: 'mdi-palm-tree', name: 'Resort Area'},
            {value: 'mdi-briefcase-outline', name: 'Work Area'},
            {value: 'mdi-printer', name: 'Printer'},
            {value: 'mdi-laptop', name: 'Laptop'},
            {value: 'mdi-laptop-account', name: 'Laptop Account'},
            {value: 'mdi-laptop-off', name: 'Laptop Off'},
            {value: 'mdi-account-multiple', name: 'Meeting Room'},
            {value: 'mdi-clock-outline', name: '24h Service'},
            {value: 'mdi-truck-delivery', name: 'Delivery'},
            {value: 'mdi-trophy-outline', name: 'Award'},
         ],
         selectedService: [],
         selectedMedia: [],
         
         selectedServiceFeatures: [],
         isEditingServiceFeature: false,
         serviceFeatureModalIsOpen: false,
         
         isEditingSubService: false,
         selectedSubServices: [],
         
         selectedSubServiceFeatures: [],
         isEditingSubServiceFeature: false,
         subServiceFeatureModalIsOpen: false,
      };
   },
   methods: {
      fetchFeatures(serviceId) {
         axios.get(route('datatable.service-features'), {
            params: {
               filter: {
                  'service_id': serviceId,
               }
            }
         })
            .then(({data}) => {
               this.selectedServiceFeatures = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the service features!", "Error");
         })
      },
      fetchSubServices(serviceId) {
         axios.get(route('datatable.sub-services'), {
            params: {
               filter: {
                  'service_id': serviceId,
               }
            }
         })
            .then(({data}) => {
               this.selectedSubServices = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the sub-service!", "Error");
         })
      },
      fetchSubServiceFeatures(subServiceId) {
         axios.get(route('datatable.sub-service-features'), {
            params: {
               filter: {
                  'sub_service_id': subServiceId,
               }
            }
         })
            .then(({data}) => {
               this.selectedSubServiceFeatures = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the sub-service features!", "Error");
         })
      },
      handleMediaUpload(fileObject) {
         if (Array.isArray(fileObject)) {
            this.form.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.form.media = fileObject.file ?? null
         }
      },
      handleSubServiceMediaUpload(fileObject) {
         if (this.isEditingSubService) {
            return;
            
         } else {
            if (Array.isArray(fileObject)) {
               this.subServiceForm.media = fileObject.length > 0 ? fileObject[0].file : null
            } else {
               this.subServiceForm.media = fileObject.file ?? null
            }
         }
      },
      showCreateServiceModal() {
         const modalElement = this.$refs.createServiceModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createService() {
         this.form.post(route('admin.components.services.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.$refs.servicesTable.reloadTable();
               const modalElement = this.$refs.createServiceModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Service Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editService(rowData) {
         this.editForm.id = rowData.hashid;
         this.editForm.service_id = rowData.id;
         this.editForm.title = rowData.title;
         this.editForm.slug = rowData.slug;
         this.editForm.details = rowData.details;
         this.editForm.short_description = rowData.short_description;
         this.editForm.active = rowData.active;
         
         const file = rowData.media?.find(md => md.collection_name === 'service-image') ?? null;
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
         
         const modalElement = this.$refs.editServiceModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateService() {
         this.editForm.patch(route('admin.components.services.update', this.editForm.id), {
            onSuccess: () => {
               this.editForm.reset();
               this.editForm.clearErrors();
               this.$refs.servicesTable.reloadTable();
               const modalElement = this.$refs.editServiceModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.$toast.success('Service Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      openServiceGalleryModal(service) {
         this.galleryForm.morph_id = service.id;
         this.galleryForm.service_id = service.id;
         const images = service.media.filter(m => m.collection_name === 'service-gallery')
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
         
         const modalElement = this.$refs.serviceMediaUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      handlePreviewRemoved(previewObject) {
         // console.log("Preview object received:", previewObject);
         let url = previewObject;
         if (typeof previewObject === 'object' && previewObject.src) {
            url = previewObject.src;
         }
         
         // console.log("Extracted URL:", url);
         const matchingImage = this.galleryForm.files.find(img => img.url === url);
         
         // console.log("Matching image found:", matchingImage);
         if (matchingImage && matchingImage.id) {
            router.delete(route('admin.medias.service.media.destroy', {
               service: matchingImage.model_id,
               media: matchingImage.id
            }), {
               preserveScroll: true,
               onSuccess: () => {
                  const index = this.galleryForm.files.findIndex(img => img.id === matchingImage.id);
                  if (index > -1) {
                     this.galleryForm.files.splice(index, 1);
                  }
                  this.$toast.success('Image deleted successfully', 'Success');
               },
               onError: (error) => {
                  console.log("Failed to delete image:", error);
                  this.$toast.error('Failed to delete image', 'Error');
               }
            });
         } else {
            console.error("Could not find matching image ID for URL:", url);
         }
      },
      openServiceFeatureModal(rowData) {
         this.serviceFeatureForm.service_id = rowData.id
         this.fetchFeatures(rowData.id)
         const modalElement = this.$refs.serviceFeatureModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      truncate(value, length) {
         if (value && value.length > length) {
            return value.substring(0, length) + "...";
         } else {
            return value;
         }
      },
      storeServiceFeature() {
         const serviceId = this.serviceFeatureForm.service_id;
         this.serviceFeatureForm.post(route('admin.service-features.store'), {
            onSuccess: () => {
               this.serviceFeatureFormCleanUp();
               this.$refs.servicesTable.reloadTable();
               // const modalElement = this.$refs.serviceFeatureModal;
               // const modalInstance = Modal.getInstance(modalElement);
               // modalInstance.hide();
               this.fetchFeatures(serviceId);
               this.$toast.success('Service Feature Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editServiceFeature(feature) {
         this.isEditingServiceFeature = true;
         
         this.serviceFeatureForm.id = feature.id;
         this.serviceFeatureForm.service_id = feature.service_id;
         this.serviceFeatureForm.title = feature.title;
         this.serviceFeatureForm.icon = feature.icon;
         this.serviceFeatureForm.details = feature.details;
      },
      closeEditingFeature() {
         this.serviceFeatureFormCleanUp();
         this.isEditingServiceFeature = false;
      },
      updateServiceFeature() {
         const serviceId = this.serviceFeatureForm.service_id;
         this.serviceFeatureForm.patch(route('admin.service-features.update', this.serviceFeatureForm.id), {
            onSuccess: () => {
               this.serviceFeatureFormCleanUp();
               this.serviceFeatureForm.service_id = serviceId;
               this.$refs.servicesTable.reloadTable();
               this.isEditingServiceFeature = false;
               this.fetchFeatures(serviceId);
               this.$toast.success('Service Feature Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteServiceFeature(feature) {
         const serviceId = feature.service_id;
         this.$toast.question(`Are you sure?`, 'Delete This Service Feature!').then(() => {
            this.$inertia.delete(route('admin.service-features.destroy', feature.hashid), {
               onSuccess: () => {
                  setTimeout(() => {
                     this.fetchFeatures(serviceId);
                     this.$toast.success('Service feature deleted successfully!', 'Success');
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the service feature!', 'Error');
               }
            })
         });
      },
      closeServiceFeatureModal() {
         this.isEditingServiceFeature = false;
         this.selectedServiceFeatures = [];
         this.serviceFeatureFormCleanUp();
         const modalElement = this.$refs.serviceFeatureModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.hide();
      },
      openSubServiceModal(rowData) {
         this.subServiceForm.service_id = rowData.id
         this.fetchSubServices(rowData.id)
         const modalElement = this.$refs.subServiceModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storeSubService() {
         const serviceId = this.subServiceForm.service_id;
         this.subServiceForm.post(route('admin.sub-services.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.subServiceFormCleanUp();
               this.subServiceForm.service_id = serviceId;
               this.$refs.servicesTable.reloadTable();
               this.fetchSubServices(serviceId);
               this.$toast.success('Service Feature Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editSubService(service) {
         this.isEditingSubService = true;
         
         this.subServiceForm.id = service.id;
         this.subServiceForm.service_id = service.service_id;
         this.subServiceForm.title = service.title;
         this.subServiceForm.details = service.details;
         this.subServiceForm.has_image = service.has_image;
         
         const file = service.media?.find(md => md.collection_name === 'sub-service-image') ?? null;
         if (file) {
            this.subServiceForm.media = [{
               id: file.model_id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true, // flag so you know it’s already uploaded
            }];
         } else {
            this.subServiceForm.media = null;
         }
      },
      closeEditingSubService(serviceId) {
         this.subServiceFormCleanUp();
         this.isEditingSubService = false;
         this.subServiceForm.service_id = serviceId
      },
      updateSubService() {
         const serviceId = this.subServiceForm.service_id;
         this.subServiceForm.patch(route('admin.sub-services.update', this.subServiceForm.id), {
            forceFormData: true,
            onSuccess: () => {
               this.subServiceFormCleanUp();
               this.subServiceForm.service_id = serviceId
               this.$refs.servicesTable.reloadTable();
               this.isEditingSubService = false;
               this.fetchSubServices(serviceId);
               this.$toast.success('Sub-Service Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteSubService(service) {
         const serviceId = service.service_id;
         this.$toast.question(`Are you sure?`, 'Delete This Service Feature!').then(() => {
            this.$inertia.delete(route('admin.sub-services.destroy', service.hashid), {
               onSuccess: () => {
                  setTimeout(() => {
                     this.fetchSubServices(serviceId);
                     this.$toast.success('Sub-Service deleted successfully!', 'Success');
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the service feature!', 'Error');
               }
            })
         });
      },
      closeSubServiceModal() {
         this.isEditingSubService = false;
         this.selectedSubServices = [];
         this.subServiceFormCleanUp();
         const modalElement = this.$refs.subServiceModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.hide();
      },
      
      openSubServiceFeatureModal(subService) {
         this.subServiceFeatureForm.sub_service_id = subService.id
         this.fetchSubServiceFeatures(subService.id)
         
         const subServiceModalEl = this.$refs.subServiceModal;
         const subServiceModal = Modal.getOrCreateInstance(subServiceModalEl);
         
         subServiceModalEl.addEventListener('hidden.bs.modal', () => {
            const featureModalEl = this.$refs.subServiceFeatureModal;
            const featureModal = Modal.getOrCreateInstance(featureModalEl);
            featureModal.show();
         }, { once: true });
         
         subServiceModal.hide();
      },
      storeSubServiceFeature() {
         const subServiceId = this.subServiceFeatureForm.sub_service_id;
         this.subServiceFeatureForm.post(route('admin.service-features.store'), {
            onSuccess: () => {
               this.subServiceFeatureFormCleanUp();
               this.$refs.servicesTable.reloadTable();
               this.fetchSubServiceFeatures(subServiceId);
               this.$toast.success('Sub-Service Feature Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editSubServiceFeature(feature) {
         this.isEditingSubServiceFeature = true;
         
         this.subServiceFeatureForm.id = feature.id;
         this.subServiceFeatureForm.service_id = feature.service_id;
         this.subServiceFeatureForm.title = feature.title;
         this.subServiceFeatureForm.icon = feature.icon;
         this.subServiceFeatureForm.details = feature.details;
      },
      closeEditingSubServiceFeature() {
         this.subServiceFeatureFormCleanUp();
         this.isEditingSubServiceFeature = false;
      },
      updateSubServiceFeature() {
         const subServiceId = this.serviceFeatureForm.sub_service_id;
         this.subServiceFeatureForm.patch(route('admin.sub-service-features.update', this.subServiceFeatureForm.id), {
            onSuccess: () => {
               this.serviceFeatureFormCleanUp();
               this.subServiceFeatureForm.service_id = serviceId;
               this.$refs.servicesTable.reloadTable();
               this.isEditingSubServiceFeature = false;
               this.fetchSubServiceFeatures(subServiceId);
               this.$toast.success('Sub-Service Feature Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deleteSubServiceFeature(feature) {
         const subServiceId = feature.sub_service_id;
         this.$toast.question(`Are you sure?`, 'Delete This Sub-Service Feature!').then(() => {
            this.$inertia.delete(route('admin.sub-service-features.destroy', feature.hashid), {
               onSuccess: () => {
                  setTimeout(() => {
                     this.fetchFeatures(subServiceId);
                     this.$toast.success('Sub-Service feature deleted successfully!', 'Success');
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred. Please try again!', 'Error');
               }
            })
         });
      },
      closeSubServiceFeatureModal(serviceId) {
         this.isEditingSubServiceFeature = false;
         this.selectedSubServiceFeatures = [];
         this.subServiceFeatureFormCleanUp();
         
         const featureModalEl = this.$refs.subServiceFeatureModal;
         const featureModal = Modal.getOrCreateInstance(featureModalEl);
         
         featureModalEl.addEventListener('hidden.bs.modal', () => {
            this.subServiceForm.service_id = serviceId;
            this.fetchSubServices(serviceId);
            
            const subServiceModalEl = this.$refs.subServiceModal;
            const subServiceModal = Modal.getOrCreateInstance(subServiceModalEl);
            subServiceModal.show();
         }, { once: true });
         
         featureModal.hide();
      },
      manageServiceSEO(rowData) {
         if(rowData && rowData.meta_seo) {
            this.serviceSeoForm.title = rowData.meta_seo.title;
            this.serviceSeoForm.description = rowData.meta_seo.description;
            this.serviceSeoForm.keywords = rowData.meta_seo.keywords;
         } else {
            this.serviceSeoForm.reset();
         }
         
         this.serviceSeoForm.service_id = rowData.hashid;
         
         const modalElement = this.$refs.createServiceSeoModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storeServiceSeo() {
         this.serviceSeoForm.post(route('admin.services.meta-seo', this.serviceSeoForm.service_id), {
            onSuccess: () => {
               this.serviceSeoForm.reset();
               this.serviceSeoForm.clearErrors();
               
               this.$refs.servicesTable.reloadTable();
               
               const modalElement = this.$refs.createServiceSeoModal;
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
      deleteService(service) {
         this.$toast.question('Are you sure?', `Deleting ${service.title}`).then(() => {
            this.$inertia.delete(route('admin.components.services.destroy', service.id), {
               onSuccess: () => {
                  this.$toast.success('Service deleted', 'Success');
                  this.$refs.servicesTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the service!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.servicesTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
      },
      editFormCleanUp() {
         this.editForm.reset();
         this.editForm.clearErrors();
         this.editForm.media = null
      },
      serviceFeatureFormCleanUp() {
         this.serviceFeatureForm.reset();
         this.serviceFeatureForm.clearErrors();
      },
      subServiceFormCleanUp() {
         this.subServiceForm.reset();
         this.subServiceForm.clearErrors();
      },
      subServiceFeatureFormCleanUp() {
         this.subServiceFeatureForm.reset();
         this.subServiceFeatureForm.clearErrors();
      }
   },
}
</script>

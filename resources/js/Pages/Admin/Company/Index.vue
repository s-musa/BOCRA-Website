<template>
   <Head title="Company"/>
   
   <DefaultLayout>
      <div class="">
         <h4 class="mb-0">Company Profile</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Company Profile
               </li>
            </ol>
         </nav>
         
         <div v-if="!company">
            <div class="d-flex justify-content-center align-content-center">
               <div class="flex flex-column items-center justify-center">
                  <div class="flex flex-column items-center justify-center">
                     <i class="bx bxs-school" style="font-size: 90px;"></i>
                  </div>
                  <div class="mt-2">
                     <label class="font-medium">
                        No company registered!
                     </label>
                  </div>
                  <div class="mt-2 text-center">
                     <label class="text-gray-500">
                        This page will provide details of your company once you register it to the system.
                     </label>
                  </div>
                  <div class="mt-6">
                     <Link :href="route('admin.companies.create')" class="btn btn-outline-primary">
                        <i class="bx bx-plus-circle me-2"></i>
                        Register Company
                     </Link>
                  </div>
               </div>
            </div>
         </div>
         <div v-else>
            <div class="row">
               <div class="col-xl-10 me-auto">
                  <div class="card">
                     <div class="card-header d-inline-block justify-content-between align-items-center">
                        <h5 class="mb-0">Company Info</h5>
                        <small class="text-muted">
                           Information about your company that will be displayed on forms and other documents created by
                           the App.
                        </small>
                     </div>
                     <div class="card-body">
                        <!-- Profile Edit Form -->
                        <div>
                           <div class="row">
                              <div v-if="logo.id" class="col-md-4 col-12 mb-4">
                                 <p class="text-muted mb-2 d-md-flex d-block">
                                    Company Logo
                                    <span class="form-check form-switch ms-auto py-md-0 py-3">
                                       <input v-model="form.has_footer_logo" class="form-check-input" type="checkbox" id="hasCtaButtons">
                                       <label class="form-check-label" for="hasCtaButtons"> Use different footer logo </label>
                                    </span>
                                 </p>
                                 <div class="card card-img p-5 bg-secondary-subtle">
                                    <img :src="logo.url" alt="logo" style="max-width:100px; height:auto;">
                                 </div>
                                 <button type="button" class="btn btn-sm btn-outline-primary my-3" @click.prevent="showLogoUploadModal(logo)">
                                    Change
                                 </button>
                                 <button type="button" class="btn btn-sm btn-outline-danger my-3 ms-3" @click.prevent="deleteLogo" :disabled="isProcessing">
                                    Delete
                                 </button>
                              </div>
                              <div v-else class="col-md-4 col-12">
                                 <div class="mb-6 form-group">
                                    <label class="form-label" for="companyLogo">Logo</label>
                                    <input @change="handleLogoUpload" id="companyLogo" type="file" accept="image/*"
                                           required class="form-control mb-3"
                                    />
                                    <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                                       Upload
                                    </button>
                                 </div>
                              </div>
                              <div v-if="form.has_footer_logo" class="col-md-4 col-12 mb-4">
                                 <div v-if="footerLogo.id" class="">
                                    <p class="text-muted">Footer Logo</p>
                                    <div class="card card-img p-5 bg-secondary-subtle">
                                       <img :src="footerLogo.url" alt="logo" style="width:100px; height:auto;">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary my-3" @click.prevent="showFooterLogoUploadModal(footerLogo)">
                                       Change
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger my-3 ms-3" @click.prevent="deleteFooterLogo" :disabled="isProcessing">
                                       Delete
                                    </button>
                                 </div>
                                 <div v-else class="">
                                    <div class="mb-6 form-group">
                                       <label class="form-label" for="companyLogo">Footer Logo</label>
                                       <input @change="handleFooterLogoUpload" id="companyFooterLogo" type="file"
                                              accept="image/*" required class="form-control mb-3"
                                       />
                                       <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                                          Upload
                                       </button>
                                    </div>
                                 </div>
                              </div>
                              <div v-if="favicon.id" class="col-md-3 col-12 mb-4">
                                 <p class="text-muted">Company Favicon</p>
                                 <div class="card card-img p-5 bg-secondary-subtle">
                                    <img :src="favicon.url" alt="logo" style="width:80px; height:auto;">
                                 </div>
                                 <button type="button" class="btn btn-sm btn-icon btn-outline-primary my-3" @click.prevent="showFaviconUploadModal(favicon)">
                                    <i class="bx bx-edit"></i>
                                 </button>
                                 <button type="button" class="btn btn-sm btn-icon btn-outline-danger my-3 ms-3" @click.prevent="deleteFavicon()" :disabled="isProcessing">
                                    <i class="bx bx-trash"></i>
                                 </button>
                              </div>
                              <div v-else class="col-md-3 col-12">
                                 <div class="mb-6 form-group">
                                    <label class="form-label" for="companyFavicon">Favicon</label>
                                    <input @change="handleFaviconUpload" id="companyFavicon" type="file"
                                       accept="image/*" required class="form-control mb-3"
                                    />
                                    <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                                       Upload
                                    </button>
                                 </div>
                              </div>
                              <div class="divider">
                                 <div class="divider-text">GENERAL INFORMATION</div>
                              </div>
                              <div class="col-md-12">
                                 <div class="mb-6 form-group">
                                    <label class="form-label" for="companyName">Company Name</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-buildings"></i>
                                       </span>
                                       <input v-model="form.name" id="companyName" type="text" class="form-control"
                                          placeholder="Company Name"/>
                                    </div>
                                    <div v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label class="form-label" for="secondaryEmail">Email</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-envelope"></i>
                                       </span>
                                       <input v-model="form.email" id="secondaryEmail" type="email" class="form-control"
                                          placeholder="info@company.com"/>
                                    </div>
                                    <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label class="form-label" for="email">Secondary Email</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-envelope"></i>
                                       </span>
                                       <input v-model="form.secondary_email" id="email" type="email" class="form-control"
                                              placeholder="info@company.com"/>
                                    </div>
                                    <div v-if="form.errors.secondary_email" class="text-danger">{{ form.errors.secondary_email }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="primaryPhone" class="form-label">Phone Number</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-phone"></i>
                                       </span>
                                       <input v-model="form.primary_phone" id="primaryPhone" type="text"
                                              class="form-control" placeholder="+254 7xxx xxx xxx"/>
                                    </div>
                                    <div v-if="form.errors.primary_phone" class="text-danger">{{ form.errors.primary_phone }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="secondaryPhone" class="form-label">Secondary Phone Number</label>
                                    <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                       <i class="bx bx-phone"></i>
                                    </span>
                                       <input v-model="form.secondary_phone" id="secondaryPhone" type="text"
                                              class="form-control" placeholder="+254 7xxx xxx xxx"/>
                                    </div>
                                    <div v-if="form.errors.secondary_phone" class="text-danger">{{ form.errors.secondary_phone }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="primaryWhatsapp" class="form-label">Whatsapp Number</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bxl-whatsapp"></i>
                                       </span>
                                       <input v-model="form.primary_whatsapp" id="primaryWhatsapp" type="text"
                                              class="form-control" placeholder="+254 7xxx xxx xxx"/>
                                    </div>
                                    <div v-if="form.errors.primary_whatsapp" class="text-danger">{{ form.errors.primary_whatsapp }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="secondaryWhatsapp" class="form-label">Secondary Whatsapp Number</label>
                                    <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                       <i class="bx bxl-whatsapp"></i>
                                    </span>
                                       <input v-model="form.secondary_whatsapp" id="secondaryWhatsapp" type="text"
                                              class="form-control" placeholder="+254 7xxx xxx xxx"/>
                                    </div>
                                    <div v-if="form.errors.secondary_whatsapp" class="text-danger">{{ form.errors.secondary_whatsapp }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="website" class="form-label">Website</label>
                                    <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                       <i class="bx bx-phone"></i>
                                    </span>
                                       <input v-model="form.website" id="website" type="text" class="form-control"
                                              placeholder="www.website.com"/>
                                    </div>
                                    <div v-if="form.errors.website" class="text-danger">{{ form.errors.website }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="country" class="form-label">Country</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-world"></i>
                                       </span>
                                       <input v-model="form.country" id="country" type="text" class="form-control"
                                              placeholder="Kenya"/>
                                    </div>
                                    <div v-if="form.errors.country" class="text-danger">{{ form.errors.country }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="state" class="form-label">State</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bxs-flag-alt"></i>
                                       </span>
                                       <input v-model="form.state" id="state" type="text" class="form-control"
                                              placeholder="Mombasa"/>
                                    </div>
                                    <div v-if="form.errors.state" class="text-danger">{{ form.errors.state }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6 form-group">
                                    <label for="city" class="form-label">City</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bxs-city"></i>
                                       </span>
                                       <input v-model="form.city" id="city" type="text" class="form-control"
                                              placeholder="Mombasa"/>
                                    </div>
                                    <div v-if="form.errors.city" class="text-danger">{{ form.errors.city }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="physical_address" class="form-label">Address</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bxs-location-plus"></i>
                                       </span>
                                       <input v-model="form.physical_address" id="physical_address" type="text"
                                              class="form-control" placeholder="Bishop Makarios Opposite Ganjoni Primary, Mombasa"/>
                                    </div>
                                 </div>
                                 <div v-if="form.errors.physical_address" class="text-danger">
                                    {{ form.errors.physical_address }}
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-box"></i>
                                       </span>
                                       <input v-model="form.postal_address" id="postal_address" type="text"
                                              class="form-control" placeholder="0001-8888"/>
                                    </div>
                                    <div v-if="form.errors.postal_address" class="text-danger">
                                       {{ form.errors.postal_address }}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="zip" class="form-label">Tax Identification Number</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class="bx bx-file"></i>
                                       </span>
                                       <input v-model="form.tax_identification_pin" id="tax_identification_number"
                                              type="text" class="form-control" placeholder="A0000001P"/>
                                    </div>
                                    <div v-if="form.errors.tax_identification_pin" class="text-danger">
                                       {{ form.errors.tax_identification_pin }}
                                    </div>
                                 </div>
                              </div>
                              <div class="divider">
                                 <div class="divider-text">SOCIAL MEDIA HANDLES</div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="twitter" class="form-label">Twitter / X</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-twitter'></i>
                                       </span>
                                       <input v-model="form.x_profile" id="twitter" type="text" class="form-control"
                                              placeholder="x.com"/>
                                    </div>
                                    <div v-if="form.errors.x_profile" class="text-danger">{{ form.errors.x_profile }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-facebook'></i>
                                       </span>
                                       <input v-model="form.fb_profile" id="facebook" type="text" class="form-control"
                                              placeholder="facebook.com"/>
                                    </div>
                                    <div v-if="form.errors.fb_profile" class="text-danger">
                                       {{ form.errors.fb_profile }}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-instagram-alt'></i>
                                       </span>
                                       <input v-model="form.ig_profile" id="instagram" type="text" class="form-control"
                                              placeholder="instagram.com"/>
                                    </div>
                                    <div v-if="form.errors.ig_profile" class="text-danger">{{ form.errors.ig_profile }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="linkedin" class="form-label">Linkedin</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-linkedin'></i>
                                       </span>
                                       <input v-model="form.linkedin_profile" id="linkedin" type="text" class="form-control"
                                              placeholder="instagram.com"/>
                                    </div>
                                    <div v-if="form.errors.linkedin_profile" class="text-danger">{{ form.errors.linkedin_profile }}</div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="tiktok" class="form-label">Tiktok</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-tiktok'></i>
                                       </span>
                                       <input v-model="form.tiktok_profile" id="tiktok" type="text" class="form-control"
                                              placeholder="tiktok.com"/>
                                    </div>
                                    <div v-if="form.errors.tiktok_profile" class="text-danger">
                                       {{ form.errors.tiktok_profile }}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="youtube" class="form-label">Youtube</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-youtube'></i>
                                       </span>
                                       <input v-model="form.youtube_profile" id="youtube" type="text" class="form-control"
                                              placeholder="youtube.com"/>
                                    </div>
                                    <div v-if="form.errors.youtube_profile" class="text-danger">
                                       {{ form.errors.youtube_profile }}
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-6">
                                    <label for="youtube" class="form-label">Pinterest</label>
                                    <div class="input-group input-group-merge">
                                       <span class="input-group-text">
                                          <i class='bx bxl-pinterest'></i>
                                       </span>
                                       <input v-model="form.pinterest_profile" id="pinterest" type="text" class="form-control"
                                              placeholder="pinterest.com"/>
                                    </div>
                                    <div v-if="form.errors.pinterest_profile" class="text-danger">
                                       {{ form.errors.pinterest_profile }}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <button type="button" class="btn btn-primary" @click.prevent="updateDetails" :disabled="form.processing">
                              Update
                           </button>
                           <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="my-3 mx-0">
                              {{ form.progress.percentage }}%
                           </progress>
                        </div>
                        <!-- End Profile Edit Form -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Logo Upload Modal -->
         <div
            class="modal fade"
            id="logo-upload-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="logo-upload-modal-label"
            aria-hidden="true"
            ref="companyLogoUploadModal"
         >
            <div class="modal-dialog modal-body-simple">
               <div class="modal-content">
                  <div class="modal-header pb-5">
                     <h5 class="modal-title" id="logo-upload-modal-label">Upload Logo</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                     ></button>
                  </div>
                  
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="uploadMedia">
                        <div class="mb-3">
                           <h6>Current Logo</h6>
                           <div class="card card-img p-5">
                              <img :src="selectedMedia.url" alt="logo" style="width:250px; height:auto;">
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="title" class="form-label">New Logo</label>
                           <input
                              @change="handleLogoUpload"
                              id="companyLogo"
                              type="file"
                              accept="image/*"
                              required
                              class="form-control mb-3"
                           />
                        </div>
                        <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                           Upload Logo
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Footer Logo Modal -->
         <div
            class="modal fade"
            id="logo-upload-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="logo-upload-modal-label"
            aria-hidden="true"
            ref="companyFooterLogoUploadModal"
         >
            <div class="modal-dialog modal-body-simple">
               <div class="modal-content">
                  <div class="modal-header pb-5">
                     <h5 class="modal-title" id="logo-upload-modal-label">Upload Footer Logo</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                     ></button>
                  </div>
                  
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="uploadMedia">
                        <div class="mb-3">
                           <h6>Current Footer Logo</h6>
                           <div class="card card-img p-5">
                              <img :src="selectedMedia.url" alt="logo" style="width:250px; height:auto;">
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="title" class="form-label">New Footer Logo</label>
                           <input
                              @change="handleFooterLogoUpload"
                              id="companyFooterLogo"
                              type="file"
                              accept="image/*"
                              required
                              class="form-control mb-3"
                           />
                        </div>
                        <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                           Upload Logo
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Favicon Upload Modal -->
         <div
            class="modal fade"
            id="favicon-upload-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="favicon-upload-modal-label"
            aria-hidden="true"
            ref="companyFaviconUploadModal"
         >
            <div class="modal-dialog modal-body-simple">
               <div class="modal-content">
                  <div class="modal-header pb-5">
                     <h5 class="modal-title" id="logo-upload-modal-label">Upload Favicon</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                     ></button>
                  </div>
                  
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="uploadMedia">
                        <div class="mb-3">
                           <h6>Current Favicon</h6>
                           <div class="card card-img p-5">
                              <img :src="selectedMedia.url" alt="favicon" style="width:80px; height:auto;">
                           </div>
                        </div>
                        <div class="mb-3">
                           <label for="title" class="form-label">New Favicon</label>
                           <input
                              @change="handleFaviconUpload"
                              id="companyFavicon"
                              type="file"
                              accept="image/*"
                              required
                              class="form-control mb-3"
                           />
                        </div>
                        <button type="button" class="btn btn-success" @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                           Upload Favicon
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>
</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import {Modal} from "bootstrap";

export default {
   components: { DefaultLayout, Head, Link },
   data() {
      return {
         mediaForm: useForm({
            company_id: '',
            logo: '',
            footer_logo: '',
            favicon: '',
         }),
         form: useForm({
            id: null,
            name: null,
            email: null,
            secondary_email: null,
            primary_phone: null,
            secondary_phone: null,
            primary_whatsapp: null,
            secondary_whatsapp: null,
            website: null,
            country: null,
            state: null,
            city: null,
            physical_address: null,
            postal_address: null,
            tax_identification_pin: null,
            x_profile: null,
            fb_profile: null,
            ig_profile: null,
            linkedin_profile: null,
            tiktok_profile: null,
            youtube_profile: null,
            pinterest_profile: null,
            has_footer_logo: false,
         }),
         company: {},
         logo: {
            id: null,
            url: null
         },
         footerLogo: {
            id: null,
            url: null,
         },
         favicon: {
            id: null,
            url: null
         },
         selectedMedia: [],
         activeModal: null,
         isProcessing: false,
      };
   },
   created() {
      Inertia.on('navigate', (event) => {
         if (event.detail.page.url === '/wp-admin/companies') {
            this.companyDetails();
         }
      });
   },
   computed: {
      authUser() {
         return this.$page.props.auth.user;
      },
   },
   mounted() {
      this.companyDetails();
   },
   methods: {
      can(permission) {
         return this.authUser?.permissions?.includes(permission);
      },
      companyDetails() {
         axios.get('/datatable/companies')
            .then(({data}) => {
               this.company = data;
               if (this.company) {
                  this.populateForm();
                  const logo = this.company.media.find(m => m.collection_name === 'logo');
                  if (logo) {
                     this.logo.id = logo.id;
                     this.logo.url = logo.original_url;
                  }
                  const footerLogo = this.company.media.find(m => m.collection_name === 'footer-logo');
                  if (footerLogo) {
                     this.footerLogo.id = footerLogo.id;
                     this.footerLogo.url = footerLogo.original_url;
                  }
                  const favicon = this.company.media.find(m => m.collection_name === 'favicon');
                  if (favicon) {
                     this.favicon.id = favicon.id;
                     this.favicon.url = favicon.original_url;
                  }
               }
            }).catch((error) => {
            console.error(error)
            this.$toast.error('An error occurred when fetching the company details.')
         })
      },
      populateForm() {
         this.form.id = this.company.hashid || '';
         this.form.name = this.company.name || '';
         this.form.email = this.company.email || '';
         this.form.secondary_email = this.company.secondary_email || '';
         this.form.primary_phone = this.company.primary_phone || '';
         this.form.secondary_phone = this.company.secondary_phone || '';
         this.form.primary_whatsapp = this.company.primary_whatsapp || '';
         this.form.secondary_whatsapp = this.company.secondary_whatsapp || '';
         this.form.country = this.company.country || '';
         this.form.website = this.company.website || '';
         this.form.state = this.company.state || '';
         this.form.city = this.company.city || '';
         this.form.physical_address = this.company.physical_address || '';
         this.form.postal_address = this.company.postal_address || '';
         this.form.tax_identification_pin = this.company.tax_identification_pin || '';
         this.form.x_profile = this.company.x_profile || '';
         this.form.fb_profile = this.company.fb_profile || '';
         this.form.ig_profile = this.company.ig_profile || '';
         this.form.linkedin_profile = this.company.linkedin_profile || '';
         this.form.tiktok_profile = this.company.tiktok_profile || '';
         this.form.youtube_profile = this.company.youtube_profile || '';
         this.form.pinterest_profile = this.company.pinterest_profile || '';
         this.form.has_footer_logo = this.company.has_footer_logo || false;
      },
      updateDetails() {
         this.form.patch('/wp-admin/companies/' + this.company.hashid, {
            onSuccess: () => {
               this.form.clearErrors();
               this.$toast.success('Company details updated', 'Updated');
               this.companyDetails();
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         });
      },
      handleLogoUpload(event) {
         const file = event.target.files[0];
         if (file) {
            this.mediaForm.logo = file;
         }
      },
      handleFooterLogoUpload(event) {
         const file = event.target.files[0];
         if (file) {
            this.mediaForm.footer_logo = file;
         }
      },
      handleFaviconUpload(event) {
         const file = event.target.files[0];
         if (file) {
            this.mediaForm.favicon = file;
         }
      },
      uploadMedia() {
         this.mediaForm.company_id = this.company.id;
         
         this.mediaForm.post(route('admin.medias.company.media'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            onSuccess: () => {
               this.mediaForm.reset();
               this.mediaForm.clearErrors();
               this.$toast.success('Media uploaded', 'Updated');
               this.$inertia.visit(route('admin.companies.index'));
               
               let modalRef = null;
               
               if (this.activeModal === 'logo') {
                  modalRef = this.$refs.companyLogoUploadModal;
               } else if (this.activeModal === 'footer-logo') {
                  modalRef = this.$refs.companyFooterLogoUploadModal;
               } else if (this.activeModal === 'favicon') {
                  modalRef = this.$refs.companyFaviconUploadModal;
               }
               
               if (modalRef) {
                  const modalInstance = Modal.getInstance(modalRef);
                  modalInstance?.hide();
               }
               
               this.companyDetails();
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      showLogoUploadModal(media) {
         this.activeModal = 'logo';
         this.selectedMedia = media;
         const modalElement = this.$refs.companyLogoUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      showFooterLogoUploadModal(media) {
         this.activeModal = 'footer-logo';
         this.selectedMedia = media;
         const modalElement = this.$refs.companyFooterLogoUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      showFaviconUploadModal(media) {
         this.activeModal = 'favicon';
         this.selectedMedia = media;
         const modalElement = this.$refs.companyFaviconUploadModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      deleteLogo() {
         this.$toast.question('Are you sure?', 'Deleting company logo!').then(() => {
            this.$inertia.delete(route('admin.medias.delete.logo', this.company.id), {
               onProgress: () => {
                  this.isProcessing = true;
               },
               onSuccess: () => {
                  this.$toast.success('Logo deleted successfully!', 'Success');
                  this.$inertia.visit(route('admin.companies.index'));
                  this.isProcessing = false;
               },
               onError: (error) => {
                  console.log(error);
                  this.isProcessing = false;
                  this.$toast.error('An error occurred while deleting the logo!', 'Error');
               }
            })
         })
      },
      deleteFooterLogo() {
         this.$toast.question('Are you sure?', 'Deleting company logo!').then(() => {
            this.$inertia.delete(route('admin.medias.delete.footer-logo', this.company.id), {
               onProgress: () => {
                  this.isProcessing = true;
               },
               onSuccess: () => {
                  this.$toast.success('Footer Logo deleted successfully!', 'Success');
                  this.$inertia.visit(route('admin.companies.index'));
                  this.isProcessing = false;
               },
               onError: (error) => {
                  console.log(error);
                  this.isProcessing = false;
                  this.$toast.error('An error occurred while deleting the logo!', 'Error');
               }
            })
         })
      },
      deleteFavicon() {
         this.$toast.question('Are you sure?', 'Deleting company favicon!').then(() => {
            this.$inertia.delete(route('admin.medias.delete.favicon', this.company.id), {
               onSuccess: () => {
                  this.$toast.success('Favicon deleted successfully!', 'Success');
                  this.$inertia.visit(route('admin.companies.index'));
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the favicon!', 'Error');
               }
            })
         })
      }
   },
}
</script>

<style scoped>
.profile-con {
   width: 100px;
   height: 100px;
   display: flex;
}

/*--------------------------------------------------------------
# Profie Page
--------------------------------------------------------------*/
.profile .profile-card img {
   max-width: 120px;
}

.profile .profile-card h2 {
   font-size: 24px;
   font-weight: 700;
   color: #2c384e;
   margin: 10px 0 0 0;
}

.profile .profile-card h3 {
   font-size: 18px;
}

.profile .profile-card .social-links a {
   font-size: 20px;
   display: inline-block;
   color: rgba(1, 41, 112, 0.5);
   line-height: 0;
   margin-right: 10px;
   transition: 0.3s;
}

.profile .profile-card .social-links a:hover {
   color: #012970;
}

.profile .profile-overview .row {
   margin-bottom: 20px;
   font-size: 15px;
}

.profile .profile-overview .card-title {
   color: #012970;
}

.profile .profile-overview .label {
   font-weight: 600;
   color: rgba(1, 41, 112, 0.6);
}

.profile .profile-edit label {
   font-weight: 600;
   color: rgba(1, 41, 112, 0.6);
}

.profile .profile-edit img {
   max-width: 120px;
}
</style>

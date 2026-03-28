<template>
   <Head title="Website Pages"/>
   
   <DefaultLayout>
      <div class="row">
         <!--         <h4 class="mb-0">{{ page.title }} Page</h4>-->
         <!--         <nav class="mb-3">-->
         <!--            <ol class="breadcrumb">-->
         <!--               <li class="breadcrumb-item">-->
         <!--                  <Link :href="route('admin.dashboard')">Home</Link>-->
         <!--               </li>-->
         <!--               <li class="breadcrumb-item">-->
         <!--                  <Link :href="route('admin.pages.index')">Pages</Link>-->
         <!--               </li>-->
         <!--               <li class="breadcrumb-item text-primary">-->
         <!--                  {{ page.title }} Page-->
         <!--               </li>-->
         <!--            </ol>-->
         <!--         </nav>-->
         
         <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
            <div class="d-flex flex-column justify-content-center">
               <h4 class="mb-1">{{ page.title }} Page Sections</h4>
               <p class="mb-0">Manage the page sections that are being displayed on the website</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
               <Link :href="route('admin.pages.index')" class="btn btn-light">
                  Back
               </Link>
               <button type="button" class="btn btn-primary d-none d-sm-inline-block" @click.prevent="openAddSectionModal">
                  Add Section
               </button>
               <button type="button" class="btn btn-primary btn-icon d-sm-none" @click.prevent="openAddSectionModal">
                  <i class="bx bx-plus"></i>
               </button>
            </div>
         </div>
         
         <div class="col-xl-12 col-md-12">
            <div class="mb-3">
               <div class="accordion">
                  <draggable :list="pageSections" class="drag-area" @change="handleDraggableChange" >
                     <div v-for="(section, index) in pageSections" :key="index" class="accordion-item mb-5">
                        <h1 class="accordion-header border-bottom d-flex">
                           <button type="button" class="accordion-button pe-1"
                                   :class="{ collapsed: openAccordion !== `${section.id}` }" :key="section.id"
                                   @click="toggleAccordion(`${section.id}`, section)">
                              <i class="bx bx-move me-3 drag-button"></i>
                              <span class="me-3">Section {{ index + 1 }}</span>-
                              <span class="ms-3 text-muted">{{ section.section_type ? section.section_type.name : section.type }}</span>
                           </button>
                           <button class="btn align-text-top ps-0 pe-3" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical"></i>
                           </button>
                           <span class="dropdown-menu dropdown-menu-start p-0">
                              <a class="dropdown-item d-flex align-content-center align-middle" href="#" @click.prevent="openMoveSectionModal(section)">
                                 <i class="bx bx-sync me-2 fs-4"></i> <span>Move Section</span>
                              </a>
                           </span>
                        </h1>
                        <div class="accordion-collapse collapse" :class="{ show: openAccordion === `${section.id}` }">
                           <div class="accordion-body py-4">
                              <div class="row">
                                 <div class="col-lg-12 col-md-12 col-12 mb-4">
                                    <div class="row">
                                       <div class="col-md-4 col-12">
                                          <div class="mb-3">
                                             <label for="section_id" class="form-label-md mb-1">Section ID</label>
                                             <input v-model="editForm.spa_section_name" type="text" class="form-control" id="section_id" />
                                             <div class="form-text mb-2">This name will be used to identify the section you want to refer to in your page. The name will be included at the menu if this page is a landing page type.</div>
                                          </div>
                                       </div>
                                       <div class="col-md-4 col-12">
                                          <div class="mb-3 ">
                                             <label class="form-label-md mb-1">Section Width Style <span class="small text-muted text-wrap">(default width)</span></label>
                                             <v-select id="sectionWidthStyle" v-model="editForm.width_style" :options="sectionWidthStyles"
                                                       label="name" :reduce="option => option.value"
                                             />
                                          </div>
                                       </div>
                                       <div v-if="editForm.width_style === 'container-fluid'" class="col-md-4 col-12">
                                          <div class="form-group mb-3">
                                             <label class="form-label-md mb-1">Width</label>
                                             <input v-model="editForm.width" type="number" class="form-control">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionType" class="form-label-md mb-2 d-md-flex d-block">
                                          Section Type
                                          <span v-if="editForm.type === 'section-with-contact-form'" class="form-check form-switch ms-auto py-md-0 py-3">
                                             <input v-model="editForm.section_has_map" class="form-check-input" type="checkbox" id="includeMap">
                                             <label class="form-check-label" for="includeMap"> Include a map </label>
                                          </span>
                                       </label>
                                       <v-select id="sectionType" v-model="editForm.type" :options="sectionTypes"
                                                 label="name" :reduce="option => option.value"
                                       >
                                          <template #option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-primary">{{ description }}</small>
                                             </div>
                                          </template>
                                          <template #selected-option="{ name, description }">
                                             <div class="d-flex flex-column">
                                                <span>{{ name }}</span>
                                                <small class="text-primary">{{ description }}</small>
                                             </div>
                                          </template>
                                       </v-select>
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-component'  && editForm.type !== 'section-with-partners'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="componentType" class="form-label-md mb-2">Component</label>
                                       <v-select id="componentType" v-model="editForm.component_type" :options="componentTypes"
                                                 label="name" :reduce="option => option.value"
                                       />
                                    </div>
                                 </div>
                                 <div v-if="editForm.type !== 'section-with-partners'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionTitle" class="form-label-md mb-2">Section Title</label>
                                       <input v-model="editForm.title" type="text" class="form-control" id="sectionTitle" />
                                    </div>
                                 </div>
                                 <div v-if="editForm.type !== 'section-with-map'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionSubTitle" class="form-label-md mb-2">Section Sub-Heading</label>
                                       <input v-model="editForm.sub_title" type="text" class="form-control" id="sectionSubTitle" />
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionTitle" class="form-label-md mb-2">Form Title</label>
                                       <input v-model="editForm.form_title" type="text" class="form-control" id="sectionTitle" />
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionTitle" class="form-label-md mb-2">Form Sub Heading</label>
                                       <input v-model="editForm.form_sub_title" type="text" class="form-control" id="sectionTitle" />
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="sectionTitle" class="form-label-md mb-2">Form Button Text</label>
                                       <input v-model="editForm.form_button_text" type="text" class="form-control" id="sectionTitle" />
                                    </div>
                                 </div>
                                 <div v-if="editForm.section_has_map" class="col-12">
                                    <div class="mb-3">
                                       <label for="mapLink" class="form-label-md mb-2">Map URL <span class="small ms-4">(add an embedded link only)</span></label>
                                       <input v-model="editForm.map_link" type="text" class="form-control" id="mapLink" />
                                       <div v-if="form.errors.map_link" class="text-danger">{{ editForm.errors.map_link }}</div>
                                    </div>
                                 </div>
                                 <div v-if="editForm.type !== 'why-us-section' && editForm.section_has_image || editForm.type === 'section-with-qr-code'
                                          && editForm.type !== 'section-with-contact-form' && editForm.type !== 'section-with-partners'"
                                      class="col-lg-6 col-md-6 col-12 my-5">
                                    <div class="mb-3">
                                       <div class="card crd-custom">
                                          <div class="card-body">
                                             <div v-if="editForm.media">
                                                <p class="text-muted d-md-flex d-block">
                                                   Section Image
                                                   <span v-if="editForm.type !== 'hero-section' && editForm.type !== 'section-with-contact-form'
                                                               && editForm.type !== 'section-with-partners' && editForm.type !== 'why-us-section'
                                                               && editForm.type !== 'section-with-qr-code'"
                                                         class="form-check form-switch ms-auto py-md-0 py-3">
                                                      <input v-model="editForm.section_image_first" class="form-check-input" type="checkbox" id="showImageFirst">
                                                      <label class="form-check-label" for="showImageFirst"> Show Image First </label>
                                                   </span>
                                                </p>
                                                <div class="card card-img p-5">
                                                   <img :src="editForm.media.original_url" alt="logo" style="width:120px; height:auto;">
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger my-3 ms-3" @click.prevent="deleteMedia(section)">
                                                   Delete Image
                                                </button>
                                             </div>
                                             <div v-else>
                                                <div class="mb-3 form-group">
                                                   <label class="form-label-md mb-2 d-md-flex d-block" for="sectionImage">
                                                      Section Image
                                                      <span v-if="editForm.type !== 'hero-section' && editForm.type !== 'section-with-contact-form'
                                                               && editForm.type !== 'section-with-partners' && editForm.type !== 'why-us-section'
                                                               && editForm.type !== 'section-with-qr-code'"
                                                            class="form-check form-switch ms-auto py-md-0 py-3">
                                                      <input v-model="editForm.section_image_first" class="form-check-input" type="checkbox" id="showImageFirst">
                                                      <label class="form-check-label" for="showImageFirst"> Show Image First </label>
                                                   </span>
                                                   </label>
                                                   <input @change="(e) => handleEditSectionMedia(e, section)"
                                                          id="sectionImage" type="file" accept="image/*" required
                                                          class="form-control mb-3"
                                                   />
                                                   <button type="button" class="btn btn-success"  @click.prevent="uploadMedia" :disabled="mediaForm.processing">
                                                      Upload Image
                                                   </button>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-video'" class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <div class="row">
                                          <div class="col-12">
                                             <div class="mb-3">
                                                <label for="videoType" class="form-label-md mb-1">Video Type</label>
                                                <input v-model="editForm.video_type" type="text" class="form-control" id="videoType" />
                                                <div v-if="form.errors.video_type" class="text-danger">{{ editForm.errors.video_type }}</div>
                                             </div>
                                          </div>
                                          <div v-if="editForm.video_type !== 'local'" class="col-12">
                                             <div class="mb-3">
                                                <label for="videoType" class="form-label-md mb-1">Video Url</label>
                                                <input v-model="editForm.video_type" type="text" class="form-control" id="videoType" />
                                                <div v-if="form.errors.video_type" class="text-danger">{{ editForm.errors.video_type }}</div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div v-if="editForm.type !== 'section-with-partners'" class="col-12">
                                    <div class="mb-5">
                                       <label for="sectionDetails" class="form-label-md mb-2 d-block">
                                          Section Details
                                          <span v-if="editForm.type === 'section-with-contact-form'" class="form-check form-switch py-3">
                                          <input v-model="editForm.include_contact_cards" class="form-check-input" type="checkbox" id="sectionDetails">
                                          <label class="form-check-label" for="sectionDetails"> Include Contact Details </label>
                                       </span>
                                       </label>
                                       <div >
                                          <editor v-model="editForm.details" id="editor" class="editor-control mx-0" />
                                       </div>
                                       <!--                                    <ck-editor v-model="editForm.details" id="editor" class="w-100"></ck-editor>-->
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-gallery' || editForm.section_style === 'hero-with-gallery'" class="col-12">
                                    <div class="mb-3">
                                       <label class="form-label-md mb-1">Gallery Images</label>
                                       <Dropzone v-model="sectionGalleryForm.file" :multiple="true" :existing="existingGalleryImages"
                                                 :showSelectButton="false" previewPosition="outside" selectFileStrategy="merge"
                                                 :allowSelectOnPreview="true" :max-files="20" :max-file-size="5"
                                                 @preview-removed="handleGalleryPreviewRemoved"
                                                 :upload-url="`/wp-admin/medias/sections/${sectionGalleryForm.section_id}/gallery`"
                                       />
                                       <div v-if="sectionGalleryForm.errors.gallery_media" class="text-danger">{{ sectionGalleryForm.errors.gallery_media }}</div>
                                    </div>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-cards'" class="col-12">
                                    <label class="form-label-lg mb-5"> Section Cards </label>
                                    <div class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-sm table-bordered">
                                                <thead>
                                                <tr>
                                                   <th colspan="4">
                                                      <button type="button" class="btn btn-secondary" @click.prevent="openNewSectionCardModal(section)">
                                                         Add Card
                                                      </button>
                                                   </th>
                                                </tr>
                                                </thead>
                                                <thead>
                                                <tr>
                                                   <th class="" style="width: 45%;">Title</th>
                                                   <th class="" style="width: 10%;">Icon</th>
                                                   <th class="" style="width: 40%;">Details</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(card, cardIndex) in section.section_cards" :key="cardIndex">
                                                   <td>{{ card.title }}</td>
                                                   <td>
                                                      <i class="mdi fs-2" :class="`${card.icon}`"></i>
                                                   </td>
                                                   <td class="text-truncate text-wrap">
                                                      <div v-html="truncate(card.details, 200)"></div>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="openSectionCardEditModal(card)">
                                                               <i class="bx bx-edit-alt me-2"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSectionCard(section, card)">
                                                               <i class="bx bx-trash"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type !== 'section-with-contact-form' && editForm.type !== 'section-with-partners' && editForm.type !== 'section-with-cards' && editForm.type !== 'section-with-gallery'"
                                      class="col-md-7 col-12">
                                    <div class="form-check form-switch mb-5">
                                       <input v-model="editForm.has_cta_buttons" class="form-check-input" type="checkbox" id="hasCtaButtons">
                                       <label class="form-check-label" for="hasCtaButtons"> Has CTA Buttons </label>
                                       <br>
                                       <span class="text-muted">check this if you want to add Call To Action(CTA) buttons to the section</span>
                                    </div>
                                    <div v-if="editForm.has_cta_buttons" class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-sm table-bordered">
                                                <thead>
                                                <tr>
                                                   <th class="" style="">Button Text</th>
                                                   <th class="" style="">Button Style</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(cta, ctaIndex) in section.cta_buttons" :key="ctaIndex">
                                                   <td>{{ cta.cta_button_text }}</td>
                                                   <td>
                                                      <span v-if="cta.cta_button_type === 'btn-primary'">Primary Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-secondary'">Secondary Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-info'">Info Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-success'">Success Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-warning'">Warning Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-danger'">Danger Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-dark'">Dark Button</span>
                                                      <span v-else-if="cta.cta_button_type === 'btn-default'">Default Button</span>
                                                      <span v-else>Unknown</span>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="openCtaButtonEditModal(cta)">
                                                               <i class="bx bx-edit-alt me-2 fs-5"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deleteCtaButton(section, cta)">
                                                               <i class="bx bx-trash me-2 fs-5"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type !== 'section-with-contact-form' && editForm.type !== 'section-with-partners' && editForm.type !== 'section-with-cards' && editForm.type !== 'section-with-gallery'"
                                      class="col-md-5 col-12">
                                    <form id="createCtaButtonForm" class="card crd-custom">
                                       <div class="card-body">
                                          <label class="form-label-md mb-4 d-md-flex d-block">
                                             <span class="align-content-center">CTA Button Form</span>
                                             <span v-if="newCtaButtonForm.cta_button_text" class="ms-auto py-md-0 py-3">
                                             <button type="button" class="btn btn-sm btn-light" @click.prevent="uploadCtaButton(section)">Save</button>
                                          </span>
                                          </label>
                                          
                                          <!--                                          <div v-if="editForm.cta_buttons.length >= 2">-->
                                          <!--                                             <p class="text-muted">-->
                                          <!--                                                You can only add 2 CTA buttons per section.-->
                                          <!--                                             </p>-->
                                          <!--                                          </div>-->
                                          <div class="row"> <!-- v-else -->
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="ctaLinkType" class="form-label-md mb-1">Link Type</label>
                                                   <v-select id="ctaLinkType" v-model="newCtaButtonForm.cta_link_type" :options="cardLinkTypes"
                                                             label="name" :reduce="option => option.value" >
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="newCtaButtonForm.cta_link_type === 'url'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="cardUrl" class="form-label-md mb-1">Custom Url</label>
                                                   <input type="text" v-model="newCtaButtonForm.custom_url" class="form-control" id="cardUrl" />
                                                </div>
                                             </div>
                                             <div v-if="newCtaButtonForm.cta_link_type === 'section'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                                                   <v-select id="sectionUrl" v-model="newCtaButtonForm.section_url" :options="sectionUrls"
                                                             label="name" :reduce="option => option.value" >
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="newCtaButtonForm.cta_link_type === 'page'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="pageId" class="form-label-md mb-1">Page To Redirect To</label>
                                                   <v-select id="pageId" v-model="newCtaButtonForm.page_id" :options="pages"
                                                             label="title" :reduce="option => option.id" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="buttonText" class="form-label-md mb-1">Button Text</label>
                                                   <input type="text" v-model="newCtaButtonForm.cta_button_text" class="form-control" id="buttonText" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="buttonType" class="form-label-md mb-1">Button Type</label>
                                                   <v-select id="buttonType" v-model="newCtaButtonForm.cta_button_type" :options="ctaButtonTypes"
                                                             label="name" :reduce="option => option.value" />
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-icon-box'" class="col-12 mb-4">
                                    <label class="form-label-lg mb-5"> Icon Boxes </label>
                                    <div class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-sm table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                   <th colspan="5">
                                                      <button type="button" class="btn btn-secondary" @click.prevent="openNewIconBoxModalModal(section)">
                                                         Add Icon Box
                                                      </button>
                                                   </th>
                                                </tr>
                                                </thead>
                                                <thead>
                                                <tr>
                                                   <th class="" style="width: 15%;">Title</th>
                                                   <th class="" style="width: 5%;">Icon</th>
                                                   <th class="" style="width: 15%;">Link Type</th>
                                                   <th class="" style="width: 60%;">Description</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(card, cardIndex) in section.icon_boxes" :key="cardIndex">
                                                   <td>{{ card.title }}</td>
                                                   <td>
                                                      <i class="mdi fs-2" :class="`${card.icon}`"></i>
                                                   </td>
                                                   <td>{{ card.icon_link_type }}</td>
                                                   <td class="text-wrap text-truncate">
                                                      <div v-html="truncate(card.details, 200)"></div>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="openIconBoxEditModal(card)">
                                                               <i class="bx bx-edit-alt me-2"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deleteIconBox(section, card)">
                                                               <i class="bx bx-trash"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type === 'section-with-faqs'" class="col-md-7 col-12 mb-4">
                                    <label class="form-label-lg mb-5"> FAQs </label>
                                    <div class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-sm table-bordered">
                                                <thead>
                                                <tr>
                                                   <th class="" style="width: 20%;">Question</th>
                                                   <th class="" style="width: 75%;">Answer</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(faq, faqIndex) in section.section_faqs" :key="faqIndex">
                                                   <td>{{ faq.question }}</td>
                                                   <td class="text-wrap">
                                                      <div v-html="truncate(faq.answer, 200)"></div>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="openSectionFaqEditModal(faq)">
                                                               <i class="bx bx-edit-alt me-2"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSectionFaq(section, faq)">
                                                               <i class="bx bx-trash"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type === 'section-with-faqs'" class="col-md-5 col-12 mt-5 pt-5">
                                    <form id="createCtaButtonForm" class="card crd-custom">
                                       <div class="card-body">
                                          <label class="form-label-md mb-4 d-md-flex d-block">
                                             <span class="align-content-center">FAQ Form</span>
                                             <span class="ms-auto py-md-0 py-3">
                                                <button type="button" class="btn btn-sm btn-light" @click.prevent="uploadNewSectionFaq(section)">Save</button>
                                             </span>
                                          </label>
                                          <div class="row">
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="question" class="form-label-md mb-1">Question</label>
                                                   <input type="text" v-model="newSectionFaqForm.question" class="form-control" id="question" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="answer" class="form-label-md mb-1">Answer</label>
                                                   <editor v-model="newSectionFaqForm.answer" id="answer" class="editor-control" />
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-testimonials'" class="col-md-7 col-12 mb-4">
                                    <label class="form-label-lg mb-5"> Testimonials </label>
                                    <div class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-sm table-bordered">
                                                <thead>
                                                <tr>
                                                   <th class="" style="width: 20%;">Name</th>
                                                   <th class="" style="width: 10%;">Position</th>
                                                   <th class="" style="width: 65%;">Details</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(testimonial, testimonialIndex) in section.section_testimonials" :key="testimonialIndex">
                                                   <td>{{ testimonial.name }}</td>
                                                   <td>{{ testimonial.position }}</td>
                                                   <td class="text-wrap">
                                                      <div v-html="truncate(testimonial.details, 200)"></div>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="openSectionTestimonialEditModal(testimonial)">
                                                               <i class="bx bx-edit-alt me-2"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSectionTestimonial(section, testimonial)">
                                                               <i class="bx bx-trash"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type === 'section-with-testimonials'" class="col-md-5 col-12 mt-5 pt-5">
                                    <form id="editTestimonialForm" class="card crd-custom">
                                       <div class="card-body">
                                          <label class="form-label-md mb-4 d-md-flex d-block">
                                             <span class="align-content-center">Testimonial Form</span>
                                             <span class="ms-auto py-md-0 py-3">
                                                <button type="button" class="btn btn-sm btn-light" @click.prevent="uploadNewSectionTestimonial(section)">Save</button>
                                             </span>
                                          </label>
                                          <div class="row">
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="name" class="form-label-md mb-1">Name</label>
                                                   <input type="text" v-model="newSectionTestimonialForm.name" class="form-control" id="name" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="position" class="form-label-md mb-1">Position <small class="text-muted ms-1">(optional)</small></label>
                                                   <input type="text" v-model="newSectionTestimonialForm.position" class="form-control" id="position" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="details" class="form-label-md mb-1">Answer</label>
                                                   <editor v-model="newSectionTestimonialForm.details" id="details" class="editor-control" />
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                                 <div v-if="editForm.type === 'section-with-pricing-plans'" class="col-12">
                                    <label class="form-label-lg mb-5"> Pricing Plans </label>
                                    <div class="w-100">
                                       <div class="mb-3">
                                          <div class="table-responsive text-nowrap">
                                             <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                   <th colspan="6">
                                                      <button type="button" class="btn btn-sm btn-secondary" @click.prevent="showCreatePricingPlanModal(section)">
                                                         Add New
                                                      </button>
                                                   </th>
                                                </tr>
                                                </thead>
                                                <thead>
                                                <tr>
                                                   <!--                                                   <th class="" style="width: 5%;"></th>-->
                                                   <th class="" style="width: 35%;">Name</th>
                                                   <th class="" style="width: 20%;">Tagline</th>
                                                   <th class="" style="width: 15%;">Price</th>
                                                   <th class="" style="width: 15%;">Billing Method</th>
                                                   <th class="" style="width: 15%;">Status</th>
                                                   <th class="" style="width: 5%;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(plan, planIndex) in section.pricing_plans" :key="planIndex">
                                                   <!--                                                   <td class="text-center"><i class="bx bx-move fs-5"></i></td>-->
                                                   <td>
                                                      {{ plan.name }} <span class="ms-2 badge"></span>
                                                   </td>
                                                   <td>{{ plan.tagline }}</td>
                                                   <td>{{ $filters.toMoneyFormat(plan.price * 0.01) }}</td>
                                                   <td>{{ plan.billing_period }}</td>
                                                   <td>
                                                      <span v-if="plan.active" class="badge bg-success">
                                                         Active
                                                      </span>
                                                      <span v-else-if="!plan.active" class="badge bg-danger">
                                                         Deactivated
                                                      </span>
                                                      <span v-else class="badge bg-secondary">
                                                         Unknown
                                                      </span>
                                                   </td>
                                                   <td>
                                                      <div class="dropdown">
                                                         <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical"></i>
                                                         </button>
                                                         <div class="dropdown-menu dropdown-menu-end">
                                                            <button type="button" class="dropdown-item" @click.prevent="editPricingPlan(section, plan)">
                                                               <i class="bx bx-edit-alt me-2"></i>Edit
                                                            </button>
                                                            <button type="button" class="dropdown-item text-danger" @click.prevent="deletePricingPlan(section, plan)">
                                                               <i class="bx bx-trash"></i> Delete
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
                                 </div>
                                 <div v-if="editForm.type" class="col-12 mt-5 pt-3">
                                    <div class="mb-3">
                                       <label class="form-label-lg mb-3"> Section Styling </label>
                                       <div class="row">
                                          <div v-if="editForm.type === 'hero-section' || editForm.type === 'section-with-services' ||
                                          editForm.type === 'section-with-projects' || editForm.type === 'section-with-cards' ||
                                          editForm.type === 'section-without-image' || editForm.type === 'section-ecommerce-products' ||
                                          editForm.type === 'section-with-icon-box' || editForm.type === 'section-with-faqs' ||
                                          editForm.type === 'section-with-pricing-plans' || editForm.type === 'section-with-contact-form' ||
                                          editForm.type === 'section-with-testimonials' || editForm.type === 'section-with-gallery' ||
                                          editForm.type === 'section-ecommerce-product-category'"
                                               class="col-md-5 col-12">
                                             <div class="mb-3">
                                                <div class="row">
                                                   <div v-if="editForm.type === 'hero-section'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="heroSectionDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-services'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="serviceComponentDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-projects'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="projectComponentDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-cards'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionCardDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-without-image'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionWithoutImageDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-icon-box'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style" :options="sectionIconBoxDesigns" label="name"
                                                                   :reduce="option => option.value" >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-faqs'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionFaqDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-testimonials'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionTestimonialDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-pricing-plans'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionPricingPlanDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-contact-form'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionFormDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-with-gallery'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="galleryDesigns" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-ecommerce-products'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style"
                                                                   :options="sectionEcommerceProductStyles" label="name"
                                                                   :reduce="option => option.value"
                                                         >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                   <div v-if="editForm.type === 'section-ecommerce-product-category'" class="col-12">
                                                      <div class="mb-3">
                                                         <label for="sectionStyle" class="form-label-md mb-1">
                                                            Section Style
                                                         </label>
                                                         <v-select id="sectionStyle" v-model="editForm.section_style" :options="sectionEcommerceProductCategoryStyles" label="name"
                                                                   :reduce="option => option.value" >
                                                            <template #option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                            <template #selected-option="{ name, description }">
                                                               <div class="d-flex flex-column">
                                                                  <span>{{ name }}</span>
                                                                  <small class="text-muted">{{ description }}</small>
                                                               </div>
                                                            </template>
                                                         </v-select>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                          <div v-if="editForm.type !== 'hero-section'" class="col-md-3 col-12">
                                             <div class="mb-3">
                                                <div class="form-check form-switch mb-0">
                                                   <input v-model="editForm.section_has_bg" class="form-check-input" type="checkbox" id="slidingHeroSection">
                                                   <label class="form-check-label" for="slidingHeroSection"> Add Background </label>
                                                </div>
                                                <span class="text-muted mb-3">check this if you want to a background to the section</span>
                                             </div>
                                          </div>
                                          <div v-if="editForm.section_has_bg" class="col-md-4 col-12">
                                             <div class="mb-3">
                                                <label for="backgroundStyle" class="form-label-md mb-1">Background Style</label>
                                                <v-select id="backgroundStyle" v-model="editForm.section_background_type"
                                                          :options="sectionBackgrounds" label="name" :reduce="option => option.value" />
                                             </div>
                                          </div>
                                          <div v-if="editForm.section_has_bg" class="col-md-4 col-12">
                                             <div class="mb-3">
                                                <label for="backgroundColor" class="form-label-md mb-1">Background Color</label>
                                                <v-select id="backgroundColor" v-model="editForm.section_background_color"
                                                          :options="sectionBgOverlays" label="name" :reduce="option => option.value" />
                                             </div>
                                          </div>
                                          <div v-if="editForm.section_background_type === 'image-bg'" class="col-md-6 col-12">
                                             <div class="mb-3">
                                                <div class="card crd-custom">
                                                   <div class="card-body">
                                                      <div v-if="editForm.background_image">
                                                         <p class="text-muted d-md-flex d-block">
                                                            Section Image
                                                         </p>
                                                         <div class="card card-img p-5">
                                                            <img :src="editForm.background_image.original_url" alt="background" style="width:200px; height:auto;">
                                                         </div>
                                                         <button type="button" class="btn btn-sm btn-outline-danger my-3 ms-3" @click.prevent="deleteBackgroundMedia(section)">
                                                            Delete Image
                                                         </button>
                                                      </div>
                                                      <div v-else>
                                                         <div class="mb-3 form-group">
                                                            <label class="form-label-md mb-2 d-md-flex d-block" for="sectionBgImage">
                                                               Background Image
                                                            </label>
                                                            <input @change="(e) => handleEditSectionBackgroundMedia(e, section)"
                                                                   id="sectionBgImage" type="file" accept="image/*" required
                                                                   class="form-control mb-3"
                                                            />
                                                            <button type="button" class="btn btn-success"  @click.prevent="uploadBackgroundMedia" :disabled="backgroundMediaForm.processing">
                                                               {{ form.processing ? 'Processing...' : 'Upload Image' }}
                                                            </button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <!--                                 <div v-if="editForm.type === 'hero-section'" class="col-12 mt-5 pt-3">-->
                                 <!--                                    <div class="mb-3">-->
                                 <!--                                       <label class="form-label-lg mb-3"> Hero Section Styling </label>-->
                                 <!--                                       <div class="row">-->
                                 <!--                                          <div class="col-md-6 col-6">-->
                                 <!--                                             <div class="mb-3">-->
                                 <!--                                                <div class="form-check form-switch mb-0">-->
                                 <!--                                                   <input v-model="editForm.sliding_hero_section" class="form-check-input" type="checkbox" id="slidingHeroSection">-->
                                 <!--                                                   <label class="form-check-label" for="slidingHeroSection"> Sliding Hero Section </label>-->
                                 <!--                                                </div>-->
                                 <!--                                                <span class="text-muted mb-3">check this if you want to make the hero section a carousel slider</span>-->
                                 <!--                                             </div>-->
                                 <!--                                          </div>-->
                                 <!--                                       </div>-->
                                 <!--                                    </div>-->
                                 <!--                                 </div>-->
                                 <div v-if="editForm.section_style === 'hero-with-carousel' || editForm.section_style === 'hero-with-carousel-aligned-left' && editForm.type !== 'section-with-gallery'"
                                      class="col-12">
                                    <div class="row">
                                       <div class="col-md-7 col-12">
                                          <div class="mb-3 mt-3">
                                             <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                   <thead>
                                                   <tr>
                                                      <th class="" style="width: 30%;">Title</th>
                                                      <th class="" style="width: 25%;">Button</th>
                                                      <th class="" style="width: 40%;">Details</th>
                                                      <th class="" style="width: 5%;"></th>
                                                   </tr>
                                                   </thead>
                                                   <tbody>
                                                   <tr v-for="(slide, slideIndex) in editForm.hero_slides" :key="slideIndex">
                                                      <td>
                                                         <div class="d-flex flex-column">
                                                            <span class="mb-3">{{ slide.title }}</span>
                                                            <span class="fw-bold">Sub Title:<small class="ms-2 fw-normal text-muted">{{ slide.sub_title }}</small></span>
                                                         </div>
                                                      </td>
                                                      <td>
                                                         <div class="d-flex flex-column">
                                                            <span class="fw-bold">Page:<small class="ms-2 fw-normal text-muted">{{ slide.page?.title ?? '-' }}</small></span>
                                                            <span class="fw-bold">Button Text:<small class="ms-2 fw-normal text-muted">{{ slide.cta_button_text }}</small></span>
                                                            <span class="fw-bold">Button Type:
                                                               <small class="ms-2 fw-normal text-muted">
                                                                  <span v-if="slide.cta_button_type === 'btn-primary'">Primary Button</span>
                                                                  <span v-else-if="slide.cta_button_type === 'btn-secondary'">Secondary Button</span>
                                                                  <span v-else-if="slide.cta_button_type === 'btn-default'">Default Button</span>
                                                                  <span v-else>Unknown</span>
                                                               </small>
                                                            </span>
                                                         </div>
                                                      </td>
                                                      <td class="">
                                                         <div v-html="slide.details ? truncate(slide.details, 100) : null"></div>
                                                      </td>
                                                      <td>
                                                         <div class="dropdown">
                                                            <button class="btn btn-sm btn-icon align-text-top py-1" data-bs-toggle="dropdown">
                                                               <i class="bx bx-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                               <button type="button" class="dropdown-item" @click.prevent="openHeroSlideEditModal(slide)">
                                                                  <i class="bx bx-edit-alt me-2"></i>Edit
                                                               </button>
                                                               <button type="button" class="dropdown-item text-danger" @click.prevent="deleteSlide(section, slide)">
                                                                  <i class="bx bx-trash"></i> Delete
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
                                       <div class="col-md-5 col-12">
                                          <div id="createHeroSlides" class="card crd-custom mb-3">
                                             <div class="card-body">
                                                <label class="form-label-md mb-4 d-md-flex d-block">
                                                   <span class="align-content-center">Hero Slides Form</span>
                                                   <span class="ms-auto py-md-0 py-3">
                                                <button type="button" class="btn btn-sm btn-light" @click.prevent="uploadNewHeroSlide(section)">Save</button>
                                             </span>
                                                </label>
                                                <div class="row">
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="cardTitle" class="form-label-md mb-1">Title</label>
                                                         <input type="text" v-model="newHeroSlideForm.title" class="form-control" id="cardTitle" />
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="cardTitle" class="form-label-md mb-1">Sub Title</label>
                                                         <input type="text" v-model="newHeroSlideForm.sub_title" class="form-control" id="cardTitle" />
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="pageId" class="form-label-md mb-1">Page To Redirect To:</label>
                                                         <v-select id="pageId" v-model="newHeroSlideForm.page_id" :options="pages" label="title" :reduce="option => option.id" />
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="buttonText" class="form-label-md mb-1">Button Text</label>
                                                         <input type="text" v-model="newHeroSlideForm.cta_button_text" class="form-control" id="buttonText" />
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="buttonType" class="form-label-md mb-1">Button Type</label>
                                                         <v-select id="buttonType" v-model="newHeroSlideForm.cta_button_type"
                                                                   :options="ctaButtonTypes" label="name" :reduce="option => option.value" />
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="slideImage" class="form-label-md mb-2 d-md-flex d-block">
                                                            Slide Image
                                                         </label>
                                                         <!--                                                         <input id="slideImage" @change="handleNewSlideMedia" type="file" accept="image/*" class="form-control">-->
                                                         <Dropzone @files-added="handleNewSlideMedia" :multiple="false"
                                                                   selectFileStrategy="replace"/>
                                                      </div>
                                                   </div>
                                                   <div class="col-12">
                                                      <div class="mb-3">
                                                         <label for="cardDetails" class="form-label-md mb-1">Details</label>
                                                         <editor v-model="newHeroSlideForm.details" id="editor" class="editor-control" />
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12 mt-5 ms-auto">
                                    <button type="button" class="btn btn-success me-3" @click.prevent="updateSection"
                                            :disabled="editForm.processing">
                                       Update Section
                                    </button>
                                    <button type="button" class="btn btn-outline-danger float-end" @click.prevent="deleteSection(section)"
                                            :disabled="editForm.processing">
                                       Delete Section
                                    </button>
                                 </div>
                                 <div class="col-6 mt-5">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </draggable>
               </div>
            </div>
         </div>
         
         <!-- Section Modal -->
         <div class="modal fade p-0" id="add-section-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="add-section-modal-label" aria-hidden="true" ref="addSectionModal"
         >
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
               <div class="modal-content">
                  <div class="modal-header p-5 border-bottom">
                     <h5 class="modal-title" id="create-menu-modal-label">New Section</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click.prevent="formCleanUp"></button>
                  </div>
                  
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="uploadNewSection">
                        <div class="row">
                           <div class="col-lg-12 col-md-12 col-12 mb-4">
                              <div class="row">
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="section_id" class="form-label-md mb-1">Section ID</label>
                                       <input v-model="form.spa_section_name" type="text" class="form-control" id="section_id" />
                                       <div class="form-text mb-2">This name will be used to identify the section you want to refer to in your page. The name will be included at the menu if this page is a landing page type.</div>
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3 ">
                                       <label class="form-label-md mb-1">Section Width Style <span class="small text-muted text-wrap">(default width)</span></label>
                                       <v-select id="sectionWidthStyle" v-model="form.width_style" :options="sectionWidthStyles"
                                                 label="name" :reduce="option => option.value"
                                       />
                                    </div>
                                 </div>
                                 <div v-if="form.width_style === 'container-fluid'" class="col-md-4 col-12">
                                    <div class="form-group mb-3">
                                       <label class="form-label mb-3">Width</label>
                                       <input v-model="form.width" type="number" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="sectionType" class="form-label-md mb-2 d-md-flex d-block">
                                    Section Type
                                    <span v-if="form.type === 'section-with-contact-form' || form.type === 'section-with-contact-cards'" class="form-check form-switch ms-auto py-md-0 py-3">
                                       <input v-model="form.section_has_map" class="form-check-input" type="checkbox" id="hasMap">
                                       <label class="form-check-label" for="hasMap"> Include a map </label>
                                    </span>
                                 </label>
                                 <v-select id="sectionType" v-model="form.type" :options="sectionTypes"
                                           label="name" :reduce="option => option.value"
                                 >
                                    <template #option="{ name, description }">
                                       <div class="d-flex flex-column">
                                          <span>{{ name }}</span>
                                          <small class="text-muted">{{ description }}</small>
                                       </div>
                                    </template>
                                    <template #selected-option="{ name, description }">
                                       <div class="d-flex flex-column">
                                          <span>{{ name }}</span>
                                          <small class="text-primary">{{ description }}</small>
                                       </div>
                                    </template>
                                 </v-select>
                                 <div v-if="form.errors.type" class="text-danger">{{ form.errors.type }}</div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-ecommerce-product-category'" class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="sectionCategoryId" class="form-label-md mb-1">
                                    Ecommerce Product Categories
                                 </label>
                                 <v-select id="sectionCategoryId" v-model="form.category_id" :options="ecmCategories" label="name"
                                           :reduce="option => option.id" >
                                 </v-select>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-component' && form.type !== 'section-with-partners'" class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="componentType" class="form-label-md mb-2">Component</label>
                                 <v-select id="componentType" v-model="form.component_type" :options="componentTypes"
                                           label="name" :reduce="option => option.value" />
                              </div>
                           </div>
                           <div v-if="form.type !== 'why-us-section' && form.section_has_image || form.type === 'section-with-qr-code'
                           && form.type !== 'section-with-contact-form' && form.type !== 'section-with-partners'"
                                class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="sectionImage" class="form-label-md mb-2 d-md-flex d-block">
                                    Section Image
                                    <span v-if="form.type !== 'hero-section' && form.type !== 'section-with-contact-form'
                                                && form.type !== 'section-with-partners' && form.type !== 'why-us-section' && form.type !== 'section-with-qr-code'"
                                          class="form-check form-switch ms-auto mb-0 py-md-0 py-3">
                                       <input v-model="form.section_image_first" class="form-check-input" type="checkbox" id="hasCtaButtons">
                                       <label class="form-check-label" for="hasCtaButtons"> Show Image First </label>
                                    </span>
                                 </label>
                                 <input id="sectionMedia" @change="handleSectionMedia" type="file" accept="image/*" class="form-control">
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="sectionType" class="form-label-md mb-2">Section Title</label>
                                 <input v-model="form.title" type="text" class="form-control" id="sectionTitle" />
                                 <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                              </div>
                           </div>
                           <div v-if="form.type !== 'section-with-map'" class="col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="sectionSubTitle" class="form-label-md mb-2">Section Sub-Heading</label>
                                 <input v-model="form.sub_title" type="text" class="form-control" id="sectionSubTitle" />
                                 <div v-if="form.errors.sub_title" class="text-danger">{{ form.errors.sub_title }}</div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="formTitle" class="form-label-md mb-2">Form Title</label>
                                 <input v-model="form.form_title" type="text" class="form-control" id="formTitle" />
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="formSubHeading" class="form-label-md mb-2">Form Sub Heading</label>
                                 <input v-model="form.form_sub_title" type="text" class="form-control" id="formSubHeading" />
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-contact-form'" class="col-lg-4 col-md-4 col-12">
                              <div class="mb-3">
                                 <label for="formButtonText" class="form-label-md mb-2">Form Button Text</label>
                                 <input v-model="form.form_button_text" type="text" class="form-control" id="formButtonText" />
                              </div>
                           </div>
                           <div v-if="form.section_has_map" class="col-12">
                              <div class="mb-3">
                                 <label for="mapLink" class="form-label-md mb-2">Map URL <span class="small ms-4">(add an embedded link only)</span></label>
                                 <input v-model="form.map_link" type="text" class="form-control" id="mapLink" placeholder="<iframe src=https://....></iframe>" />
                                 <div v-if="form.errors.map_link" class="text-danger">{{ form.errors.map_link }}</div>
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="mb-5">
                                 <label for="sectionDetails" class="form-label-md">
                                    Section Details
                                    <span v-if="form.type === 'section-with-contact-form'" class="form-check form-switch py-3">
                                          <input v-model="form.include_contact_cards" class="form-check-input" type="checkbox" id="hasCtaButtons">
                                          <label class="form-check-label" for="hasCtaButtons"> Include Contact Details </label>
                                       </span>
                                 </label>
                                 <div >
                                    <editor v-model="form.details" id="editor" class="editor-control mx-0 w-100" />
                                 </div>
                                 <div v-if="form.errors.details" class="text-danger">{{ form.errors.details }}</div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-gallery' || form.section_style === 'hero-with-gallery'" class="col-12">
                              <div class="mb-3">
                                 <label class="form-label-md mb-1">Gallery Images</label>
                                 <Dropzone @files-added="handleGalleryFiles" @file-removed="handleGalleryFileRemoved" :multiple="true"
                                           :max-files="20" :max-file-size="5" previewPosition="outside"
                                           selectFileStrategy="merge" :allowSelectOnPreview="true" />
                                 <div v-if="form.errors.gallery_media" class="text-danger">{{ form.errors.gallery_media }}</div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-cards'" class="col-md-7 col-12">
                              <label class="form-label-lg mb-5"> Section Cards </label>
                              <div class="w-100">
                                 <div class="mb-3">
                                    <div class="table-responsive text-nowrap">
                                       <table class="table table-sm table-bordered">
                                          <thead>
                                          <tr>
                                             <th class="" style="width: 20%;">Title</th>
                                             <th class="" style="width: 5%;">Icon</th>
                                             <th class="" style="width: 70%;">Description</th>
                                             <th class="" style="width: 5%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="(card, cardIndex) in form.section_cards" :key="cardIndex">
                                             <td>{{ card.title }}</td>
                                             <td>
                                                <i class="mdi fs-2" :class="`${card.icon}`"></i>
                                             </td>
                                             <td class="text-wrap">
                                                <div v-html="card.details"></div>
                                             </td>
                                             <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        @click.prevent="removeSectionCard(cardIndex)">
                                                   <i class="bx bx-trash"></i>
                                                </button>
                                             </td>
                                          </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-cards'" class="col-md-5 col-12">
                              <div id="createCtaButtonForm" class="card crd-custom">
                                 <div class="card-body">
                                    <label class="form-label-md mb-4 d-md-flex d-block">
                                       <span class="align-content-center">Section Cards Form</span>
                                       <span class="ms-auto py-md-0 py-3">
                                          <button type="button" class="btn btn-sm btn-light" @click.prevent="addSectionCardToForm">Save</button>
                                       </span>
                                    </label>
                                    <div class="row">
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="cardTitle" class="form-label-md mb-1">Card Title</label>
                                             <input type="text" v-model="sectionCardForm.title" class="form-control" id="cardTitle" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="cardIcon" class="form-label-md mb-1">Card Icon</label>
                                             <v-select id="cardIcon" v-model="sectionCardForm.icon" :options="cardIcons"
                                                       label="name" :reduce="option => option.value" >
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
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="cardDetails" class="form-label-md mb-1">Card Details</label>
                                             <editor v-model="sectionCardForm.details" id="editor" class="editor-control" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type !== 'section-with-contact-form' && form.type !== 'section-with-partners'
                                 && form.type !== 'section-with-cards' && form.type !== 'section-with-qr-code' && form.type !== 'section-with-gallery'" class="col-md-7 col-12 mb-4">
                              <div class="form-check form-switch mb-0">
                                 <input v-model="form.has_cta_buttons" class="form-check-input" type="checkbox" id="hasCtaButtons">
                                 <label class="form-check-label" for="hasCtaButtons"> Has CTA Buttons </label>
                              </div>
                              <span class="text-muted mb-3">check this if you want to add Call To Action (CTA) buttons</span>
                              <div v-if="form.has_cta_buttons" class="w-100">
                                 <div class="mb-3 mt-3">
                                    <div class="table-responsive text-nowrap">
                                       <table class="table table-sm table-bordered">
                                          <thead>
                                          <tr>
                                             <th class="" style="width: 25%;">Button Text</th>
                                             <th class="" style="width: 20%;">Button Style</th>
                                             <th class="" style="width: 5%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="(cta, ctaIndex) in form.cta_buttons" :key="ctaIndex">
                                             <td>{{ cta.cta_button_text }}</td>
                                             <td>{{ cta.cta_button_type.name }}</td>
                                             <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger" @click.prevent="removeCtaButton(ctaIndex)">
                                                   <i class="bx bx-trash"></i>
                                                </button>
                                             </td>
                                          </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.has_cta_buttons && form.type !== 'section-with-partners' && form.type !== 'section-with-cards' && form.type !== 'section-with-gallery'" class="col-md-5 col-12 mb-4">
                              <div id="createCtaButtonForm" class="card crd-custom">
                                 <div class="card-body">
                                    <label class="form-label-md mb-4 d-md-flex d-block">
                                       <span class="align-content-center">CTA Button Form</span>
                                       <span v-if="ctaButtonForm.cta_button_text" class="ms-auto py-md-0 py-3">
                                          <button type="button" class="btn btn-sm btn-light" @click.prevent="addCtaButtonToForm">
                                             Save
                                          </button>
                                       </span>
                                    </label>
                                    <div v-if="form.cta_buttons.length >= 2">
                                       <p class="text-muted">
                                          You can only add 2 CTA button per section.
                                       </p>
                                    </div>
                                    <div v-else class="row">
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="cardLinkType" class="form-label-md mb-1">Link Type</label>
                                             <v-select id="cardLinkType" v-model="ctaButtonForm.cta_link_type" :options="cardLinkTypes"
                                                       label="name" :reduce="option => option.value" >
                                             </v-select>
                                          </div>
                                       </div>
                                       <div v-if="ctaButtonForm.cta_link_type === 'url'" class="col-12">
                                          <div class="mb-3">
                                             <label for="cardUrl" class="form-label-md mb-1">Custom Url</label>
                                             <input type="text" v-model="ctaButtonForm.custom_url" class="form-control" id="cardUrl" />
                                          </div>
                                       </div>
                                       <div v-if="ctaButtonForm.cta_link_type === 'section'" class="col-12">
                                          <div class="mb-3">
                                             <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                                             <v-select id="sectionUrl" v-model="ctaButtonForm.section_url" :options="sectionUrls"
                                                       label="name" :reduce="option => option.value" >
                                             </v-select>
                                          </div>
                                       </div>
                                       <div v-if="ctaButtonForm.cta_link_type === 'page'" class="col-12">
                                          <div class="mb-3">
                                             <label for="pageId" class="form-label-md mb-1">Page To Redirect To:</label>
                                             <v-select id="pageId" v-model="ctaButtonForm.page" :options="pages" label="title"/>
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="buttonText" class="form-label-md mb-1">Button Text</label>
                                             <input type="text" v-model="ctaButtonForm.cta_button_text" class="form-control" id="buttonText" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="buttonType" class="form-label-md mb-1">Button Type</label>
                                             <v-select id="buttonType" v-model="ctaButtonForm.cta_button_type" :options="ctaButtonTypes" label="name" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-icon-box'" class="col-md-7 col-12">
                              <label class="form-label-lg mb-5"> Icon Boxes </label>
                              <div class="w-100">
                                 <div class="mb-3">
                                    <div class="table-responsive text-nowrap">
                                       <table class="table table-sm table-bordered">
                                          <thead>
                                          <tr>
                                             <th class="" style="width: 20%;">Title</th>
                                             <th class="" style="width: 5%;">Icon</th>
                                             <th class="" style="width: 70%;">Description</th>
                                             <th class="" style="width: 5%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="(card, cardIndex) in form.icon_boxes" :key="cardIndex">
                                             <td>{{ card.title }}</td>
                                             <td>
                                                <i class="mdi fs-2" :class="`${card.icon}`"></i>
                                             </td>
                                             <td class="text-wrap">
                                                <div v-html="card.details"></div>
                                             </td>
                                             <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        @click.prevent="removeIconBox(cardIndex)">
                                                   <i class="bx bx-trash"></i>
                                                </button>
                                             </td>
                                          </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-icon-box'" class="col-md-5 col-12">
                              <div id="createCtaButtonForm" class="card crd-custom">
                                 <div class="card-body">
                                    <label class="form-label-md mb-4 d-md-flex d-block">
                                       <span class="align-content-center">Icon Boxes Form</span>
                                       <span v-if="iconBoxForm.title && iconBoxForm.details" class="ms-auto py-md-0 py-3">
                                          <button type="button" class="btn btn-sm btn-light" @click.prevent="addIconBoxToForm">Save</button>
                                       </span>
                                    </label>
                                    <div class="row">
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="cardTitle" class="form-label-md mb-1">Title</label>
                                             <input type="text" v-model="iconBoxForm.title" class="form-control" id="cardTitle" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="icon" class="form-label-md mb-1">Icon</label>
                                             <v-select id="icon" v-model="iconBoxForm.icon" :options="cardIcons"
                                                       label="name" :reduce="option => option.value" >
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
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="details" class="form-label-md mb-1">Details</label>
                                             <editor v-model="iconBoxForm.details" id="editor" class="editor-control" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-faqs'" class="col-md-7 col-12">
                              <label class="form-label-lg mb-5"> Section FAQs </label>
                              <div class="w-100">
                                 <div class="mb-3">
                                    <div class="table-responsive text-nowrap">
                                       <table class="table table-sm table-bordered">
                                          <thead>
                                          <tr>
                                             <th class="" style="width: 20%;">Question</th>
                                             <th class="" style="width: 75%;">Answer</th>
                                             <th class="" style="width: 5%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="(faq, faqIndex) in form.section_faqs" :key="faqIndex">
                                             <td>{{ faq.question }}</td>
                                             <td class="text-wrap">
                                                <div v-html="faq.answer"></div>
                                             </td>
                                             <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        @click.prevent="removeSectionFaq(faqIndex)">
                                                   <i class="bx bx-trash"></i>
                                                </button>
                                             </td>
                                          </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-faqs'" class="col-md-5 col-12">
                              <div id="createCtaButtonForm" class="card crd-custom">
                                 <div class="card-body">
                                    <label class="form-label-md mb-4 d-md-flex d-block">
                                       <span class="align-content-center">Section FAQ Form</span>
                                       <span class="ms-auto py-md-0 py-3">
                                          <button type="button" class="btn btn-sm btn-light" @click.prevent="addSectionFaqToForm">Save</button>
                                       </span>
                                    </label>
                                    <div class="row">
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="question" class="form-label-md mb-1">Question</label>
                                             <input type="text" v-model="sectionFaqForm.question" class="form-control" id="question" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="answer" class="form-label-md mb-1">Answer</label>
                                             <editor v-model="sectionFaqForm.answer" id="answer" class="editor-control" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-testimonials'" class="col-md-7 col-12">
                              <label class="form-label-lg mb-5"> Section Testimonials </label>
                              <div class="w-100">
                                 <div class="mb-3">
                                    <div class="table-responsive text-nowrap">
                                       <table class="table table-sm table-bordered">
                                          <thead>
                                          <tr>
                                             <th class="" style="width: 20%;">Name</th>
                                             <th class="" style="width: 10%;">Position</th>
                                             <th class="" style="width: 65%;">Details</th>
                                             <th class="" style="width: 5%;"></th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr v-for="(testimonial, testimonialIndex) in form.section_testimonials" :key="testimonialIndex">
                                             <td>{{ testimonial.name }}</td>
                                             <td>{{ testimonial.position }}</td>
                                             <td class="text-wrap">
                                                <div v-html="testimonial.details"></div>
                                             </td>
                                             <td>
                                                <button type="button" class="btn btn-sm btn-icon btn-danger"
                                                        @click.prevent="removeSectionTestimonial(testimonialIndex)">
                                                   <i class="bx bx-trash"></i>
                                                </button>
                                             </td>
                                          </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type === 'section-with-testimonials'" class="col-md-5 col-12">
                              <div id="createCtaButtonForm" class="card crd-custom">
                                 <div class="card-body">
                                    <label class="form-label-md mb-4 d-md-flex d-block">
                                       <span class="align-content-center">Section Testimonial Form</span>
                                       <span class="ms-auto py-md-0 py-3">
                                          <button type="button" class="btn btn-sm btn-light" @click.prevent="addSectionTestimonialToForm">Save</button>
                                       </span>
                                    </label>
                                    <div class="row">
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="name" class="form-label-md mb-1">Name</label>
                                             <input type="text" v-model="sectionTestimonialForm.name" class="form-control" id="name" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="position" class="form-label-md mb-1">Position <small class="text-muted ms-1">(optional)</small></label>
                                             <input type="text" v-model="sectionTestimonialForm.position" class="form-control" id="position" />
                                          </div>
                                       </div>
                                       <div class="col-12">
                                          <div class="mb-3">
                                             <label for="details" class="form-label-md mb-1">Details</label>
                                             <editor v-model="sectionTestimonialForm.details" id="details" class="editor-control" />
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div v-if="form.type" class="col-12 mt-5 pt-3">
                              <div class="mb-3">
                                 <label class="form-label-lg mb-3"> Section Styling </label>
                                 <div class="row">
                                    <div v-if="form.type === 'hero-section' || form.type === 'section-with-services' ||
                                             form.type === 'section-with-projects' || form.type === 'section-with-cards' ||
                                             form.type === 'section-without-image' || form.type === 'section-with-icon-box' ||
                                             form.type === 'section-with-faqs' || form.type === 'section-with-testimonials' ||
                                             form.type === 'section-with-pricing-plans' ||  form.type === 'section-with-gallery' ||
                                             form.type === 'section-ecommerce-product-category'"
                                         class="col-md-4 col-12">
                                       <div class="mb-3">
                                          <div class="row">
                                             <div v-if="form.type === 'hero-section'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="heroSectionDesigns" label="name"
                                                             :reduce="option => option.value" >
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
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-services'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="serviceComponentDesigns" label="name"
                                                             :reduce="option => option.value" >
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
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-projects'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="projectComponentDesigns" label="name"
                                                             :reduce="option => option.value" >
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
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-cards'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionCardDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-without-image'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionWithoutImageDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-icon-box'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionIconBoxDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-faqs'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style"
                                                             :options="sectionFaqDesigns" label="name"
                                                             :reduce="option => option.value"
                                                   >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-testimonials'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionTestimonialDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-pricing-plans'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionPricingPlanDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-contact-form'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionFormDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-with-gallery'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="galleryDesigns" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                             <div v-if="form.type === 'section-ecommerce-product-category'" class="col-12">
                                                <div class="mb-3">
                                                   <label for="sectionStyle" class="form-label-md mb-1">
                                                      Section Style
                                                   </label>
                                                   <v-select id="sectionStyle" v-model="form.section_style" :options="sectionEcommerceProductCategoryStyles" label="name"
                                                             :reduce="option => option.value" >
                                                      <template #option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                      <template #selected-option="{ name, description }">
                                                         <div class="d-flex flex-column">
                                                            <span>{{ name }}</span>
                                                            <small class="text-muted">{{ description }}</small>
                                                         </div>
                                                      </template>
                                                   </v-select>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div v-if="form.type !== 'hero-section'" class="col-md-4 col-12">
                                       <div class="mb-3">
                                          <div class="form-check form-switch mb-0">
                                             <input v-model="form.section_has_bg" class="form-check-input" type="checkbox" id="sectionHasBg">
                                             <label class="form-check-label" for="sectionHasBg"> Add Background </label>
                                          </div>
                                          <span class="text-muted mb-3">check this if you want to a background to the section</span>
                                       </div>
                                    </div>
                                    <div v-if="form.section_has_bg" class="col-md-4 col-12">
                                       <div class="mb-3">
                                          <label for="backgroundStyle" class="form-label-md mb-1">Background Style</label>
                                          <v-select id="backgroundStyle" v-model="form.section_background_type" :options="sectionBackgrounds"
                                                    label="name" :reduce="option => option.value"/>
                                       </div>
                                    </div>
                                    <div v-if="form.section_background_type === 'image-bg'" class="col-md-4 col-12">
                                       <div class="mb-3">
                                          <label for="sectionBgImage" class="form-label-md mb-1 d-md-flex d-block">
                                             Section Background Image
                                          </label>
                                          <input id="sectionBgImage" @change="handleSectionBgMedia" type="file" accept="image/*" class="form-control">
                                       </div>
                                    </div>
                                    <div v-if="form.section_has_bg" class="col-md-4 col-12">
                                       <div class="mb-3">
                                          <label for="backgroundColor" class="form-label-md mb-1">Background Color</label>
                                          <v-select id="backgroundColor" v-model="form.section_background_color" :options="sectionBgOverlays"
                                                    label="name" :reduce="option => option.value" />
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!--                           <div v-if="form.type === 'hero-section'" class="col-12 mt-5 pt-3">-->
                           <!--                              <div class="mb-3">-->
                           <!--                                 <label class="form-label-lg mb-3"> Hero Section Styling </label>-->
                           <!--                                 <div class="row">-->
                           <!--                                    <div class="col-md-6 col-6">-->
                           <!--                                       <div class="mb-3">-->
                           <!--                                          <div class="form-check form-switch mb-0">-->
                           <!--                                             <input v-model="form.sliding_hero_section" class="form-check-input" type="checkbox" id="slidingHeroSection">-->
                           <!--                                             <label class="form-check-label" for="slidingHeroSection"> Sliding Hero Section </label>-->
                           <!--                                          </div>-->
                           <!--                                          <span class="text-muted mb-3">check this if you want to make the hero section a carousel slider</span>-->
                           <!--                                       </div>-->
                           <!--                                    </div>-->
                           <!--                                 </div>-->
                           <!--                              </div>-->
                           <!--                           </div>-->
                           <div v-if="form.section_style === 'hero-with-carousel' || form.section_style === 'hero-with-carousel-aligned-left'" class="col-12">
                              <div class="row">
                                 <div class="col-md-7 col-12">
                                    <div class="mb-3 mt-3">
                                       <div class="table-responsive text-nowrap">
                                          <table class="table table-sm table-bordered">
                                             <thead>
                                             <tr>
                                                <th class="" style="width: 20%;">Title</th>
                                                <th class="" style="width: 25%;">Button</th>
                                                <!--                                                <th class="" style="width: 15%;">Media</th>-->
                                                <th class="" style="width: 35%;">Details</th>
                                                <th class="" style="width: 5%;"></th>
                                             </tr>
                                             </thead>
                                             <tbody>
                                             <tr v-for="(slide, slideIndex) in form.hero_slides" :key="slideIndex">
                                                <td>
                                                   <div class="d-flex flex-column">
                                                      <span>{{ slide.title }}</span>
                                                      <span class="fw-bold">Sub Title: </span><small class="ms-2 fw-normal text-muted">{{ slide.sub_title }}</small>
                                                   </div>
                                                </td>
                                                <td>
                                                   <div class="d-flex flex-column">
                                                      <span class="fw-bold">Page:<small class="ms-2 fw-normal text-primary">{{ slide.page?.title ?? '-' }}</small></span>
                                                      <span class="fw-bold">Button Text:<small class="ms-2 fw-normal text-muted">{{ slide.cta_button_text }}</small></span>
                                                      <span class="fw-bold">Button Type:<small class="ms-2 fw-normal text-muted">{{ slide.cta_button_type.name }}</small></span>
                                                   </div>
                                                </td>
                                                <!--                                                <td>{{ slide.slide_image.name }}</td>-->
                                                <td class="text-wrap">
                                                   <div v-html="slide.details ? truncate(slide.details, 100) : null"></div>
                                                </td>
                                                <td>
                                                   <button type="button" class="btn btn-sm btn-icon btn-danger" @click.prevent="removeSlide(slideIndex)">
                                                      <i class="bx bx-trash"></i>
                                                   </button>
                                                </td>
                                             </tr>
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-5 col-12">
                                    <div id="createHeroSlides" class="card crd-custom mb-3">
                                       <div class="card-body">
                                          <label class="form-label-md mb-4 d-md-flex d-block">
                                             <span class="align-content-center">Hero Slides Form</span>
                                             <span class="ms-auto py-md-0 py-3">
                                                <button type="button" class="btn btn-sm btn-light" @click.prevent="addSlideToForm"
                                                        :disabled="heroSlideForm.processing">
                                                   {{ heroSlideForm.processing ? 'Processing...' : 'Save' }}
                                                </button>
                                             </span>
                                          </label>
                                          <div class="row">
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="cardTitle" class="form-label-md mb-1">Title</label>
                                                   <input type="text" v-model="heroSlideForm.title" class="form-control" id="cardTitle" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="cardTitle" class="form-label-md mb-1">Sub Title</label>
                                                   <input type="text" v-model="heroSlideForm.sub_title" class="form-control" id="cardTitle" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="pageId" class="form-labe-md mb-1l">Page To Redirect To:</label>
                                                   <v-select id="pageId" v-model="heroSlideForm.page" :options="pages" label="title" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="buttonText" class="form-label-md mb-1">Button Text</label>
                                                   <input type="text" v-model="heroSlideForm.cta_button_text" class="form-control" id="buttonText" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="buttonType" class="form-label-md mb-1">Button Type</label>
                                                   <v-select id="buttonType" v-model="heroSlideForm.cta_button_type"
                                                             :options="ctaButtonTypes" label="name" />
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="slideImage" class="form-label-md mb-2 d-md-flex d-block">
                                                      Slide Image
                                                   </label>
                                                   <Dropzone @files-added="handleSlideMedia" :multiple="false"
                                                             selectFileStrategy="replace"/>
                                                </div>
                                             </div>
                                             <div class="col-12">
                                                <div class="mb-3">
                                                   <label for="cardDetails" class="form-label-md mb-1">Details</label>
                                                   <editor v-model="heroSlideForm.details" id="editor" class="editor-control" />
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
                  
                  <div class="modal-footer p-5 border-top">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="formCleanUp"
                             :disabled="form.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="uploadNewSection"
                             :disabled="form.processing">
                        {{ form.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Move Section Modal -->
         <div class="modal fade" id="move-section-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="move-section-modal-label" aria-hidden="true" ref="moveSectionModal">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-section-cta-button-modal-label">Move Section To a Different Page</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="moveSectionFormCleanUp" :disabled="moveSectionForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="newPageId" class="form-label-md mb-1">Page</label>
                              <v-select id="newPageId" v-model="moveSectionForm.new_page_id" :options="availablePagesToMoveSectionsTo"
                                        label="title" :reduce="option => option.id" >
                              </v-select>
                              <div v-if="moveSectionForm.errors.new_page_id" class="text-danger">
                                 {{ moveSectionForm.errors.new_page_id }}
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" @click="moveSectionFormCleanUp"
                             :disabled="moveSectionForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="moveSectionToAnotherPage"
                             :disabled="moveSectionForm.processing">
                        {{ moveSectionForm.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit CTA Button Modal -->
         <div class="modal fade" id="edit-section-cta-button-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-section-cta-button-modal-label" aria-hidden="true" ref="editSectionCtaButtonModal">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-section-cta-button-modal-label">Edit CTA Button</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="editCtaButtonFormCleanUp" :disabled="editCtaButtonForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="ctaLinkType" class="form-label-md mb-1">Link Type</label>
                              <v-select id="ctaLinkType" v-model="editCtaButtonForm.cta_link_type" :options="cardLinkTypes"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="editCtaButtonForm.cta_link_type === 'url'" class="col-12">
                           <div class="mb-3">
                              <label for="cardUrl" class="form-label-md mb-1">Custom Url</label>
                              <input type="text" v-model="editCtaButtonForm.custom_url" class="form-control" id="cardUrl" />
                           </div>
                        </div>
                        <div v-if="editCtaButtonForm.cta_link_type === 'section'" class="col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="editCtaButtonForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="editCtaButtonForm.cta_link_type === 'page'" class="col-12">
                           <div class="mb-3">
                              <label for="pageId" class="form-label-md mb-1">Page To Redirect To</label>
                              <v-select id="pageId" v-model="editCtaButtonForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="buttonText" class="form-label-md mb-1">Button Text</label>
                              <input type="text" v-model="editCtaButtonForm.cta_button_text" class="form-control" id="buttonText" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="buttonType" class="form-label-md mb-1">Button Type</label>
                              <v-select id="buttonType" v-model="editCtaButtonForm.cta_button_type" :options="ctaButtonTypes"
                                        label="name" :reduce="option => option.value" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="editCtaButtonFormCleanUp" :disabled="editCtaButtonForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateCtaButton" :disabled="editCtaButtonForm.processing">
                        {{ editSectionCardForm.processing ? 'Processing...' : 'Update CTA Button' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- New Section Card Modal -->
         <div class="modal fade" id="new-section-card-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="new-section-card-modal-label" aria-hidden="true" ref="newSectionCardModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="new-section-card-modal-label">New Section Card</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="newSectionCardFormCleanUp" :disabled="newSectionCardForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="title" class="form-label-md mb-1">Title</label>
                              <input type="text" v-model="newSectionCardForm.title" class="form-control" id="title" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="icon" class="form-label-md mb-1">Icon</label>
                              <v-select id="icon" v-model="newSectionCardForm.icon" :options="cardIcons"
                                        label="name" :reduce="option => option.value" >
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
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardLinkType" class="form-label-md mb-1">Card Link Type</label>
                              <v-select id="cardLinkType" v-model="newSectionCardForm.card_link_type" :options="cardLinkTypes"
                                        label="name" :reduce="option => option.value"
                              />
                           </div>
                        </div>
                        <div v-if="newSectionCardForm.card_link_type === 'page'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageId" class="form-label-md mb-1">Page</label>
                              <v-select id="pageId" v-model="newSectionCardForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                           </div>
                        </div>
                        <div v-if="newSectionCardForm.card_link_type === 'section'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="newSectionCardForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value"
                              />
                           </div>
                        </div>
                        <div v-if="newSectionCardForm.card_link_type === 'url'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="customUrl" class="form-label-md mb-1">Url</label>
                              <input type="text" v-model="newSectionCardForm.custom_url" class="form-control" id="customUrl" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="media" class="form-label-md mb-1">Image</label>
                              <Dropzone @files-added="handleCardMediaUpload" :multiple="false"
                                        selectFileStrategy="replace" img-width="100%" />
                              <div v-if="newSectionCardForm.errors.media" class="text-danger">{{ newSectionCardForm.errors.media }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="cardDetails" class="form-label-md mb-1">Details</label>
                              <editor v-model="newSectionCardForm.details" id="cardDetails" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="newSectionCardFormCleanUp" :disabled="newSectionCardForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="uploadSectionCard" :disabled="newSectionCardForm.processing">
                        {{ newSectionCardForm.processing ? 'Processing...' : 'Submit Card Details' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Section Card Modal -->
         <div class="modal fade" id="edit-section-card-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-section-card-modal-label" aria-hidden="true" ref="editSectionCardModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-section-card-modal-label">Edit Section Card</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="sectionCardEditFormCleanUp" :disabled="editSectionCardForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="title" class="form-label-md mb-1">Title</label>
                              <input type="text" v-model="editSectionCardForm.title" class="form-control" id="title" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="icon" class="form-label-md mb-1">Icon</label>
                              <v-select id="icon" v-model="editSectionCardForm.icon" :options="cardIcons"
                                        label="name" :reduce="option => option.value" >
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
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardIcon" class="form-label-md mb-1">Card Link Type</label>
                              <v-select id="cardIcon" v-model="editSectionCardForm.card_link_type" :options="cardLinkTypes"
                                        label="name" :reduce="option => option.value"
                              />
                           </div>
                        </div>
                        <div v-if="editSectionCardForm.card_link_type === 'page'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardIcon" class="form-label-md mb-1">Page</label>
                              <v-select id="cardIcon" v-model="editSectionCardForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                           </div>
                        </div>
                        <div v-if="editSectionCardForm.card_link_type === 'section'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="editSectionCardForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value"
                              />
                           </div>
                        </div>
                        <div v-if="editSectionCardForm.card_link_type === 'url'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardUrl" class="form-label-md mb-1">Url</label>
                              <input type="text" v-model="editSectionCardForm.custom_url" class="form-control" id="cardUrl" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="media" class="form-label-md mb-1">Image</label>
                              <Dropzone v-model="editSectionCardForm.media" :multiple="false"
                                        :existing="editSectionCardForm.media ? editSectionCardForm.media : []"
                                        :upload-url="`/wp-admin/medias/section-cards/${editSectionCardForm.card_id}/card-image`"
                                        selectFileStrategy="replace" img-width="100%"
                                        :showSelectButton="false" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="details" class="form-label-md mb-1">Details</label>
                              <editor v-model="editSectionCardForm.details" id="editor" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="sectionCardEditFormCleanUp" :disabled="editSectionCardForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateSectionCard" :disabled="editSectionCardForm.processing">
                        {{ editSectionCardForm.processing ? 'Processing...' : 'Update Card Details' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Hero Slide Modal -->
         <div class="modal fade" id="edit-hero-slide-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-hero-slide-modal-label" aria-hidden="true" ref="editHeroSlideModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-menu-modal-label">Edit Slide</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="heroEditFormCleanUp" :disabled="editHeroSlideForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="slideTitle" class="form-label-md mb-1">Title</label>
                              <input id="slideTitle" type="text" v-model="editHeroSlideForm.title" class="form-control">
                              <div v-if="editHeroSlideForm.errors.title" class="text-danger">{{ form.errors.title }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="subTitle" class="form-label-md mb-1">Subtitle</label>
                              <input id="subTitle" type="text" v-model="editHeroSlideForm.sub_title" class="form-control">
                              <div v-if="editHeroSlideForm.errors.sub_title" class="text-danger">{{ form.errors.sub_title }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageId" class="form-label-md mb-1">Page</label>
                              <v-select id="pageId" v-model="editHeroSlideForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id" />
                              <div v-if="editHeroSlideForm.errors.page_id" class="text-danger">{{ editHeroSlideForm.errors.page_id }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="slideButtonText" class="form-label-md mb-1">Button Text</label>
                              <input id="slideButtonText" type="text" v-model="editHeroSlideForm.cta_button_text" class="form-control">
                              <div v-if="editHeroSlideForm.errors.cta_button_text" class="text-danger">{{ editHeroSlideForm.errors.cta_button_text }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="slideButtonType" class="form-label-md mb-1">Button Type</label>
                              <v-select id="slideButtonType" v-model="editHeroSlideForm.cta_button_type" :options="ctaButtonTypes"
                                        label="name" :reduce="option => option.value" />
                              <div v-if="editHeroSlideForm.errors.cta_button_type" class="text-danger">{{ editHeroSlideForm.errors.cta_button_type }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="slideButtonType" class="form-label-md mb-1">Image</label>
                              <Dropzone v-model="editHeroSlideForm.media" :multiple="false"
                                        :existing="editHeroSlideForm.media ? editHeroSlideForm.media : []"
                                        :upload-url="`/wp-admin/medias/hero-slides/${editHeroSlideForm.slide_id}`"
                                        :delete-url="`/wp-admin/medias/hero-slides/${editHeroSlideForm.slide_id}/delete`"
                                        selectFileStrategy="replace"
                                        :showSelectButton="false" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="slideDetails" class="form-label-md mb-1">Details</label>
                              <editor id="slideDetails" v-model="editHeroSlideForm.details" class="editor-control"></editor>
                              <div v-if="editHeroSlideForm.errors.cta_button_text" class="text-danger">{{ editHeroSlideForm.errors.cta_button_text }}</div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="heroEditFormCleanUp" :disabled="editHeroSlideForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateHeroSlide" :disabled="editHeroSlideForm.processing">
                        {{ editHeroSlideForm.processing ? 'Processing...' : 'Update Slide Details' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Icon Box Modal -->
         <div class="modal fade" id="create-icon-box-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="create-icon-box-modal-label" aria-hidden="true" ref="createIconBoxModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="create-icon-box-modal-label">Add Icon Box</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="iconBoxEditFormCleanUp" :disabled="newIconBoxForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="title" class="form-label-md mb-1">Title</label>
                              <input type="text" v-model="newIconBoxForm.title" class="form-control" id="title" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="icon" class="form-label-md mb-1">Icon</label>
                              <v-select id="icon" v-model="newIconBoxForm.icon" :options="cardIcons"
                                        label="name" :reduce="option => option.value" >
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
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardLinkType" class="form-label-md mb-1">Link Type</label>
                              <v-select id="cardLinkType" v-model="newIconBoxForm.icon_link_type" :options="cardLinkTypes"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="newIconBoxForm.icon_link_type === 'url'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardUrl" class="form-label-md mb-1">Custom Url</label>
                              <input type="text" v-model="newIconBoxForm.custom_url" class="form-control" id="cardUrl" />
                           </div>
                        </div>
                        <div v-if="newIconBoxForm.icon_link_type === 'section'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="newIconBoxForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="newIconBoxForm.icon_link_type === 'page'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageId" class="form-label-md mb-1">Page</label>
                              <v-select id="pageId" v-model="newIconBoxForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id" >
                              </v-select>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="details" class="form-label-md mb-1">Details</label>
                              <editor v-model="newIconBoxForm.details" id="editor" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="newIconBoxFormCleanUp" :disabled="newIconBoxForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="uploadNewIconBox" :disabled="newIconBoxForm.processing">
                        {{ newIconBoxForm.processing ? 'Processing...' : 'Submit' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Icon Box Modal -->
         <div class="modal fade" id="edit-icon-box-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-icon-box-modal-label" aria-hidden="true" ref="editIconBoxModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-icon-box-modal-label">Edit Icon Box</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="iconBoxEditFormCleanUp" :disabled="editIconBoxForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="title" class="form-label-md mb-1">Title</label>
                              <input type="text" v-model="editIconBoxForm.title" class="form-control" id="title" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="icon" class="form-label-md mb-1">Icon</label>
                              <v-select id="icon" v-model="editIconBoxForm.icon" :options="cardIcons"
                                        label="name" :reduce="option => option.value" >
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
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardLinkType" class="form-label-md mb-1">Link Type</label>
                              <v-select id="cardLinkType" v-model="editIconBoxForm.icon_link_type" :options="cardLinkTypes"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="editIconBoxForm.icon_link_type === 'url'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="cardUrl" class="form-label-md mb-1">Custom Url</label>
                              <input type="text" v-model="editIconBoxForm.custom_url" class="form-control" id="cardUrl" />
                           </div>
                        </div>
                        <div v-if="editIconBoxForm.icon_link_type === 'section'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="editIconBoxForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value" >
                              </v-select>
                           </div>
                        </div>
                        <div v-if="editIconBoxForm.icon_link_type === 'page'" class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="pageId" class="form-label-md mb-1">Page</label>
                              <v-select id="pageId" v-model="editIconBoxForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id" >
                              </v-select>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="details" class="form-label-md mb-1">Details</label>
                              <editor v-model="editIconBoxForm.details" id="editor" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="iconBoxEditFormCleanUp" :disabled="editIconBoxForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateIconBox" :disabled="editIconBoxForm.processing">
                        {{ editIconBoxForm.processing ? 'Processing...' : 'Update Icon Box' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Section FAQ Modal -->
         <div class="modal fade" id="edit-section-faq-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-section-faq-modal-label" aria-hidden="true" ref="editSectionFaqModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-section-faq-modal-label">Edit FAQ</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="sectionFaqEditFormCleanUp" :disabled="editSectionFaqForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="question" class="form-label-md mb-1">Question</label>
                              <input type="text" v-model="editSectionFaqForm.question" class="form-control" id="question" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="answer" class="form-label-md mb-1">Answer</label>
                              <editor v-model="editSectionFaqForm.answer" id="answer" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="sectionFaqEditFormCleanUp" :disabled="editSectionFaqForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateSectionFaq" :disabled="editSectionFaqForm.processing">
                        {{ editSectionFaqForm.processing ? 'Processing...' : 'Update FAQ' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Section Testimonial Modal -->
         <div class="modal fade" id="edit-section-testimonial-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-section-testimonial-modal-label" aria-hidden="true" ref="editSectionTestimonialModal">
            <div class="modal-dialog modal-xl">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-section-testimonial-modal-label">Edit FAQ</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="sectionTestimonialEditFormCleanUp" :disabled="editSectionTestimonialForm.processing"></button>
                  </div>
                  <div class="modal-body">
                     <div class="row">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="testimonialName" class="form-label-md mb-1">Name</label>
                              <input type="text" v-model="editSectionTestimonialForm.name" class="form-control" id="testimonialName" />
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="testimonialOptional" class="form-label-md mb-1">Position <small class="text-muted ms-2">(optional)</small></label>
                              <input type="text" v-model="editSectionTestimonialForm.position" class="form-control" id="testimonialOption" />
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="details" class="form-label-md mb-1">Details</label>
                              <editor v-model="editSectionTestimonialForm.details" id="details" class="editor-control" />
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal" @click="sectionTestimonialEditFormCleanUp"
                             :disabled="editSectionTestimonialForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary" @click.prevent="updateSectionTestimonial"
                             :disabled="editSectionTestimonialForm.processing">
                        {{ editSectionTestimonialForm.processing ? 'Processing...' : 'Update Testimonial' }}
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Pricing Plan Modal -->
         <div class="modal fade" id="create-pricing-plan-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="create-pricing-plan-modal-label" aria-hidden="true" ref="createPricingPlanModal"
         >
            <div class="modal-dialog modal-xl modal-body-simple">
               <div class="modal-content">
                  <div class="modal-header pb-5">
                     <h5 class="modal-title" id="create-pricing-plan-modal-label">Add Pricing Plan</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="pricingPlanFormCleanUp" :disabled="pricingPlanForm.processing"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <div id="createForm" class="row" @submit.prevent="createPricingPlan">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="name" class="form-label-md mb-1">Name</label>
                              <input id="name" type="text" v-model="pricingPlanForm.name" class="form-control">
                              <div v-if="pricingPlanForm.errors.name" class="text-danger">{{ pricingPlanForm.errors.name }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="tagline" class="form-label-md mb-1">Tagline</label>
                              <input id="tagline" type="text" v-model="pricingPlanForm.tagline" class="form-control">
                              <div v-if="pricingPlanForm.errors.tagline" class="text-danger">{{ pricingPlanForm.errors.tagline }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="price" class="form-label-md mb-1">Price</label>
                              <money v-model="pricingPlanForm.price" id="price"></money>
                              <div v-if="pricingPlanForm.errors.price" class="text-danger">{{ pricingPlanForm.errors.price }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="billing_period" class="form-label-md mb-1">Billing Period</label>
                              <v-select id="billing_period" v-model="pricingPlanForm.billing_period" :options="pricingBillingPeriods"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="pricingPlanForm.errors.billing_period" class="text-danger">{{ pricingPlanForm.errors.billing_period }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="billing_period_label" class="form-label-md mb-1">Billing Period Label</label>
                              <input id="billing_period_label" type="text" v-model="pricingPlanForm.billing_period_label" class="form-control">
                              <div v-if="pricingPlanForm.errors.billing_period_label" class="text-danger">{{ pricingPlanForm.errors.billing_period_label }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="button_text" class="form-label-md mb-1">Button Text</label>
                              <input id="button_text" type="text" v-model="pricingPlanForm.button_text" class="form-control">
                              <div v-if="pricingPlanForm.errors.button_text" class="text-danger">{{ pricingPlanForm.errors.button_text }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="button_type" class="form-label-md mb-1">Button Type</label>
                              <v-select id="button_type" v-model="pricingPlanForm.button_type" :options="pricingPlanButtonTypes"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="pricingPlanForm.errors.button_type" class="text-danger">{{ pricingPlanForm.errors.button_type }}</div>
                           </div>
                        </div>
                        <div v-if="pricingPlanForm.button_type === 'url'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="buttonUrl" class="form-label-md mb-1">Button Url</label>
                              <input id="buttonUrl" type="text" v-model="pricingPlanForm.button_url" class="form-control">
                              <div v-if="pricingPlanForm.errors.button_url" class="text-danger">{{ pricingPlanForm.errors.button_url }}</div>
                           </div>
                        </div>
                        <div v-if="pricingPlanForm.button_type === 'section'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="pricingPlanForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="pricingPlanForm.errors.section_url" class="text-danger">{{ pricingPlanForm.errors.section_url }}</div>
                           </div>
                        </div>
                        <div v-if="pricingPlanForm.button_type === 'page'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="page_id" class="form-label-md mb-1">Page</label>
                              <v-select id="page_id" v-model="pricingPlanForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                              <div v-if="pricingPlanForm.errors.page_id" class="text-danger">{{ pricingPlanForm.errors.page_id }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="description" class="form-label-md mb-1">Description</label>
                              <editor v-model="pricingPlanForm.description" id="editor" class="editor-control" />
                              <div v-if="pricingPlanForm.errors.description" class="text-danger">{{ pricingPlanForm.errors.description }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3 mt-4">
                              <h5 class="mb-1">Details</h5>
                              <div class="row">
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="bedroom_label" class="form-label-md mb-1">Bedroom Label</label>
                                       <input id="bedroom_label" type="text" v-model="pricingPlanForm.bedroom_label" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="size" class="form-label-md mb-1">Size (sqft)</label>
                                       <input id="size" type="text" v-model="pricingPlanForm.size_sqft" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Deposit %</label>
                                       <input id="billing_period_label" type="text" v-model="pricingPlanForm.deposit_percentage" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Deposit Amount</label>
                                       <input id="billing_period_label" type="text" v-model="pricingPlanForm.deposit_amount" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Payment Duration (months)</label>
                                       <input id="billing_period_label" type="text" v-model="pricingPlanForm.payment_duration_months" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Monthly Payment</label>
                                       <input id="billing_period_label" type="text" v-model="pricingPlanForm.monthly_payment" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-5">
                              <label for="pricingFeatures" class="form-label-md mb-1 h5">Features</label>
                              <div class="table-responsive">
                                 <table class="table table-bordered table-hover table-center">
                                    <thead>
                                    <tr>
                                       <th>Name</th>
                                       <th>Feature Included</th>
                                       <th class="w__5"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(feature, index) in pricingPlanForm.features">
                                       <td>
                                          <input v-model="feature.name" type="text" class="form-control">
                                       </td>
                                       <td>
                                          <div class="d-flex justify-content-between align-items-center pt-2">
                                             <div class="d-flex justify-content-start">
                                                <div class="form-check form-switch">
                                                   <input v-model="feature.is_included" type="checkbox" class="form-check-input">
                                                </div>
                                             </div>
                                          </div>
                                       </td>
                                       <td>
                                          <a v-if="index > 0" href="javascript:void(0);" class="fw-medium btn btn-icon bg-danger-subtle text-danger"
                                             @click.prevent="removePricingFeature(index)">
                                             <i class="icon-base bx bxs-trash icon-lg"></i>
                                          </a>
                                       </td>
                                    </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <div class="row">
                                 <div class="col-12 mb-3">
                                    <button type="button" class="btn btn-secondary btn-soft-secondary w-100" @click.prevent="addPricingFeature">
                                       Add Feature
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label class="row d-flex">
                                 <span class="col d-flex">
                                    <span class="fw-bold me-3">Active</span>
                                    <span class="d-flex justify-content-between align-items-end">
                                       <label class="form-check form-switch">
                                          <input v-model="pricingPlanForm.active" class="form-check-input" type="checkbox">
                                       </label>
                                    </span>
                                 </span>
                                 <span class="form-check-description">When enabled, the pricing plan will be displayed on the website.</span>
                              </label>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer pt-5">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                             @click="pricingPlanFormCleanUp" :disabled="pricingPlanForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary"
                             @click.prevent="createPricingPlan" :disabled="pricingPlanForm.processing">
                        Submit
                     </button>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Edit Pricing Plan Modal -->
         <div class="modal fade" id="edit-pricing-plan-modal" data-bs-backdrop="static" tabindex="-1"
              aria-labelledby="edit-pricing-plan-modal-label" aria-hidden="true" ref="editPricingPlanModal"
         >
            <div class="modal-dialog modal-xl modal-body-simple">
               <div class="modal-content">
                  <div class="modal-header pb-5">
                     <h5 class="modal-title" id="create-pricing-plan-modal-label">Edit Pricing Plan</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                             @click="editPricingPlanFormCleanUp" :disabled="editPricingPlanForm.processing"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <div id="createForm" class="row" @submit.prevent="editPricingPlan">
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="name" class="form-label-md mb-1">Name</label>
                              <input id="name" type="text" v-model="editPricingPlanForm.name" class="form-control">
                              <div v-if="editPricingPlanForm.errors.name" class="text-danger">{{ editPricingPlanForm.errors.name }}</div>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="mb-3">
                              <label for="tagline" class="form-label-md mb-1">Tagline</label>
                              <input id="tagline" type="text" v-model="editPricingPlanForm.tagline" class="form-control">
                              <div v-if="editPricingPlanForm.errors.tagline" class="text-danger">{{ editPricingPlanForm.errors.tagline }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="price" class="form-label-md mb-1">Price</label>
                              <money v-model="editPricingPlanForm.price" id="price"></money>
                              <div v-if="editPricingPlanForm.errors.price" class="text-danger">{{ editPricingPlanForm.errors.price }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="billing_period" class="form-label-md mb-1">Billing Period</label>
                              <v-select id="billing_period" v-model="editPricingPlanForm.billing_period" :options="pricingBillingPeriods"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="editPricingPlanForm.errors.billing_period" class="text-danger">{{ editPricingPlanForm.errors.billing_period }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="billing_period_label" class="form-label-md mb-1">Billing Period Label</label>
                              <input id="billing_period_label" type="text" v-model="editPricingPlanForm.billing_period_label" class="form-control">
                              <div v-if="editPricingPlanForm.errors.billing_period_label" class="text-danger">{{ editPricingPlanForm.errors.billing_period_label }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="button_text" class="form-label-md mb-1">Button Text</label>
                              <input id="button_text" type="text" v-model="editPricingPlanForm.button_text" class="form-control">
                              <div v-if="editPricingPlanForm.errors.button_text" class="text-danger">{{ editPricingPlanForm.errors.button_text }}</div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="button_type" class="form-label-md mb-1">Button Type</label>
                              <v-select id="button_type" v-model="editPricingPlanForm.button_type" :options="pricingPlanButtonTypes"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="editPricingPlanForm.errors.button_type" class="text-danger">{{ editPricingPlanForm.errors.button_type }}</div>
                           </div>
                        </div>
                        <div v-if="editPricingPlanForm.button_type === 'url'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="buttonUrl" class="form-label-md mb-1">Button Url</label>
                              <input id="buttonUrl" type="text" v-model="editPricingPlanForm.button_url" class="form-control">
                              <div v-if="editPricingPlanForm.errors.button_url" class="text-danger">{{ editPricingPlanForm.errors.button_url }}</div>
                           </div>
                        </div>
                        <div v-if="editPricingPlanForm.button_type === 'section'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="sectionUrl" class="form-label-md mb-1">Section</label>
                              <v-select id="sectionUrl" v-model="editPricingPlanForm.section_url" :options="sectionUrls"
                                        label="name" :reduce="option => option.value"
                              />
                              <div v-if="editPricingPlanForm.errors.section_url" class="text-danger">{{ editPricingPlanForm.errors.section_url }}</div>
                           </div>
                        </div>
                        <div v-if="editPricingPlanForm.button_type === 'page'" class="col-md-4 col-12">
                           <div class="mb-3">
                              <label for="page_id" class="form-label-md mb-1">Page</label>
                              <v-select id="page_id" v-model="editPricingPlanForm.page_id" :options="pages"
                                        label="title" :reduce="option => option.id"
                              />
                              <div v-if="editPricingPlanForm.errors.page_id" class="text-danger">{{ editPricingPlanForm.errors.page_id }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3">
                              <label for="description" class="form-label-md mb-1">Description</label>
                              <editor v-model="editPricingPlanForm.description" id="editor" class="editor-control" />
                              <div v-if="editPricingPlanForm.errors.description" class="text-danger">{{ editPricingPlanForm.errors.description }}</div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-3 mt-4">
                              <h5 class="mb-1">Details</h5>
                              <div class="row">
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="bedroom_label" class="form-label-md mb-1">Bedroom Label</label>
                                       <input id="bedroom_label" type="text" v-model="editPricingPlanForm.bedroom_label" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="size" class="form-label-md mb-1">Size (sqft)</label>
                                       <input id="size" type="text" v-model="editPricingPlanForm.size_sqft" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Deposit %</label>
                                       <input id="billing_period_label" type="text" v-model="editPricingPlanForm.deposit_percentage" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Deposit Amount</label>
                                       <input id="billing_period_label" type="text" v-model="editPricingPlanForm.deposit_amount" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Payment Duration (months)</label>
                                       <input id="billing_period_label" type="text" v-model="editPricingPlanForm.payment_duration_months" class="form-control">
                                    </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                    <div class="mb-3">
                                       <label for="billing_period_label" class="form-label-md mb-1">Monthly Payment</label>
                                       <input id="billing_period_label" type="text" v-model="editPricingPlanForm.monthly_payment" class="form-control">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="mb-5">
                              <label for="pricingFeatures" class="form-label-md mb-1 h5">Pricing Plan Features</label>
                              <div class="table-responsive">
                                 <table class="table table-bordered table-hover table-center">
                                    <thead>
                                    <tr>
                                       <th>Name</th>
                                       <th>Feature Included</th>
                                       <th class="w__5"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(feature, index) in editPricingPlanForm.features">
                                       <td>
                                          <input v-model="feature.name" type="text" class="form-control">
                                       </td>
                                       <td>
                                          <div class="d-flex justify-content-between align-items-center pt-2">
                                             <div class="d-flex justify-content-start">
                                                <div class="form-check form-switch">
                                                   <input v-model="feature.is_included" type="checkbox" class="form-check-input">
                                                </div>
                                             </div>
                                          </div>
                                       </td>
                                       <td>
                                          <a v-if="index > 0" href="javascript:void(0);" class="fw-medium btn btn-icon bg-danger-subtle text-danger"
                                             @click.prevent="removePricingFeatureOnEditForm(index)">
                                             <i class="icon-base bx bxs-trash icon-lg"></i>
                                          </a>
                                       </td>
                                    </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <div class="row">
                                 <div class="col-12 mb-3">
                                    <button type="button" class="btn btn-secondary btn-soft-secondary w-100" @click.prevent="addPricingFeatureOnEditForm">
                                       Add Feature
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label class="row">
                                 <span class="d-flex">
                                    <span class="fw-bold me-3">Featured</span>
                                    <span class="d-flex justify-content-between align-items-end">
                                       <label class="form-check form-switch">
                                          <input v-model="editPricingPlanForm.featured" class="form-check-input" type="checkbox">
                                       </label>
                                    </span>
                                 </span>
                                 <span class="form-check-description">When enabled, the pricing plan will be highlighted as the most popular option.</span>
                              </label>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="mb-3">
                              <label class="row">
                                 <span class="d-flex">
                                    <span class="fw-bold me-3">Active</span>
                                    <span class="d-flex justify-content-between align-items-end">
                                       <label class="form-check form-switch">
                                          <input v-model="editPricingPlanForm.active" class="form-check-input" type="checkbox">
                                       </label>
                                    </span>
                                 </span>
                                 <span class="form-check-description">When enabled, the pricing plan will be displayed on the website.</span>
                              </label>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer pt-5">
                     <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"
                             @click="editPricingPlanFormCleanUp" :disabled="editPricingPlanForm.processing">
                        Close
                     </button>
                     <button type="button" class="btn btn-primary"
                             @click.prevent="updatePricingPlan" :disabled="editPricingPlanForm.processing">
                        Submit
                     </button>
                  </div>
               </div>
            </div>
         </div>
      
      </div>
   </DefaultLayout>
</template>

<script>
import SectionManagementLayout from "@layouts/SectionManagementLayout.vue";
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import axios from "axios";
import _debounce from "lodash/debounce.js";
import {Modal} from "bootstrap";
import Editor from "@components/global/Editor.vue";
import Dropzone from "@components/global/Dropzone.vue";
import Money from "@components/global/Money.vue";

export default {
   components: {Dropzone, SectionManagementLayout, Editor, DefaultLayout, Head, Link, Money},
   props: {
      page: {
         type: Object,
         required: true,
      },
      sectionTypes: {
         type: Array,
         required: true,
      }
   },
   data() {
      return {
         form: useForm({
            page_id: this.page.id,
            spa_section_name: null,
            type: null,
            sub_title: null,
            title: null,
            details: null,
            component_type: null,
            section_image_first: false,
            include_contact_cards: false,
            has_cta_buttons: false,
            cta_buttons: [],
            section_cards: [],
            hero_slides: [],
            icon_boxes: [],
            section_faqs: [],
            section_testimonials: [],
            section_has_image: false,
            section_has_map: false,
            sliding_hero_section: false,
            section_has_bg: false,
            section_background_type: null,
            section_background_color: null,
            section_style: null,
            width_style: null,
            width: null,
            form_title: null,
            form_sub_title: null,
            form_button_text: null,
            video_type: null,
            video_link: null,
            background_image: null,
            media: null,
            map_link: null,
            category_id: null,
            gallery_media: [],
         }),
         editForm: useForm({
            index: null,
            page_id: this.page.id,
            section_id: null,
            spa_section_name: null,
            type: null,
            sub_title: null,
            title: null,
            details: null,
            component_type: null,
            include_contact_cards: false,
            section_image_first: false,
            has_cta_buttons: false,
            cta_buttons: [],
            section_cards: [],
            hero_slides: [],
            icon_boxes: [],
            section_faqs: [],
            section_testimonials: [],
            section_has_image: false,
            section_has_map: false,
            sliding_hero_section: false,
            section_has_bg: false,
            section_background_type: null,
            section_background_color: null,
            section_style: null,
            width_style: null,
            width: null,
            form_title: null,
            form_sub_title: null,
            form_button_text: null,
            video_type: null,
            video_link: null,
            background_image: null,
            media: null,
            map_link: null,
            category_id: null,
         }),
         mediaForm: useForm({
            section_id: '',
            file: null,
         }),
         backgroundMediaForm: useForm({
            section_id: '',
            file: null,
         }),
         ctaButtonForm: useForm({
            cta_link_type: null,
            custom_url: null,
            section_url: null,
            page: null,
            cta_button_text: '',
            cta_button_type: '',
         }),
         newCtaButtonForm: useForm({
            section_id: null,
            cta_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
            cta_button_text: '',
            cta_button_type: '',
         }),
         editCtaButtonForm: useForm({
            id: '',
            section_id: null,
            cta_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
            cta_button_text: '',
            cta_button_type: '',
         }),
         sectionCardForm: useForm({
            title: null,
            icon: null,
            details: null,
            card_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
         }),
         newSectionCardForm: useForm({
            section_id: null,
            title: null,
            icon: null,
            details: null,
            card_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
            media: null,
         }),
         editSectionCardForm: useForm({
            id: null,
            card_id: null,
            section_id: null,
            title: null,
            icon: null,
            details: null,
            card_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
            media: [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }],
         }),
         heroSlideForm: useForm({
            section_id: null,
            page: null,
            title: null,
            sub_title: null,
            details: null,
            cta_button_text: '',
            cta_button_type: '',
            slide_image: null,
         }),
         newHeroSlideForm: useForm({
            section_id: null,
            page_id: null,
            title: null,
            sub_title: null,
            details: null,
            cta_button_text: '',
            cta_button_type: '',
            file: null,
         }),
         editHeroSlideForm: useForm({
            id: null,
            slide_id: null,
            section_id: null,
            page_id: null,
            title: null,
            sub_title: null,
            details: null,
            media: null,
            cta_button_text: '',
            cta_button_type: '',
         }),
         iconBoxForm: useForm({
            title: null,
            icon: null,
            details: null,
            card_link_type: null,
            card_url: null,
            section_url: null,
            page_id: null,
         }),
         newIconBoxForm: useForm({
            section_id: null,
            title: null,
            icon: null,
            details: null,
            icon_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
         }),
         editIconBoxForm: useForm({
            id: null,
            section_id: null,
            title: null,
            icon: null,
            details: null,
            icon_link_type: null,
            custom_url: null,
            section_url: null,
            page_id: null,
         }),
         sectionFaqForm: useForm({
            question: null,
            answer: null,
         }),
         newSectionFaqForm: useForm({
            section_id: null,
            question: null,
            answer: null,
         }),
         editSectionFaqForm: useForm({
            id: null,
            section_id: null,
            question: null,
            answer: null,
         }),
         sectionTestimonialForm: useForm({
            name: null,
            position: null,
            details: null,
         }),
         newSectionTestimonialForm: useForm({
            section_id: null,
            name: null,
            position: null,
            details: null,
         }),
         editSectionTestimonialForm: useForm({
            id: null,
            section_id: null,
            name: null,
            position: null,
            details: null,
         }),
         sectionGalleryForm: useForm({
            section_id: null,
            files: [],
            file: null,
         }),
         pricingPlanForm: useForm({
            section_id: null,
            name: null,
            tagline: null,
            description: null,
            price: 0,
            billing_period: null,
            billing_period_label: null,
            button_text: '',
            button_type: '',
            button_url: null,
            section_url: null,
            page_id: null,
            featured: false,
            active: true,
            bedroom_label: '',
            size_sqft: '',
            deposit_percentage: '',
            deposit_amount: '',
            payment_duration_months: '',
            monthly_payment: '',
            features: [
               {
                  name: '',
                  order: '',
                  is_included: false,
               }
            ],
         }),
         editPricingPlanForm: useForm({
            id: null,
            section_id: null,
            name: null,
            slug: null,
            tagline: null,
            description: null,
            price: 0,
            billing_period: null,
            billing_period_label: null,
            button_text: '',
            button_type: '',
            button_url: null,
            section_url: null,
            page_id: null,
            featured: false,
            active: true,
            bedroom_label: '',
            size_sqft: '',
            deposit_percentage: '',
            deposit_amount: '',
            payment_duration_months: '',
            monthly_payment: '',
            features: [
               {
                  name: '',
                  order: '',
                  is_included: false,
               }
            ],
         }),
         moveSectionForm: useForm({
            page_id: this.page.id,
            section_id: '',
            new_page_id: '',
         }),
         existingGalleryImages: [],
         pages: [],
         pageSections: [],
         openAccordion: '',
         componentTypes: [
            {value: 'services', name: 'Services'},
            {value: 'projects', name: 'Projects'},
            {value: 'blogs', name: 'Blogs'},
            {value: 'events', name: 'Events'},
            {value: 'board-members', name: 'Board Members'},
            {value: 'faqs', name: 'FAQs'},
         ],
         ctaButtonTypes: [
            {value: 'btn-primary', name: 'Primary Button'},
            {value: 'btn-secondary', name: 'Secondary Button'},
            {value: 'btn-info', name: 'Info Button'},
            {value: 'btn-success', name: 'Success Button'},
            {value: 'btn-warning', name: 'Warning Button'},
            {value: 'btn-danger', name: 'Danger Button'},
            {value: 'btn-dark', name: 'Dark Button'},
            {value: 'btn-default', name: 'Default Button'},
         ],
         cardIcons: [
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
         sectionUrls: [],
         cardLinkTypes: [
            {value: 'url', name: 'Custom Url'},
            {value: 'section', name: 'Section'},
            {value: 'page', name: 'Page'},
         ],
         videoLinkTypes: [
            {value: 'local', name: 'Self Hosted'},
            {value: 'youtube', name: 'Youtube'},
            {value: 'vimeo', name: 'Vimeo'},
            {value: 'onedrive', name: 'Onedrive'},
            {value: 'gdrive', name: 'Google Drive'},
         ],
         sectionBackgrounds: [
            {value: 'image-bg', name: 'Image Background'},
            {value: 'color-bg', name: 'Color Background'},
         ],
         sectionBgOverlays: [
            {value: 'bg-overlay', name: 'Default'},
            {value: 'bg-overlay-gray', name: 'Gray'},
            {value: 'bg-overlay-black', name: 'Black'},
            {value: 'bg-overlay-primary', name: 'Primary'},
            {value: 'bg-overlay-primary-light', name: 'Primary Light'},
            {value: 'bg-overlay-secondary', name: 'Secondary'},
            {value: 'bg-overlay-secondary-light', name: 'Secondary Light'},
            {value: 'bg-overlay-gradient', name: 'Gradient'},
            {value: 'bg-overlay-gradient-light', name: 'Gradient Light'},
         ],
         sectionWidthStyles: [
            {value: 'container', name: 'Boxed'},
            {value: 'container-fluid', name: 'Full Width'},
         ],
         heroSectionDesigns: [
            {value: 'hero-with-image-content', name: 'Hero With Image Content', description: 'Similar to the default layout but the hero image is included in the hero content instead as a background.'},
            {value: 'hero-with-image-content-design-two', name: 'Hero With Image Content Design Two', description: 'Similar to the default layout but the hero image is included in the hero content instead as a background.'},
            {value: 'hero-with-image-content-design-three', name: 'Hero With Image Content Design Three', description: 'Similar to the default layout but the hero image is included in the hero content instead as a background.'},
            {value: 'hero-aligned-left', name: 'Text Aligned Left', description: 'Simple hero section with the details aligned to the left'},
            {value: 'hero-with-carousel', name: 'Hero With Carousel', description: 'A hero section a sliding carousel'},
            {value: 'hero-with-gallery', name: 'Hero With Gallery Carousel', description: 'A hero section a sliding carousel'},
            {value: 'hero-with-carousel-aligned-left', name: 'Hero With Carousel Text Aligned Left', description: 'A hero section a sliding carousel and its details are aligned to the left.'},
            {value: 'hero-with-video', name: 'Hero With Video', description: 'A hero section with a video playinh'},
         ],
         serviceComponentDesigns: [
            // {value: 'services-default', name: 'Classic Grid', description: 'Evenly spaced service cards in a simple grid layout.',},
            {value: 'services-without-image', name: 'Text-Only', description: 'Simple list of services without any images.',},
            {value: 'services-in-carousel', name: 'Scrolling Carousel', description: 'Swipe or click through services in a horizontal carousel.',},
            {value: 'services-blog-design', name: 'Blog Style', description: 'Service cards with large images and descriptions, similar to blog posts.',}
         ],
         projectComponentDesigns: [
            // {value: 'projects-without-image', name: 'Text-Only', description: 'Simple list of projects without any images.',},
            {value: 'projects-in-carousel', name: 'Scrolling Carousel', description: 'Swipe or click through projects in a horizontal carousel.',},
            {value: 'projects-blog-design', name: 'Blog Style', description: 'Project cards with large images and descriptions, similar to blog posts.',}
         ],
         partnersComponentDesigns: [
            {value: 'partners-in-carousel', name: 'Partners In Carousel Slider', description: 'Partners displayed in a carousel slider.'},
         ],
         sectionCardDesigns: [
            // {value: 'cards-default', name: 'Default Layout', description: 'Large cards with centered icons and text. Displays 2 cards per row.'},
            {value: 'cards-vertical-layout', name: 'Vertical Layout', description: 'Compact cards with icon, title, and description stacked vertically. Great for showing many items at once.'},
            {value: 'cards-carousel-layout', name: 'Cards In Carousel', description: 'Small vertical-style cards displayed inside a sliding carousel. Useful when you have many cards.'},
            {value: 'cards-inline-layout', name: 'Inline Layout', description: 'Cards with the icon and title aligned side-by-side on the same row, followed by the description below.'},
            {value: 'cards-with-image', name: 'Cards With Image', description: 'Cards with an image displayed.'},
            // {value: 'testimonial-in-carousel', name: 'Inline Layout', description: 'Cards in a testimonial structure'}
         ],
         sectionWithoutImageDesigns: [
            {value: 'section-aligned-left', name: 'Left Aligned Layout', description: 'Section Details are aligned to the left side of the section.'},
            {value: 'section-aligned-left-2', name: 'Left Aligned Layout Two', description: 'Section Details are aligned to the left side of the section.'}
         ],
         sectionFormDesigns: [
            {value: 'form-aligned-right', name: 'Form Aligned To The Right ', description: 'Section with the form aligned to the right side of the section.'},
            {value: 'form-aligned-left', name: 'Form Aligned To The Left', description: 'Section with the form aligned to the left side of the section.'},
            {value: 'form-with-icon-cards', name: 'Form With Icon Cards', description: 'Section with icon cards showing contact information on the form.'},
         ],
         sectionIconBoxDesigns: [
            {value: 'icon-boxes-without-image', name: 'Icon Box Section Without Image', description: 'A section with icon boxes without an image.'},
            {value: 'icon-boxes-without-image-two', name: 'Icon Box Section Without Image Design Two', description: 'A section with icon boxes without an image.'},
            {value: 'icon-boxes-without-image-left', name: 'Icon Box Section Without Image Left Aligned', description: 'A section with icon boxes without an image but the icon boxes are on the left side of the section.'},
         ],
         sectionFaqDesigns: [
            {value: 'faqs-without-image', name: 'Section FAQs Without Image', description: 'A section with faqs displayed without an image.'},
            {value: 'faqs-in-two-columns', name: 'Section FAQs In Two Columns', description: 'A section with faqs displayed in two columns. This style does not include an image'},
         ],
         sectionTestimonialDesigns: [
            {value: 'testimonials-in-carousel-design-two', name: 'Testimonials In Carousel Design Two', description: ''},
         ],
         galleryDesigns: [
            {value: 'gallery-in-carousel', name: 'Gallery In Carousel', description: ''},
         ],
         sectionPricingPlanDesigns: [
            {value: 'pricing-plans-design-two', name: 'Section Pricing Plans Design Two', description: ''},
            {value: 'pricing-plans-design-three', name: 'Section Pricing Plans Design Three', description: ''},
            {value: 'pricing-plans-design-four', name: 'Section Pricing Plans Design Four', description: ''},
            {value: 'pricing-plans-design-five', name: 'Section Pricing Plans Design Five', description: ''},
            {value: 'pricing-plans-design-six', name: 'Section Pricing Plans Design Six', description: ''},
            {value: 'pricing-plans-sticky', name: 'Pricing Plan Sticky', description: 'A pricing plan that sticks at the bottom of your screen as you scroll.'},
         ],
         pricingBillingPeriods: [
            {value: 'monthly', name: 'Monthly'},
            {value: 'yearly', name: 'Yearly'},
            {value: 'one-time', name: 'One-Time'},
            {value: 'custom', name: 'Custom'},
         ],
         pricingPlanButtonTypes: [
            {value: 'url', name: 'Custom Url'},
            {value: 'section', name: 'Section'},
            {value: 'page', name: 'Page'},
         ],
         sectionEcommerceProductStyles: [
            {value: 'ecm-products-in-list', name: 'E-commerce Products In A List', description: 'Section with Ecommerce Products in a list table'},
            {value: 'ecm-products-in-carousel', name: 'E-commerce Products In A Carousel', description: 'Section with Ecommerce Products in a carousel extending the whole width of the site'},
         ],
         sectionEcommerceProductCategoryStyles: [
            {value: 'ecm-products-category-slider', name: 'E-commerce Products In A Slider', description: 'Displays e-commerce products from a specific category in a scrollable or sliding carousel.'},
            {value: 'ecm-category-listing', name: 'E-commerce Category Grid', description: 'Displays e-commerce product categories in a grid layout with clickable images linking to each category page.'}
         ],
         ecmCategories: [],
      }
   },
   watch: {
      'form.type': function(newVal) {
         this.form.section_has_image = ['hero-section', 'section-with-image', 'why-us-section', 'section-with-icon-box',
            'section-with-faqs', 'section-with-video'].includes(newVal);
         if (!this.form.section_has_image) this.form.media = null;
         if(newVal) {
            this.form.sliding_hero_section = false;
            this.form.section_has_bg = false;
            this.form.section_background_type = null;
            this.form.section_background_color = null;
         }
         if(newVal !== 'section-with-services') {
            this.form.section_style = null;
         }
      },
      'form.section_has_image': function(val) {
         this.form.include_contact_cards = false;
      },
      'form.include_contact_cards': function(newVal) {
         if (newVal) {
            this.form.section_has_image = false;
         }
      },
      'form.section_has_bg': function(val) {
         if(!val) {
            this.form.section_background_type = null;
            this.form.section_background_color = null;
            this.form.background_image = null;
         }
      },
      'editForm.type': function(newVal) {
         this.editForm.section_has_image = ['hero-section', 'section-with-image', 'why-us-section', 'section-with-icon-box',
            'section-with-faqs', 'section-with-video'].includes(newVal);
         // if(newVal !== 'hero-section') {
         //    this.editForm.sliding_hero_section = false;
         //    this.editForm.section_has_bg = false;
         //    this.editForm.section_background_type = null;
         //    this.editForm.section_background_color = null;
         // }
      },
      'editForm.section_has_image': function(val) {
         this.editForm.include_contact_cards = false;
      },
      'editForm.include_contact_cards': function(newVal) {
         if (newVal) {
            this.editForm.section_has_image = false;
         }
      },
      'editForm.section_has_bg': function(val) {
         if(!val) {
            this.editForm.section_background_type = null;
            this.editForm.section_background_color = null;
            this.editForm.background_image = null;
         }
      },
   },
   computed: {
      ecommerce() {
         return this.$page.props.modules.find(m => m.slug === 'ecommerce' && m.installed && m.enabled);
      },
      availablePagesToMoveSectionsTo() {    // omit the current page from the list
         return this.pages.filter(page => page.id !== this.page.id)
      },
   },
   mounted() {
      this.fetchPages();
      this.fetchSections();
      this.fetchEcommerceProductCategories();
   },
   methods: {
      truncate(value, length) {
         if (value && value.length > length) {
            return value.substring(0, length) + "...";
         } else {
            return value;
         }
      },
      toggleAccordion(sectionKey, section) {
         this.openAccordion = this.openAccordion === sectionKey ? null : sectionKey;
         this.editForm.reset();
         this.editForm.clearErrors();
         this.editForm.media = null;
         if(this.openAccordion === sectionKey && section) {
            this.populateEditForm(section.id);
         } else {
            this.editForm.reset();
            this.editForm.clearErrors();
            this.editForm.media = null;
         }
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
      fetchSections() {
         axios.get(route('datatable.sections'), {
            params: {
               filter: {
                  'page_id': this.page.id,
               }
            }
         })
            .then(({data}) => {
               this.pageSections = data.data;
               if(this.pageSections) {
                  this.populateSectionUrls(this.pageSections);
               }
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the page sections!", "Error");
         })
      },
      populateSectionUrls(pageSections) {
         return this.sectionUrls = pageSections
            .filter(section => section.spa_section_name && section.spa_section_id)
            .map(section => ({
               name: section.spa_section_name,
               value: section.spa_section_id,
            }));
      },
      fetchEcommerceProductCategories() {
         if(this.ecommerce) {
            axios.get(route('admin.ecm_datatable.categories'), {
               params: {
                  filter: {
                     'active': true,
                  }
               }
            })
               .then(({data}) => {
                  this.ecmCategories = data.data;
               }).catch((error) => {
               console.log(error);
               this.$toast.error("An error occurred while fetching the ecommerce categories!", "Error");
            })
         }
      },
      handleDraggableChange: _debounce(function(event) {
         if (event.moved) {
            console.log('Moved item:', event.moved.element);
            const orderedIds = this.pageSections.map((section, index) => ({
               id: section.id,
               order: index + 1
            }));
            
            this.$inertia.post('/wp-admin/sections/update-section-order', { sections: orderedIds }, {
               onSuccess: () => {
                  console.log('Section order updated successfully');
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Sections order updated successfully', 'Success');
                     this.openAccordion = '';
                  }, 400)
               },
               onError: (error) => {
                  console.log(error);
               }
            })
         }
      }, 500),
      handleGalleryFiles(fileObj) {
         if (Array.isArray(fileObj)) {
            this.form.gallery_media = fileObj.map(f => f.file).filter(Boolean);
         } else {
            if (!this.form.gallery_media) this.form.gallery_media = [];
            if (fileObj.file) this.form.gallery_media.push(fileObj.file);
         }
      },
      handleGalleryFileRemoved({ file, id }) {
         const index = this.form.gallery_media.findIndex(f => f === file);
         if (index > -1) {
            this.form.gallery_media.splice(index, 1);
         }
      },
      populateEditForm(sectionId) {
         let section = this.pageSections.find((sec) => sec.id === sectionId);
         if(section) {
            this.editForm.section_id = section.id;
            this.editForm.type = section.type;
            this.editForm.spa_section_name = section.spa_section_name;
            this.editForm.title = section.title;
            this.editForm.sub_title = section.sub_title;
            this.editForm.component_type = section.component_type;
            this.editForm.details = section.details;
            this.editForm.include_contact_cards = section.include_contact_cards;
            this.editForm.section_has_image = section.section_has_image;
            this.editForm.section_image_first = section.section_image_first;
            this.editForm.has_cta_buttons = section.has_cta_buttons;
            this.editForm.section_has_map = section.section_has_map;
            this.editForm.sliding_hero_section = section.sliding_hero_section;
            this.editForm.section_has_bg = section.section_has_bg;
            this.editForm.section_background_type = section.section_background_type;
            this.editForm.section_background_color = section.section_background_color;
            this.editForm.section_style = section.section_style;
            this.editForm.width_style = section.width_style;
            this.editForm.width = section.width;
            this.editForm.form_title = section.form_title;
            this.editForm.form_sub_title = section.form_sub_title;
            this.editForm.form_button_text = section.form_button_text;
            this.editForm.video_type = section.video_type;
            this.editForm.video_link = section.video_link;
            this.editForm.hero_slides = section.hero_slides;
            this.editForm.icon_boxes = section.icon_boxes;
            this.editForm.section_faqs = section.faqs;
            this.editForm.section_testimonials = section.testimonials;
            this.editForm.media = section.media?.find(md => md.collection_name === 'section_image') ?? null;
            this.editForm.background_image = section.media?.find(md => md.collection_name === 'section_bg') ?? null;
            this.editForm.cta_buttons = section.cta_buttons;
            this.editForm.map_link = section.map_link;
            this.editForm.category_id = section.section_link?.linkable.id ?? null;
            
            this.sectionGalleryForm.section_id = section.id;
            
            const sectionGallery = section.media?.filter(md => md.collection_name === 'section-gallery') ?? null;
            
            if(sectionGallery && sectionGallery.length > 0) {
               this.existingGalleryImages = sectionGallery.map((m) => ({
                  id: m.id,
                  url: m.original_url,
               })) || []
               
               this.sectionGalleryForm.files = sectionGallery.map(img => ({
                  id: img.id,
                  model_id: img.model_id,
                  name: img.file_name,
                  size: img.size,
                  url: img.original_url,
                  type: img.mime_type,
                  isExisting: true,
               }))
            }
            
         }
      },
      openAddSectionModal() {
         const modalElement = this.$refs.addSectionModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
         this.openAccordion = '';
      },
      openMoveSectionModal(section) {
         console.log("selected section: ", section)
         this.moveSectionForm.section_id = section.id;
         
         this.openAccordion = '';
         
         const modalElement = this.$refs.moveSectionModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      moveSectionToAnotherPage() {
         if(!this.moveSectionForm.new_page_id) {
            this.$toast.info('Select the page first!', 'Info')
            return;
         }
         
         const sectionId = this.moveSectionForm.section_id;
         
         this.moveSectionForm.post(route('admin.pages.sections.move-section'), {
            onSuccess: () => {
               this.moveSectionForm.reset();
               this.moveSectionForm.clearErrors();
               
               const modalElement = this.$refs.moveSectionModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               
               setTimeout(() => {
                  this.$toast.success('Section moved successfully', 'Updated');
                  this.fetchSections();
                  // this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            }
         })
      },
      addCtaButtonToForm() {
         // if (!this.ctaButtonForm.page) {
         //    this.$toast.info('Select a page first', 'Info');
         //    return;
         // }
         if (!this.ctaButtonForm.cta_button_text) {
            this.$toast.info('Label the CTA button first', 'Info');
            return;
         }
         this.form.cta_buttons.push({
            page: this.ctaButtonForm.page,
            cta_button_text: this.ctaButtonForm.cta_button_text,
            cta_button_type: this.ctaButtonForm.cta_button_type,
         });
         
         this.ctaButtonForm.reset();
         this.ctaButtonForm.clearErrors();
         this.$toast.success('CTA button added!', 'Success');
      },
      removeCtaButton(index) {
         this.form.cta_buttons.splice(index, 1);
      },
      addSectionCardToForm() {
         if (!this.sectionCardForm.title) {
            this.$toast.info('Give the card a title first!', 'Info');
            return;
         }
         if (!this.sectionCardForm.details) {
            this.$toast.info('Add card details first!', 'Info');
            return;
         }
         this.form.section_cards.push({
            title: this.sectionCardForm.title,
            icon: this.sectionCardForm.icon,
            details: this.sectionCardForm.details,
         });
         this.sectionCardForm.reset();
         this.sectionCardForm.clearErrors();
         this.$toast.success('Card added to section!', 'Success');
      },
      removeSectionCard(index) {
         this.form.section_cards.splice(index, 1);
      },
      addSlideToForm() {
         // if (!this.heroSlideForm.title) {
         //    this.$toast.info('Add a title to the slide first!', 'Info');
         //    return;
         // }
         if (!this.heroSlideForm.slide_image) {
            this.$toast.info('Add an image first!', 'Info');
            return;
         }
         // if (!this.heroSlideForm.details) {
         //    this.$toast.info('Add slide details first', 'Info');
         //    return;
         // }
         this.form.hero_slides.push({
            title: this.heroSlideForm.title,
            sub_title: this.heroSlideForm.sub_title,
            details: this.heroSlideForm.details,
            page: this.heroSlideForm.page,
            slide_image: this.heroSlideForm.slide_image,
            cta_button_text: this.heroSlideForm.cta_button_text,
            cta_button_type: this.heroSlideForm.cta_button_type,
         });
         
         this.heroSlideForm.reset();
         this.heroSlideForm.clearErrors();
         this.$toast.success('Hero slide added!', 'Success');
      },
      removeSlide(index) {
         this.form.hero_slides.splice(index, 1);
      },
      handleSectionMedia(event) {
         const file = event.target.files[0];
         if (!file) {
            this.form.media = null;
            return;
         }
         const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         const maxSizeMB = 5;
         if (!allowedTypes.includes(file.type)) {
            this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
            event.target.value = ''; // reset input
            return;
         }
         if (file.size > maxSizeMB * 1024 * 1024) {
            this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
            event.target.value = '';
            return;
         }
         this.form.media = file;
      },
      addIconBoxToForm() {
         // if (!this.iconBoxForm.title) {
         //    this.$toast.info('Give the card a title first!', 'Info');
         //    return;
         // }
         // if (!this.iconBoxForm.details) {
         //    this.$toast.info('Add card details first!', 'Info');
         //    return;
         // }
         this.form.icon_boxes.push({
            title: this.iconBoxForm.title,
            icon: this.iconBoxForm.icon,
            details: this.iconBoxForm.details,
            card_link_type: this.iconBoxForm.card_link_type,
            card_url: this.iconBoxForm.card_url,
            section_url: this.iconBoxForm.section_url,
            page_id: this.iconBoxForm.page_id,
         });
         this.iconBoxForm.reset();
         this.iconBoxForm.clearErrors();
         this.$toast.success('Icon Box added to section!', 'Success');
      },
      removeIconBox(index) {
         this.form.icon_boxes.splice(index, 1);
      },
      addSectionFaqToForm() {
         if (!this.sectionFaqForm.question) {
            this.$toast.info('Provide a question for the faq first!', 'Info');
            return;
         }
         if (!this.sectionFaqForm.answer) {
            this.$toast.info('Provide an answer to the faq first!', 'Info');
            return;
         }
         this.form.section_faqs.push({
            question: this.sectionFaqForm.question,
            answer: this.sectionFaqForm.answer,
         });
         this.sectionFaqForm.reset();
         this.sectionFaqForm.clearErrors();
         this.$toast.success('Section FAQ added to section!', 'Success');
      },
      removeSectionFaq(index) {
         this.form.section_faqs.splice(index, 1);
      },
      addSectionTestimonialToForm() {
         if (!this.sectionTestimonialForm.name) {
            this.$toast.info('Provide a name for the testimonial first!', 'Info');
            return;
         }
         if (!this.sectionTestimonialForm.details) {
            this.$toast.info('Provide details to the testimonial first!', 'Info');
            return;
         }
         this.form.section_testimonials.push({
            name: this.sectionTestimonialForm.name,
            position: this.sectionTestimonialForm.position,
            details: this.sectionTestimonialForm.details,
         });
         this.sectionTestimonialForm.reset();
         this.sectionTestimonialForm.clearErrors();
         this.$toast.success('Testimonial added to section!', 'Success');
      },
      removeSectionTestimonial(index) {
         this.form.section_testimonials.splice(index, 1);
      },
      handleSectionBgMedia(event) {
         const file = event.target.files[0];
         if (!file) {
            this.form.background_image = null;
            return;
         }
         const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         const maxSizeMB = 5;
         if (!allowedTypes.includes(file.type)) {
            this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
            event.target.value = null; // reset input
            return;
         }
         if (file.size > maxSizeMB * 1024 * 1024) {
            this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
            event.target.value = null;
            return;
         }
         this.form.background_image = file;
      },
      handleSlideMedia(fileObject) {
         // If single file
         if (Array.isArray(fileObject)) {
            this.heroSlideForm.slide_image = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.heroSlideForm.slide_image = fileObject.file ?? null
         }
         // const file = event.target.files[0];
         // if (!file) {
         //    this.heroSlideForm.slide_image = null;
         //    return;
         // }
         // const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         // const maxSizeMB = 5;
         // if (!allowedTypes.includes(file.type)) {
         //    this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
         //    event.target.value = null; // reset input
         //    return;
         // }
         // if (file.size > maxSizeMB * 1024 * 1024) {
         //    this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
         //    event.target.value = null;
         //    return;
         // }
         // this.heroSlideForm.slide_image = file;
      },
      handleNewSlideMedia(fileObject) {
         if (Array.isArray(fileObject)) {
            this.newHeroSlideForm.file = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.newHeroSlideForm.file = fileObject.file ?? null
         }
         // const file = event.target.files[0];
         // if (!file) {
         //    this.newHeroSlideForm.file = null;
         //    return;
         // }
         // const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         // const maxSizeMB = 5;
         // if (!allowedTypes.includes(file.type)) {
         //    this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
         //    event.target.value = null; // reset input
         //    return;
         // }
         // if (file.size > maxSizeMB * 1024 * 1024) {
         //    this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
         //    event.target.value = null;
         //    return;
         // }
         // this.newHeroSlideForm.file = file;
      },
      handleEditSectionMedia(event, section) {
         const file = event.target.files?.[0];
         const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         const maxSizeMB = 5;
         if (!allowedTypes.includes(file.type)) {
            this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
            event.target.value = '';
            return;
         }
         if (file.size > maxSizeMB * 1024 * 1024) {
            this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
            event.target.value = '';
            return;
         }
         if (file) {
            this.mediaForm.section_id = section.id;
            this.mediaForm.file = file;
         }
      },
      handleEditSectionBackgroundMedia(event, section) {
         const file = event.target.files?.[0];
         
         const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
         const maxSizeMB = 5;
         
         if (!allowedTypes.includes(file.type)) {
            this.$toast.error('Only JPEG, JPG, or PNG files are allowed.', 'Error');
            event.target.value = null;
            return;
         }
         
         if (file.size > maxSizeMB * 1024 * 1024) {
            this.$toast.error(`Image must be less than ${maxSizeMB}MB.`, 'Error');
            event.target.value = null;
            return;
         }
         
         if (file) {
            this.backgroundMediaForm.section_id = section.id;
            this.backgroundMediaForm.file = file;
         }
      },
      uploadMedia() {
         const sectionId = this.mediaForm.section_id;
         this.mediaForm.post(route('admin.pages.sections.media'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            forceFormData: true,
            onSuccess: () => {
               this.mediaForm.reset();
               this.mediaForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Media uploaded', 'Updated');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      uploadBackgroundMedia() {
         const sectionId = this.backgroundMediaForm.section_id;
         this.backgroundMediaForm.post(route('admin.pages.sections.background-media'), {
            headers: {
               "Content-Type": "multipart/form-data",
            },
            forceFormData: true,
            onSuccess: () => {
               this.backgroundMediaForm.reset();
               this.backgroundMediaForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Media uploaded', 'Updated');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      uploadNewSection() {
         this.form.post(route('admin.pages.sections.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               const modalElement = this.$refs.addSectionModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               setTimeout(() => {
                  this.$toast.success('Page section created successfully', 'Success');
                  this.fetchSections();
               }, 400);
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      updateSection() {
         this.editForm.patch(route('admin.pages.sections.update', this.editForm.section_id), {
            onSuccess: () => {
               this.editForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Page sections updated successfully', 'Success');
                  this.populateEditForm(this.editForm.section_id)
               }, 400)
               
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      deleteMedia(section) {
         this.$toast.question(`Delete media for ${section.title}?`, 'Deleting section media!').then(() => {
            this.$inertia.delete(route('admin.pages.sections.delete-media', section.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Media deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the media!', 'Error');
               }
            })
         })
      },
      deleteBackgroundMedia(section) {
         this.$toast.question(`Delete media for ${section.title}?`, 'Deleting section media!').then(() => {
            this.$inertia.delete(route('admin.pages.sections.delete-background', section.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Media deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the media!', 'Error');
               }
            })
         })
      },
      uploadCtaButton(section) {
         this.newCtaButtonForm.section_id = section.id;
         
         if (!this.newCtaButtonForm.cta_button_text) {
            this.$toast.info('Label the CTA button first', 'Info');
            return;
         }
         this.newCtaButtonForm.post(route('admin.cta-buttons.store'), {
            onSuccess: () => {
               this.newCtaButtonForm.reset();
               this.newCtaButtonForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('CTA button added!', 'Success');
                  this.populateEditForm(section.id)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save CTA button! Please try again', 'Error');
            },
         })
      },
      openCtaButtonEditModal(cta) {
         this.editCtaButtonForm.id = cta.id;
         this.editCtaButtonForm.section_id = cta.section_id;
         this.editCtaButtonForm.cta_link_type = cta.cta_link_type;
         this.editCtaButtonForm.custom_url = cta.custom_url;
         this.editCtaButtonForm.section_url = cta.section_url;
         this.editCtaButtonForm.page_id = cta.page_id;
         this.editCtaButtonForm.cta_button_text = cta.cta_button_text;
         this.editCtaButtonForm.cta_button_type = cta.cta_button_type;
         
         const modalElement = this.$refs.editSectionCtaButtonModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateCtaButton() {
         const sectionId = this.editCtaButtonForm.section_id
         
         if (!this.editCtaButtonForm.cta_button_text) {
            this.$toast.info('Label the CTA button first', 'Info');
            return;
         }
         this.editCtaButtonForm.patch(route('admin.cta-buttons.update', this.editCtaButtonForm.id), {
            onSuccess: () => {
               this.editCtaButtonForm.reset();
               this.editCtaButtonForm.clearErrors();
               const modalElement = this.$refs.editSectionCtaButtonModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('CTA button updated!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save CTA button! Please try again', 'Error');
            },
         })
      },
      deleteCtaButton(section, cta) {
         this.$toast.question(`Are you sure?`, 'Delete CTA Button!').then(() => {
            this.$inertia.delete(route('admin.cta-buttons.destroy', cta.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('CTA button deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the CTA button!', 'Error');
               }
            })
         });
      },
      openNewSectionCardModal(section) {
         this.newSectionCardForm.section_id = section.id;
         
         const modalElement = this.$refs.newSectionCardModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      handleCardMediaUpload(fileObject) {
         if (Array.isArray(fileObject)) {
            this.newSectionCardForm.media = fileObject.length > 0 ? fileObject[0].file : null
         } else {
            this.newSectionCardForm.media = fileObject.file ?? null
         }
      },
      uploadSectionCard() {
         const sectionId = this.newSectionCardForm.section_id;
         
         if (!this.newSectionCardForm.title) {
            this.$toast.info('Give the card a title first!', 'Info');
            return;
         }
         this.newSectionCardForm.post(route('admin.section-cards.store'), {
            forceFormData: true,
            onSuccess: () => {
               this.newSectionCardForm.reset();
               this.newSectionCardForm.clearErrors();
               const modalElement = this.$refs.newSectionCardModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Section card added!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the section card! Please try again!', 'Error');
            },
         })
      },
      openSectionCardEditModal(card) {
         this.editSectionCardForm.id = card.id;
         this.editSectionCardForm.card_id = card.hashid;
         this.editSectionCardForm.section_id = card.section_id;
         this.editSectionCardForm.title = card.title;
         this.editSectionCardForm.icon = card.icon;
         this.editSectionCardForm.details = card.details;
         this.editSectionCardForm.card_link_type = card.card_link_type;
         this.editSectionCardForm.custom_url = card.custom_url;
         this.editSectionCardForm.section_url = card.section_url;
         this.editSectionCardForm.page_id = card.page_id;
         const file = card.media?.find(md => md.collection_name === 'card-image') ?? null;
         if (file) {
            this.editSectionCardForm.media = [{
               id: file.id,
               model_id: card.id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true,
            }];
         } else {
            this.editForm.media = [{
               id: null,
               model_id: null,
               name: null,
               size: null,
               url: null,
               type: null,
               isExisting: false,
            }];
         }
         
         const modalElement = this.$refs.editSectionCardModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateSectionCard() {
         const sectionId = this.editSectionCardForm.section_id
         
         this.editSectionCardForm.patch(route('admin.section-cards.update', this.editSectionCardForm.id), {
            onSuccess: () => {
               this.editSectionCardForm.reset();
               this.editSectionCardForm.clearErrors();
               const modalElement = this.$refs.editSectionCardModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Section card updated!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the section card! Please try again!', 'Error');
            },
         })
      },
      deleteSectionCard(section, card) {
         this.$toast.question(`Are you sure?`, 'Delete Section Card!').then(() => {
            this.$inertia.delete(route('admin.section-cards.destroy', card.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Section Card deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the section card!', 'Error');
               }
            })
         });
      },
      uploadNewHeroSlide(section) {
         this.newHeroSlideForm.section_id = section.id;
         // if (!this.newHeroSlideForm.title) {
         //    this.$toast.info('Add a title to the slide first!', 'Info');
         //    return;
         // }
         // if (!this.newHeroSlideForm.details) {
         //    this.$toast.info('Add slide details first', 'Info');
         //    return;
         // }
         this.newHeroSlideForm.post(route('admin.hero-slides.store'), {
            onSuccess: () => {
               this.newHeroSlideForm.reset();
               this.newHeroSlideForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Hero slide added!', 'Success');
                  this.populateEditForm(section.id)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the hero slide! Please try again!', 'Error');
            },
         })
      },
      openHeroSlideEditModal(slide) {
         this.editHeroSlideForm.id = slide.hashid;
         this.editHeroSlideForm.slide_id = slide.id;
         this.editHeroSlideForm.section_id = slide.section_id;
         this.editHeroSlideForm.title = slide.title;
         this.editHeroSlideForm.sub_title = slide.sub_title;
         this.editHeroSlideForm.details = slide.details;
         this.editHeroSlideForm.page_id = slide.page_id;
         this.editHeroSlideForm.cta_button_text = slide.cta_button_text;
         this.editHeroSlideForm.cta_button_type = slide.cta_button_type;
         
         const file = slide.media?.find(md => md.collection_name === 'slide_image') ?? null;
         if (file) {
            this.editHeroSlideForm.media = [{
               id: file.model_id,
               name: file.file_name,
               size: file.size,
               url: file.original_url,
               type: file.mime_type,
               isExisting: true, // flag so you know it’s already uploaded
            }];
         } else {
            this.editHeroSlideForm.media = null;
         }
         
         const modalElement = this.$refs.editHeroSlideModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateHeroSlide() {
         // if (!this.editHeroSlideForm.title) {
         //    this.$toast.info('Add a title to the slide first!', 'Info');
         //    return;
         // }
         // if (!this.editHeroSlideForm.details) {
         //    this.$toast.info('Add slide details first', 'Info');
         //    return;
         // }
         const sectionId = this.editHeroSlideForm.section_id;
         this.editHeroSlideForm.patch(route('admin.hero-slides.update', this.editHeroSlideForm.id), {
            onSuccess: () => {
               this.editHeroSlideForm.reset();
               this.editHeroSlideForm.clearErrors();
               const modalElement = this.$refs.editHeroSlideModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Hero slide updated!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the hero slide! Please try again!', 'Error');
            },
         })
      },
      deleteSlide(section, slide) {
         this.$toast.question(`Are you sure?`, 'Delete Slide!').then(() => {
            this.$inertia.delete(route('admin.hero-slides.destroy', slide.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Hero slide deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the hero slide!', 'Error');
               }
            })
         });
      },
      openNewIconBoxModalModal(section) {
         this.newIconBoxForm.section_id = section.id;
         
         const modalElement = this.$refs.createIconBoxModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      uploadNewIconBox() {
         const sectionId = this.newIconBoxForm.section_id;
         
         if (!this.newIconBoxForm.title) {
            this.$toast.info('Give the card a title first!', 'Info');
            return;
         }
         
         this.newIconBoxForm.post(route('admin.icon-boxes.store'), {
            onSuccess: () => {
               this.newIconBoxForm.reset();
               this.newIconBoxForm.clearErrors();
               
               const modalElement = this.$refs.createIconBoxModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Section card added!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the section card! Please try again!', 'Error');
            },
         })
      },
      openIconBoxEditModal(iconBox) {
         this.editIconBoxForm.id = iconBox.hashid;
         this.editIconBoxForm.section_id = iconBox.section_id;
         this.editIconBoxForm.title = iconBox.title;
         this.editIconBoxForm.icon = iconBox.icon;
         this.editIconBoxForm.details = iconBox.details;
         this.editIconBoxForm.icon_link_type = iconBox.icon_link_type;
         this.editIconBoxForm.custom_url = iconBox.custom_url;
         this.editIconBoxForm.section_url = iconBox.section_url;
         this.editIconBoxForm.page_id = iconBox.page_id;
         
         const modalElement = this.$refs.editIconBoxModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateIconBox() {
         const sectionId = this.editIconBoxForm.section_id
         
         this.editIconBoxForm.patch(route('admin.icon-boxes.update', this.editIconBoxForm.id), {
            onSuccess: () => {
               this.editIconBoxForm.reset();
               this.editIconBoxForm.clearErrors();
               const modalElement = this.$refs.editIconBoxModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Icon Box added!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the icon box! Please try again!', 'Error');
            },
         })
      },
      deleteIconBox(section, iconBox) {
         this.$toast.question(`Are you sure?`, 'Delete Icon Box!').then(() => {
            this.$inertia.delete(route('admin.icon-boxes.destroy', iconBox.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Icon Box deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the icon box!', 'Error');
               }
            })
         });
      },
      uploadNewSectionFaq(section) {
         this.newSectionFaqForm.section_id = section.id;
         if (!this.newSectionFaqForm.question) {
            this.$toast.info('Give the faq a question first!', 'Info');
            return;
         }
         this.newSectionFaqForm.post(route('admin.section-faqs.store'), {
            onSuccess: () => {
               this.newSectionFaqForm.reset();
               this.newSectionFaqForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('FAQ added!', 'Success');
                  this.populateEditForm(section.id)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the faq! Please try again!', 'Error');
            },
         })
      },
      openSectionFaqEditModal(sectionFaq) {
         this.editSectionFaqForm.id = sectionFaq.hashid;
         this.editSectionFaqForm.section_id = sectionFaq.section_id;
         this.editSectionFaqForm.question = sectionFaq.question;
         this.editSectionFaqForm.answer = sectionFaq.answer;
         
         const modalElement = this.$refs.editSectionFaqModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateSectionFaq() {
         const sectionId = this.editSectionFaqForm.section_id
         
         this.editSectionFaqForm.patch(route('admin.section-faqs.update', this.editSectionFaqForm.id), {
            onSuccess: () => {
               this.editSectionFaqForm.reset();
               this.editSectionFaqForm.clearErrors();
               const modalElement = this.$refs.editSectionFaqModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('FAQ added!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the FAQ! Please try again!', 'Error');
            },
         })
      },
      deleteSectionFaq(section, faq) {
         this.$toast.question(`Are you sure?`, 'Delete FAQ!').then(() => {
            this.$inertia.delete(route('admin.section-faqs.destroy', faq.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('FAQ deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the faq!', 'Error');
               }
            })
         });
      },
      uploadNewSectionTestimonial(section) {
         this.newSectionTestimonialForm.section_id = section.id;
         if (!this.newSectionTestimonialForm.name) {
            this.$toast.info('Provide a name to the testimonial first!', 'Info');
            return;
         }
         if (!this.newSectionTestimonialForm.details) {
            this.$toast.info('Provide details to the testimonial first!', 'Info');
            return;
         }
         this.newSectionTestimonialForm.post(route('admin.section-testimonials.store'), {
            onSuccess: () => {
               this.newSectionTestimonialForm.reset();
               this.newSectionTestimonialForm.clearErrors();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Testimonial added!', 'Success');
                  this.populateEditForm(section.id)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the testimonial! Please try again!', 'Error');
            },
         })
      },
      openSectionTestimonialEditModal(sectionTestimonial) {
         this.editSectionTestimonialForm.id = sectionTestimonial.hashid;
         this.editSectionTestimonialForm.section_id = sectionTestimonial.section_id;
         this.editSectionTestimonialForm.name = sectionTestimonial.name;
         this.editSectionTestimonialForm.position = sectionTestimonial.position;
         this.editSectionTestimonialForm.details = sectionTestimonial.details;
         
         const modalElement = this.$refs.editSectionTestimonialModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateSectionTestimonial() {
         const sectionId = this.editSectionTestimonialForm.section_id
         
         this.editSectionTestimonialForm.patch(route('admin.section-testimonials.update', this.editSectionTestimonialForm.id), {
            onSuccess: () => {
               this.editSectionTestimonialForm.reset();
               this.editSectionTestimonialForm.clearErrors();
               const modalElement = this.$refs.editSectionTestimonialModal;
               const modalInstance = Modal.getOrCreateInstance(modalElement);
               modalInstance.hide();
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Testimonial added!', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Failed to save the testimonial! Please try again!', 'Error');
            },
         })
      },
      deleteSectionTestimonial(section, faq) {
         this.$toast.question(`Are you sure?`, 'Delete Testimonial!').then(() => {
            this.$inertia.delete(route('admin.section-testimonials.destroy', faq.id), {
               onSuccess: () => {
                  this.editForm.clearErrors();
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Testimonial deleted successfully!', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the faq!', 'Error');
               }
            })
         });
      },
      deleteSection(section) {
         this.$toast.question(`Are you sure?`, 'Delete Section!').then(() => {
            this.$inertia.delete(route('admin.pages.sections.delete', section.id), {
               onSuccess: () => {
                  this.$toast.success('Page section deleted successfully!', 'Success');
                  this.openAccordion = '';
                  setTimeout(() => {
                     this.fetchSections();
                  }, 300)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the page section!', 'Error');
               }
            })
         });
      },
      handleGalleryPreviewRemoved(previewObject) {
         // Extract URL from previewObject
         let url = previewObject;
         if (typeof previewObject === 'object' && previewObject.src) {
            url = previewObject.src;
         }
         
         // Find matching image in existingGalleryImages
         const matchingImage = this.existingGalleryImages.find(
            (img) => img.url === url
         );
         
         if (matchingImage && matchingImage.id) {
            this.$inertia.delete(
               this.route("admin.medias.delete.section-gallery", {
                  section: this.sectionGalleryForm.section_id,
                  media: matchingImage.id,
               }),
               {
                  preserveScroll: true,
                  onSuccess: () => {
                     const index = this.existingGalleryImages.findIndex(
                        (img) => img.id === matchingImage.id
                     );
                     if (index > -1) {
                        this.existingGalleryImages.splice(index, 1);
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
      showCreatePricingPlanModal(section) {
         this.pricingPlanForm.section_id = section.id;
         
         const modalElement = this.$refs.createPricingPlanModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      createPricingPlan() {
         const sectionId = this.pricingPlanForm.section_id;
         this.pricingPlanForm.post(route('admin.pricing-plans.store'), {
            onSuccess: () => {
               this.pricingPlanForm.reset();
               this.pricingPlanForm.clearErrors();
               
               const modalElement = this.$refs.createPricingPlanModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Pricing Plan Updated Successfully', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
               
               this.$toast.success('Pricing Plan Created Successfully', 'Success')
            },
            onError: (errors) => {
               console.log(errors)
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         });
      },
      editPricingPlan(section, rowData) {
         this.editPricingPlanForm.id = rowData.hashid;
         this.editPricingPlanForm.section_id = section.id;
         this.editPricingPlanForm.name = rowData.name;
         this.editPricingPlanForm.slug = rowData.slug;
         this.editPricingPlanForm.tagline = rowData.tagline;
         this.editPricingPlanForm.price = rowData.price * 0.01;
         this.editPricingPlanForm.billing_period = rowData.billing_period;
         this.editPricingPlanForm.billing_period_label = rowData.billing_period_label;
         this.editPricingPlanForm.description = rowData.description;
         this.editPricingPlanForm.button_text = rowData.button_text;
         this.editPricingPlanForm.button_type = rowData.button_type;
         this.editPricingPlanForm.button_url = rowData.button_url;
         this.editPricingPlanForm.section_url = rowData.section_url;
         this.editPricingPlanForm.page_id = rowData.page_id;
         this.editPricingPlanForm.featured = rowData.featured;
         this.editPricingPlanForm.active = rowData.active;
         this.editPricingPlanForm.bedroom_label = rowData.bedroom_label;
         this.editPricingPlanForm.size_sqft = rowData.size_sqft;
         this.editPricingPlanForm.deposit_percentage = rowData.deposit_percentage;
         this.editPricingPlanForm.deposit_amount = rowData.deposit_amount;
         this.editPricingPlanForm.payment_duration_months = rowData.payment_duration_months;
         this.editPricingPlanForm.monthly_payment = rowData.monthly_payment;
         this.editPricingPlanForm.features = rowData.features?.map(feature => ({
            id: feature.id,
            name: feature.name,
            order: feature.order,
            is_included: feature.is_included,
         }));
         
         const modalElement = this.$refs.editPricingPlanModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updatePricingPlan() {
         const sectionId = this.editPricingPlanForm.section_id;
         this.editPricingPlanForm.patch(route('admin.pricing-plans.update', this.editPricingPlanForm.id), {
            onSuccess: () => {
               this.editPricingPlanForm.reset();
               this.editPricingPlanForm.clearErrors();
               this.editPricingPlanForm.media = null;
               
               const modalElement = this.$refs.editPricingPlanModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               
               this.fetchSections();
               setTimeout(() => {
                  this.$toast.success('Pricing Plan Updated Successfully', 'Success');
                  this.populateEditForm(sectionId)
               }, 400)
            },
            onError: (errors) => {
               console.log(errors);
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      deletePricingPlan(section, plan) {
         this.$toast.question('Are you sure?', `Deleting ${plan.name}`).then(() => {
            this.$inertia.delete(route('admin.pricing-plans.destroy', plan.hashid), {
               onSuccess: () => {
                  this.fetchSections();
                  setTimeout(() => {
                     this.$toast.success('Pricing Plan deleted', 'Success');
                     this.populateEditForm(section.id)
                  }, 400)
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the plan!', 'Error');
               }
            })
         })
      },
      addPricingFeature() {
         this.pricingPlanForm.features.push({
            name: '',
            order: '',
            is_included: false,
         });
      },
      removePricingFeature(index) {
         this.pricingPlanForm.features.splice(index, 1);
      },
      addPricingFeatureOnEditForm() {
         this.editPricingPlanForm.features.push({
            name: '',
            order: '',
            is_included: false,
         });
      },
      removePricingFeatureOnEditForm(index) {
         this.editPricingPlanForm.features.splice(index, 1);
      },
      formCleanUp() {
         this.form.reset();
         this.form.clearErrors();
         this.form.media = null;
      },
      editCtaButtonFormCleanUp() {
         this.editCtaButtonForm.reset();
         this.editCtaButtonForm.clearErrors();
      },
      newSectionCardFormCleanUp() {
         this.newSectionCardForm.reset();
         this.newSectionCardForm.clearErrors();
      },
      moveSectionFormCleanUp() {
         this.moveSectionForm.reset();
         this.moveSectionForm.clearErrors();
      },
      sectionCardEditFormCleanUp() {
         this.editSectionCardForm.reset();
         this.editSectionCardForm.clearErrors();
      },
      heroEditFormCleanUp() {
         this.editHeroSlideForm.reset();
         this.editHeroSlideForm.clearErrors();
      },
      iconBoxEditFormCleanUp() {
         this.editIconBoxForm.reset();
         this.editIconBoxForm.clearErrors();
      },
      sectionFaqEditFormCleanUp() {
         this.editSectionFaqForm.reset();
         this.editSectionFaqForm.clearErrors();
      },
      sectionTestimonialEditFormCleanUp() {
         this.editSectionTestimonialForm.reset();
         this.editSectionTestimonialForm.clearErrors();
      },
      pricingPlanFormCleanUp() {
         this.pricingPlanForm.reset();
         this.pricingPlanForm.clearErrors();
      },
      editPricingPlanFormCleanUp() {
         this.editPricingPlanForm.reset();
         this.editPricingPlanForm.clearErrors();
      },
   }
}
</script>

<style scoped>
.accordion-item {
   border-radius: 0.375rem;
   border-bottom: none;
}
.crd-custom {
   border: 1px solid rgb(228.48, 230.16, 231.84);
   box-shadow:none;
}
.table thead {
   background-color: rgba(228.48, 230.16, 231.84, 0.3);
}
.table thead tr th {
   padding: 0.65rem;
}
.table tbody tr td {
   padding: 0.55rem;
}
</style>

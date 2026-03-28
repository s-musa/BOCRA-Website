<template>
   <Head title="Website Menus"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">Website Menus</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Menu Items
               </li>
            </ol>
         </nav>
         
         <div class="col-xxl-12">
            <div class="row">
               <div class="col-xl-3 col-12 order-lg-first order-last">
                  <div class="card mb-3">
                     <div class="card-body">
                        <div class="card-title mb-5">Menu Sorting</div>
                        <draggable
                           :list="menuItems"
                           class="drag-area"
                           @change="handleDraggableChange"
                        >
                           <div v-for="(item, index) in menuItems" class="card bg-muted border-0 mb-3">
                              <div class="card-body p-3">
                                 <div class="d-flex">
                                    <span class="me-5 drag-button">
                                       <i class="bx bx-move"></i>
                                    </span>
                                    <span class="fw-normal"><span>{{ index + 1 }}.</span> {{ item.title }}</span>
                                 </div>
                              </div>
                           </div>
                        </draggable>
                     </div>
                  </div>
               </div>
               <div class="col-xl-9 col-12 order-lg-last order-first">
                  <div class="card">
                     <div class="card-header flex-column flex-md-row">
                        <div class="row row-gap-1">
                           <div class="col-md-3 col-9">
                              <input type="search" id="search" class="form-control bg-muted-lt rounded-2"
                                     placeholder="Search Menus"
                                     @input="applyFilter" v-model="appendParams.filter.title">
                           </div>
                           <div class="col-md-6 col-3 ms-auto">
                              <div class="flex-wrap text-end">
                                 <div class="card-action">
                                    <button type="button" class="btn btn-primary d-none d-sm-inline-block" @click="createMenu">
                                       <i class="bx bx-plus-circle me-2"></i>
                                       Add Menu
                                    </button>
                                    
                                    <button type="button" class="btn btn-primary btn-icon d-sm-none" @click="createMenu">
                                       <i class="bx bx-plus"></i>
                                    </button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <VueTable
                        :fields="fields"
                        api-url="datatable/menus"
                        :append-params="appendParams"
                        ref="menusTable"
                     >
                        <template #actions="props">
                           <div class="dropdown">
                              <button class="btn align-text-top py-1" data-bs-toggle="dropdown">
                                 <i class="bx bx-dots-vertical"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end">
                                 <a class="dropdown-item" href="#" @click.prevent="editMenu(props.rowData)">
                                    <i class="bx bx-edit-alt me-2"></i>Edit
                                 </a>
                                 <a class="dropdown-item text-danger" href="#" @click.prevent="deleteMenu(props.rowData)">
                                    <i class="bx bx-trash me-2"></i>Delete
                                 </a>
                              </div>
                           </div>
                        </template>
                     </VueTable>
                  </div>
               </div>
            </div>
         </div>
         
         <!-- Create Modal -->
         <div
            class="modal fade"
            id="create-menu-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="create-menu-modal-label"
            aria-hidden="true"
            ref="createMenuModal"
         >
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="create-menu-modal-label">Add Menu</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="formCleanUp"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="storeMenu">
                        <div class="mb-3">
                           <label for="menuTitle" class="form-label-md mb-1">Title</label>
                           <input id="menuTitle" type="text" v-model="form.title" class="form-control">
                           <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                        </div>
                        <div class="mb-3">
                           <label for="menuType" class="form-label-md mb-1">Type</label>
                           <v-select
                              id="menuType"
                              v-model="form.type"
                              :options="menuTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.type" class="text-danger">{{ form.errors.type }}</div>
                        </div>
                        <div v-if="typeIsPage" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Page</label>
                           <v-select
                              id="pageId"
                              v-model="form.page_id"
                              :options="pages"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="form.errors.page_id" class="text-danger">{{ form.errors.page_id }}</div>
                        </div>
                        <div v-if="typeIsCustom" class="mb-3">
                           <label for="menuUrl" class="form-label-md mb-1">Custom Url</label>
                           <input id="menuUrl" type="text" v-model="form.url" class="form-control">
                           <div v-if="form.errors.url" class="text-danger">{{ form.errors.url }}</div>
                        </div>
                        <div class="form-check form-switch mb-5">
                           <input v-model="form.has_children" class="form-check-input" type="checkbox" id="hasCtaButtons">
                           <label class="form-check-label" for="hasCtaButtons">
                              Is A Dropdown
                              <br>
                              <span class="text-muted">check this if you want to make the menu a dropdown menu</span>
                           </label>
                        </div>
                        <div v-if="form.has_children" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Items Type</label>
                           <v-select
                              id="pageId"
                              v-model="form.child_type"
                              :options="dropdownTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.child_type" class="text-danger">{{ form.errors.child_type }}</div>
                        </div>
                        <div v-if="form.child_type && form.child_type === 'pages'" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Pages</label>
                           <v-select
                              multiple
                              id="pageId"
                              v-model="form.children"
                              :options="pages"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="form.errors.child_type" class="text-danger">{{ form.errors.child_type }}</div>
                        </div>
                        <div v-if="form.child_type && form.child_type === 'component'" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Component Type</label>
                           <v-select
                              id="pageId"
                              v-model="form.component"
                              :options="componentTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.component" class="text-danger">{{ form.errors.component }}</div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button
                        type="button"
                        class="btn btn-secondary me-2"
                        data-bs-dismiss="modal"
                        @click="formCleanUp"
                        :disabled="form.processing"
                     >
                        Close
                     </button>
                     <button
                        type="button"
                        class="btn btn-primary"
                        @click.prevent="storeMenu"
                        :disabled="form.processing"
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
            id="edit-menu-modal"
            data-bs-backdrop="static"
            tabindex="-1"
            aria-labelledby="edit-menu-modal-label"
            aria-hidden="true"
            ref="editMenuModal"
         >
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="edit-menu-modal-label">Edit Menu</h5>
                     <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="formCleanUp"
                     ></button>
                  </div>
                  <div class="modal-body">
                     <form id="createForm" @submit.prevent="updateMenu">
                        <div class="mb-3">
                           <label for="menuTitle" class="form-label-md mb-1">Title</label>
                           <input id="menuTitle" type="text" v-model="form.title" class="form-control">
                           <div v-if="form.errors.title" class="text-danger">{{ form.errors.title }}</div>
                        </div>
                        
                        <div class="mb-3">
                           <label for="menuType" class="form-label-md mb-1">Type</label>
                           <v-select
                              id="menuType"
                              v-model="form.type"
                              :options="menuTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.type" class="text-danger">{{ form.errors.type }}</div>
                        </div>
                        
                        <div v-if="typeIsPage" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Page</label>
                           <v-select
                              id="pageId"
                              v-model="form.page_id"
                              :options="pages"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="form.errors.page_id" class="text-danger">{{ form.errors.page_id }}</div>
                        </div>
                        
                        <div v-if="typeIsCustom" class="mb-3">
                           <label for="menuUrl" class="form-label-md mb-1">Custom Url</label>
                           <input id="menuUrl" type="text" v-model="form.url" class="form-control">
                           <div v-if="form.errors.url" class="text-danger">{{ form.errors.url }}</div>
                        </div>
                        
<!--                        <div class="mb-3">-->
<!--                           <label for="menuOrder" class="form-label-md mb-1">Menu EcmOrder</label>-->
<!--                           <input id="menuOrder" type="number" v-model="form.order" class="form-control">-->
<!--                           <div v-if="form.errors.order" class="text-danger">{{ form.errors.order }}</div>-->
<!--                        </div>-->
                        
                        <div class="form-check form-switch mb-5">
                           <input v-model="form.has_children" class="form-check-input" type="checkbox" id="hasCtaButtons">
                           <label class="form-check-label" for="hasCtaButtons">
                              Is A Dropdown
                              <br>
                              <span class="text-muted">check this if you want to make the menu a dropdown menu</span>
                           </label>
                        </div>
                        
                        <div v-if="form.has_children" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Items Type</label>
                           <v-select
                              id="pageId"
                              v-model="form.child_type"
                              :options="dropdownTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.child_type" class="text-danger">{{ form.errors.child_type }}</div>
                        </div>
                        <div v-if="form.child_type && form.child_type === 'pages'" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Pages</label>
                           <v-select
                              multiple
                              id="pageId"
                              v-model="form.children"
                              :options="pages"
                              label="title"
                              :reduce="option => option.id"
                           />
                           <div v-if="form.errors.child_type" class="text-danger">{{ form.errors.child_type }}</div>
                        </div>
                        <div v-if="form.child_type && form.child_type === 'component'" class="mb-3">
                           <label for="pageId" class="form-label-md mb-1">Dropdown Component Type</label>
                           <v-select
                              id="pageId"
                              v-model="form.component"
                              :options="componentTypes"
                              label="name"
                              :reduce="option => option.value"
                           />
                           <div v-if="form.errors.component" class="text-danger">{{ form.errors.component }}</div>
                        </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button
                        type="button"
                        class="btn btn-secondary me-2"
                        data-bs-dismiss="modal"
                        @click="formCleanUp"
                        :disabled="form.processing"
                     >
                        Close
                     </button>
                     <button
                        type="button"
                        class="btn btn-primary"
                        @click.prevent="updateMenu"
                        :disabled="form.processing"
                     >
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
import DefaultLayout from "@layouts/DefaultLayout.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {Modal} from "bootstrap";
import axios from "axios";
import _debounce from "lodash/debounce.js";

export default {
   components: {DefaultLayout, Head, Link},
   data() {
      return {
         fields: [
            {
               name: 'title',
               title: 'TITLE',
               titleClass: 'w-auto',
               dataClass: 'w-auto',
            },
            {
               name: 'type',
               title: 'TYPE',
               titleClass: 'w__15',
               dataClass: 'w__15',
            },
            {
               name: 'page.title',
               title: 'PAGE',
               titleClass: 'w-auto',
               dataClass: 'w-auto',
            },
            // {
            //    name: 'order',
            //    title: 'MENU ORDER',
            //    titleClass: 'w__15',
            //    dataClass: 'w__15',
            // },
            {
               name: '__slot:actions',
               title: 'ACTIONS',
               titleClass: 'text-end w__5',
               dataClass: 'text-end w__5',
            },
         ],
         appendParams: {
            filter: {
               title: '',
            }
         },
         form: useForm({
            id: null,
            page_id: null,
            title: null,
            type: null,
            url: null,
            // order: '',
            has_children: false,
            child_type: null,
            children: [],
            component: null,
         }),
         menuTypes: [
            { name: "Page", value: "page" },
            { name: "Custom", value: "custom" },
         ],
         dropdownTypes: [
            { name: "Pages", value: "pages" },
            { name: "Component", value: "component" },
         ],
         componentTypes: [
            { name: "Services", value: "App\\Models\\Service" },
            { name: "Projects", value: "App\\Models\\Project" },
         ],
         menuItems: [],
         pages: [],
         typeIsPage: false,
         typeIsCustom: false,
      }
   },
   watch: {
      'form.type': function(val) {
         if(val === 'page') {
            this.typeIsPage = true;
            this.typeIsCustom = false;
            this.form.url = '';
         } else if (val === 'custom') {
            this.typeIsPage = false;
            this.typeIsCustom = true;
            this.form.page_id = '';
         } else {
            this.typeIsPage = false;
            this.typeIsCustom = false;
            this.form.page_id = '';
         }
      },
      // 'form.child_type': function(val) {
      //    if (val === 'pages') {
      //       this.form.component = null;
      //    }
      //    if (val === 'component') {
      //       this.form.children = [];
      //    }
      // },
   },
   created() {
      this.fetchMenuItems();
      this.fetchPages();
   },
   methods: {
      fetchMenuItems() {
         axios.get(route('datatable.menus'))
            .then(({data}) => {
               this.menuItems = data.data;
            }).catch((error) => {
            console.log(error);
            this.$toast.error("An error occurred while fetching the menu items!", "Error");
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
      handleDraggableChange: _debounce(function(event) {
         if (event.moved) {
            console.log('Moved item:', event.moved.element);
            const orderedIds = this.menuItems.map((menu, index) => ({
               id: menu.id,
               order: index + 1
            }));
            
            this.$inertia.post('/wp-admin/menus/update-menu-order', { menus: orderedIds }, {
               onSuccess: () => {
                  this.fetchMenuItems();
                  setTimeout(() => {
                     // this.$toast.success('Menu order updated successfully', 'Success');
                     this.$refs.menusTable.reloadTable();
                  }, 300)
               },
               onError: (error) => {
                  console.log(error);
                  this.$toast.error('Something went wrong', 'Error');
               }
            })
         }
      }, 500),
      createMenu() {
         const modalElement = this.$refs.createMenuModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      storeMenu() {
         this.form.post(route('admin.menus.store'), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.$refs.menusTable.reloadTable();
               const modalElement = this.$refs.createMenuModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.fetchMenuItems();
               this.$toast.success('Menu Created Successfully', 'Success')
            },
            onError: (errors) => {
               this.$toast.error('An error occurred. Please try again', 'Error')
            },
         })
      },
      editMenu(rowData) {
         this.form.id = rowData.hashid;
         this.form.page_id = rowData.page_id;
         this.form.title = rowData.title;
         this.form.type = rowData.type;
         this.form.url = rowData.url;
         // this.form.order = rowData.order;
         this.form.has_children = rowData.has_children;
         this.form.child_type = rowData.child_type;
         this.form.component = rowData.component;
         this.form.children = rowData.children?.map(child => child.page_id) ?? [];
         
         const modalElement = this.$refs.editMenuModal;
         const modalInstance = Modal.getOrCreateInstance(modalElement);
         modalInstance.show();
      },
      updateMenu() {
         this.form.patch(route('admin.menus.update', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.$refs.menusTable.reloadTable();
               const modalElement = this.$refs.editMenuModal;
               const modalInstance = Modal.getInstance(modalElement);
               modalInstance.hide();
               this.fetchMenuItems();
               this.$toast.success('Menu Updated Successfully', 'Success')
            },
            onError: (errors) => {
               console.error('Error updating menu: ', errors);
               this.$toast.error('An error occurred. Please try again', 'Error');
            },
         })
      },
      deleteMenu(menu) {
         this.$toast.question('Are you sure?', `Deleting ${menu.title}`).then(() => {
            this.$inertia.delete(route('admin.menus.destroy', menu.hashid), {
               onSuccess: () => {
                  this.$toast.success('Menu item deleted', 'Success');
                  this.$refs.menusTable.reloadTable();
               },
               onError: (error) => {
                  console.log(error)
                  this.$toast.error('An error occurred while deleting the menu item!', 'Error');
               }
            })
         })
      },
      applyFilter: _debounce(function () {
         this.$refs.menusTable.reloadTable();
      }, 800),
      formCleanUp() {
         this.form.reset()
      },
   },
}
</script>

<style scoped>
.drag-button, .drag-button:hover {
   cursor: grab !important;
}
</style>

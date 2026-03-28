<template>
   <Head title="Components"/>
   
   <DefaultLayout>
      <div class="row">
         <h4 class="mb-0">Website Components</h4>
         <nav class="mb-3">
            <ol class="breadcrumb">
               <li class="breadcrumb-item">
                  <Link :href="route('admin.dashboard')">Home</Link>
               </li>
               <li class="breadcrumb-item text-primary">
                  Components
               </li>
            </ol>
         </nav>
         
         <div class="col-12">
            <div class="nav-align-top mb-6">
               <v-select class="d-sm-none w-100 mb-7 bg-white" id="componentTabs" v-model="activeTab" :clearable="false" :options="tabs"
                         label="name" :reduce="option => option.value"
               />
               <ul class="nav nav-pills mb-5 d-none d-sm-flex" role="tablist">
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'services' }"
                        @click="activeTab = 'services'">
                        Services
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'projects' }"
                             @click="activeTab = 'projects'">
                        Projects
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'blogs' }"
                             @click="activeTab = 'blogs'">
                        Blogs
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'events' }"
                             @click="activeTab = 'events'">
                        Events
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'board' }"
                             @click="activeTab = 'board'">
                        Board Members
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'partners' }"
                             @click="activeTab = 'partners'">
                        Partners
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'clients' }"
                             @click="activeTab = 'clients'">
                        Clients
                     </button>
                  </li>
                  <li class="nav-item me-2" role="presentation">
                     <button type="button" class="nav-link" :class="{ active: activeTab === 'faqs' }"
                             @click="activeTab = 'faqs'">
                        FAQs
                     </button>
                  </li>
               </ul>
               
               <div class="tab-content">
                  <div v-show="activeTab === 'services'">
                     <h5 class="mb-0">Services</h5>
                     <h6 class="text-muted">Your company services that will be displayed on the website.</h6>
                     <Services />
                  </div>
                  <div v-show="activeTab === 'projects'">
                     <h5 class="mb-0">Projects</h5>
                     <h6 class="text-muted">Your company projects/programmes that will be displayed on the website.</h6>
                     <Projects />
                  </div>
                  <div v-show="activeTab === 'blogs'">
                     <h5 class="mb-0">Blogs</h5>
                     <h6 class="text-muted">You blogs that you need to add to your website.</h6>
                     <Blogs />
                  </div>
                  <div v-show="activeTab === 'events'">
                     <h5 class="mb-0">Events</h5>
                     <h6 class="text-muted">You events that you need to add to your website.</h6>
                     <Events />
                  </div>
                  <div v-show="activeTab === 'board'">
                     <h5 class="mb-0">Board Members</h5>
                     <h6 class="text-muted">Board Member details that are to be displayed in the website.</h6>
                     <board-members />
                  </div>
                  <div v-show="activeTab === 'partners'">
                     <h5 class="mb-0">Partners</h5>
                     <h6 class="text-muted">Partner details that are to be displayed in the website.</h6>
                     <partners />
                  </div>
                  <div v-show="activeTab === 'clients'">
                     <h5 class="mb-0">Partners</h5>
                     <h6 class="text-muted">Clients details that are to be displayed in the website.</h6>
                     <clients />
                  </div>
                  <div v-show="activeTab === 'products'">
                     <h5 class="mb-0">Products</h5>
                     <h6 class="text-muted">Your company products that will be displayed on the website.</h6>
                     <Products />
                  </div>
                  <div v-show="activeTab === 'faqs'">
                     <h5 class="mb-0">Frequently Asked Questions</h5>
                     <h6 class="text-muted">FAQs to be displayed on the website</h6>
                     <Faqs />
                  </div>
               </div>
            </div>
         </div>
      </div>
   </DefaultLayout>

</template>

<script>
import DefaultLayout from "@layouts/DefaultLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Services from "@pages/Admin/Components/Services.vue"
import Projects from "@pages/Admin/Components/Projects.vue"
import Products from "@pages/Admin/Components/Products.vue"
import BoardMembers from "@pages/Admin/Components/BoardMembers.vue"
import Partners from "@pages/Admin/Components/Partners.vue";
import Faqs from "@pages/Admin/Components/Faqs.vue"
import Skills from "@pages/Admin/Components/Skills.vue"
import Blogs from "@pages/Admin/Components/Blogs.vue"
import Events from "@pages/Admin/Components/Events.vue"
import Clients from "@pages/Admin/Components/Clients.vue"

export default {
   components: {DefaultLayout, Head, Link, Faqs, BoardMembers, Projects, Products, Services, Skills, Blogs, Events, Partners, Clients},
   data() {
      return {
         activeTab: 'services',
         tabs: [
            {value: 'services', name: 'Services'},
            {value: 'projects', name: 'Projects'},
            {value: 'blogs', name: 'Blogs'},
            {value: 'events', name: 'Events'},
            {value: 'board', name: 'Board Members'},
            {value: 'partners', name: 'Partners'},
            {value: 'clients', name: 'Clients'},
            {value: 'products', name: 'Products'},
            {value: 'faqs', name: 'FAQs'},
         ],
      }
   },
}
</script>

<style scoped>
.tab-content {
   padding: 0 !important;
   background-color: transparent;
   border: none;
   box-shadow: none;
}
.nav-pills .nav-item .nav-link:not(.active) {
   border: 1px solid #e9ecef;
}
</style>

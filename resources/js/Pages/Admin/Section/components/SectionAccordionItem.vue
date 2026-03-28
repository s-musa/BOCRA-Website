<template>
   <div class="accordion-item mb-5">
      <h1 class="accordion-header border-bottom">
         <button type="button" class="accordion-button"
                 :class="{ collapsed: !isOpen }"
                 @click="$emit('toggle')">
            <i class="bx bx-move me-3 drag-button"></i>
            <span class="me-3">Section {{ index + 1 }}</span>-
            <span class="ms-3 text-muted">{{ section.section_type ? section.section_type.name : section.type }}</span>
         </button>
      </h1>
      <div class="accordion-collapse collapse" :class="{ show: isOpen }">
         <div class="accordion-body py-4">
            <SectionEditForm
               v-if="isOpen"
               :section="section"
               :page-id="section.page_id"
               @updated="$emit('updated')"
               @delete="$emit('delete', section)"
            />
         </div>
      </div>
   </div>
</template>

<script>
import SectionEditForm from "./SectionEditForm.vue";

export default {
   components: {SectionEditForm},
   props: {
      section: {type: Object, required: true},
      index: {type: Number, required: true},
      isOpen: {type: Boolean, default: false},
   },
   emits: ['toggle', 'delete', 'updated']
}
</script>

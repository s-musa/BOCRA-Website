<template>
   <div class="row">
      <!-- Basic Fields -->
      <div class="col-md-4 col-12">
         <div class="mb-3">
            <label for="sectionTitle" class="form-label-md mb-2">Section Title</label>
            <input v-model="localValue.title" type="text" class="form-control" id="sectionTitle" />
         </div>
      </div>
      
      <div class="col-md-4 col-12">
         <div class="mb-3">
            <label for="sectionSubTitle" class="form-label-md mb-2">Section Sub-Title</label>
            <input v-model="localValue.sub_title" type="text" class="form-control" id="sectionSubTitle" />
         </div>
      </div>
      
      <!-- Section Image -->
      <SectionImageUpload v-model="localValue.media" />
      
      <!-- Section Details -->
      <div class="col-12">
         <div class="mb-5">
            <label for="sectionDetails" class="form-label-md mb-2">Section Details</label>
            <editor v-model="localValue.details" id="editor" class="editor-control mx-0 w-100" />
         </div>
      </div>
      
      <!-- CTA Buttons -->
      <CtaButtonManager
         v-model="localValue.cta_buttons"
         :pages="pages"
      />
      
      <!-- Section Styling -->
      <SectionStyling v-model="localValue" section-type="hero" />
      
      <!-- Hero Slides (if carousel style selected) -->
      <HeroSlideManager
         v-if="isCarouselStyle"
         v-model="localValue.hero_slides"
         :pages="pages"
      />
   </div>
</template>

<script>
import Editor from "@components/global/Editor.vue";
import SectionImageUpload from "../shared/SectionImageUpload.vue";
import CtaButtonManager from "../shared/CtaButtonManager.vue";

export default {
   components: {
      Editor,
      SectionImageUpload,
      CtaButtonManager,
   },
   props: {
      modelValue: {type: Object, required: true},
      pages: {type: Array, default: () => []}
   },
   computed: {
      localValue: {
         get() {return this.modelValue},
         set(val) {this.$emit('update:modelValue', val)}
      },
      isCarouselStyle() {
         return ['hero-with-carousel', 'hero-with-carousel-aligned-left'].includes(this.localValue.section_style);
      }
   }
}
</script>

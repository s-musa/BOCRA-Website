<template>
   <div class="col-md-4 col-12">
      <div class="mb-3">
         <label for="sectionImage" class="form-label-md mb-2">Section Image</label>
         <input
            id="sectionImage"
            @change="handleFileChange"
            type="file"
            accept="image/*"
            class="form-control"
         />
         <div v-if="previewUrl" class="mt-2">
            <img :src="previewUrl" alt="Preview" style="width:120px; height:auto;">
         </div>
      </div>
   </div>
</template>

<script>
export default {
   props: {
      modelValue: {type: [File, Object], default: null}
   },
   data() {
      return {
         previewUrl: null
      }
   },
   methods: {
      handleFileChange(event) {
         const file = event.target.files[0];
         if (!file) {
            this.$emit('update:modelValue', null);
            this.previewUrl = null;
            return;
         }
         
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
         
         this.previewUrl = URL.createObjectURL(file);
         this.$emit('update:modelValue', file);
      }
   }
}
</script>

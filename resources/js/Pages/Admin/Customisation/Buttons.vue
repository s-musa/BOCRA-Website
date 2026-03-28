<template>
   <div class="card mb-3">
      <div class="card-header py-3">
         <h5 class="card-title mb-0">
<!--            <i class="bx bx-play-circle bx-md"></i>-->
            Buttons
         </h5>
      </div>
   </div>
   
   <div class="card">
      <div class="card-body">
         <p class="fs-6">Choose a button style</p>
         <div class="row">
            <div class="col-12">
               <div class="row mb-4">
                  <div class="col-2  align-content-center justify-content-center">
                     <div class="form-check">
                        <input v-model="form.button_style" name="default-radio-1" class="form-check-input" type="radio" value="btn-outline" id="buttonStyle1">
                     </div>
                  </div>
                  <label class="form-check-label col-10" for="buttonStyle1">
                     <button type="button" class="primary-btn btn-outline w-100">Outlined</button>
                  </label>
               </div>
               <div class="row mb-4">
                  <div class="col-2 align-content-center justify-content-center">
                     <div class="form-check">
                        <input v-model="form.button_style" name="default-radio-1" class="form-check-input" type="radio" value="default"  id="buttonStyle2">
                     </div>
                  </div>
                  <label
                     class="form-check-label col-10" for="buttonStyle2">
                     <button type="button" class="primary-btn w-100">Contained</button>
                  </label>
               </div>
            </div>
            <div class="col-12 mt-8">
               <button type="button" class="btn btn-outline-secondary w-100" @click.prevent="updateButtonStyles">
                  Save
               </button>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3"
export default {
   name: "ButtonStyle",
   props: {
      customisation: {
         type: Object,
         required: true,
      },
   },
   emits: ['ButtonStyleUpdated'],
   data() {
      return {
         form: useForm({
            id: '',
            button_style: '',
         }),
         formData: [],
      }
   },
   watch: {
      customisation: {
         handler(newVal) {
            if (newVal) {
               this.populateForm()
            }
         },
         immediate: true, // run on mount as well
         deep: true,
      },
   },
   methods: {
      populateForm() {
         this.form.id = this.customisation.hashid;
         this.form.button_style = this.customisation.button_style;
      },
      populateFormWithSavedData(data) {
         this.form.id = data.hashid;
         this.form.button_style = data.button_style;
         
         this.formData = [];
      },
      updateButtonStyles() {
         this.formData = this.form.data;
         this.form.patch(route('admin.customisations.button-styles', this.form.id), {
            onSuccess: () => {
               this.form.reset();
               this.form.clearErrors();
               this.populateFormWithSavedData(this.formData)
               this.$emit('ButtonStyleUpdated', this.formData);
               this.$toast.success('Button style updated!', 'Success');
            },
            onError: (error) => {
               console.log(error);
               this.$toast.error('Something went wrong! Please try again', 'Error')
            }
         })
      },
   }
}
</script>

<style scoped>
.primary-btn {
   background-color: var(--ecbz-primary);
   color: #ffffff;
   font-weight: 700;
   padding: 12px 25px;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   font-size: 1rem;
   text-align: center;
   transition: all 0.8s ease;
   position: relative;
}
.primary-btn.btn-outline {
   border: 2px solid var(--ecbz-primary);
   border-radius: 30px;
   background-color: transparent;
   color: var(--ecbz-primary);
   transition: all 0.6s ease;
}
.primary-btn.btn-outline:hover {
   background-color: var(--ecbz-primary);
   color: #ffffff;
}
.form-check {
   margin-bottom: 0;
}
</style>

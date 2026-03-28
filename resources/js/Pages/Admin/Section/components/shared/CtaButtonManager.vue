<template>
   <div class="col-12">
      <div class="form-check form-switch mb-3">
         <input v-model="hasButtons" class="form-check-input" type="checkbox" id="hasCtaButtons">
         <label class="form-check-label" for="hasCtaButtons">Has CTA Buttons</label>
      </div>
      
      <div v-if="hasButtons" class="row">
         <div class="col-md-6 col-12">
            <div class="mb-3">
               <div class="table-responsive text-nowrap">
                  <table class="table table-sm table-bordered">
                     <thead>
                     <tr>
                        <th style="width: 40%;">Page</th>
                        <th style="width: 30%;">Button Text</th>
                        <th style="width: 25%;">Button Style</th>
                        <th style="width: 5%;"></th>
                     </tr>
                     </thead>
                     <tbody>
                     <tr v-for="(cta, index) in localValue" :key="index">
                        <td>{{ cta.page?.title || cta.page }}</td>
                        <td>{{ cta.cta_button_text }}</td>
                        <td>{{ getButtonTypeName(cta.cta_button_type) }}</td>
                        <td>
                           <button type="button" class="btn btn-sm btn-icon btn-danger" @click="removeButton(index)">
                              <i class="bx bx-trash"></i>
                           </button>
                        </td>
                     </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
         <div class="col-md-6 col-12">
            <div class="card crd-custom">
               <div class="card-body">
                  <label class="form-label-md mb-3">Add CTA Button</label>
                  <div v-if="localValue.length >= 1">
                     <p class="text-muted">You can only add 1 CTA button per section.</p>
                  </div>
                  <div v-else class="row">
                     <div class="col-12 mb-3">
                        <label class="form-label">Page To Redirect To:</label>
                        <v-select v-model="buttonForm.page" :options="pages" label="title"/>
                     </div>
                     <div class="col-12 mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" v-model="buttonForm.cta_button_text" class="form-control"/>
                     </div>
                     <div class="col-12 mb-3">
                        <label class="form-label">Button Type</label>
                        <v-select v-model="buttonForm.cta_button_type" :options="buttonTypes" label="name"/>
                     </div>
                     <div class="col-12">
                        <button type="button" class="btn btn-sm btn-light" @click="addButton">
                           Add Button
                        </button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
   props: {
      modelValue: {type: Array, default: () => []},
      pages: {type: Array, default: () => []}
   },
   data() {
      return {
         hasButtons: this.modelValue.length > 0,
         buttonForm: {
            page: null,
            cta_button_text: '',
            cta_button_type: null
         },
         buttonTypes: [
            {value: 'btn-primary', name: 'Primary Button'},
            {value: 'btn-secondary', name: 'Secondary Button'},
            {value: 'btn-default', name: 'Default Button'},
         ]
      }
   },
   computed: {
      localValue: {
         get() {return this.modelValue},
         set(val) {this.$emit('update:modelValue', val)}
      }
   },
   watch: {
      hasButtons(val) {
         if (!val) this.$emit('update:modelValue', []);
      }
   },
   methods: {
      addButton() {
         if (!this.buttonForm.page || !this.buttonForm.cta_button_text) {
            this.$toast.info('Please fill all fields', 'Info');
            return;
         }
         
         const newButtons = [...this.localValue, {...this.buttonForm}];
         this.$emit('update:modelValue', newButtons);
         
         this.buttonForm = {page: null, cta_button_text: '', cta_button_type: null};
         this.$toast.success('CTA button added!', 'Success');
      },
      removeButton(index) {
         const newButtons = this.localValue.filter((_, i) => i !== index);
         this.$emit('update:modelValue', newButtons);
      },
      getButtonTypeName(type) {
         if (typeof type === 'object') return type.name;
         const found = this.buttonTypes.find(t => t.value === type);
         return found ? found.name : type;
      }
   }
}
</script>

<style scoped>
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

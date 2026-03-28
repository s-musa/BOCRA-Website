<template>
   <Head title="Login"></Head>
   <GuestLayout>
      <div class="w-px-400 mx-auto mt-sm-12 mt-8">
         <h4 class="mb-1">Welcome to <span class="text-primary">{{ company.name }}</span></h4>
         <p class="mb-6">Please sign-in to your account and start the adventure</p>
         
         <form @submit.prevent="handleRegister" class="mb-6">
            <div class="mb-4 form-control-validation">
               <label for="name" class="form-label">Full Name</label>
               <input
                  type="text"
                  class="form-control"
                  :class="{'is-invalid': form.errors.name}"
                  id="name"
                  v-model="form.name"
                  placeholder="Enter your full name"
                  autofocus
                  required
               />
               <div v-if="form.errors.name" class="invalid-feedback">
                  {{ form.errors.name }}
               </div>
            </div>
            
            <!-- Username -->
<!--            <div class="mb-4 form-control-validation">-->
<!--               <label for="username" class="form-label">Username</label>-->
<!--               <input-->
<!--                  type="text"-->
<!--                  class="form-control"-->
<!--                  :class="{'is-invalid': form.errors.username}"-->
<!--                  id="username"-->
<!--                  v-model="form.username"-->
<!--                  @input="validateUsername"-->
<!--                  placeholder="Choose a unique username"-->
<!--                  required-->
<!--               />-->
<!--               <div v-if="form.errors.username" class="invalid-feedback">-->
<!--                  {{ form.errors.username }}-->
<!--               </div>-->
<!--               <small class="text-muted">Only letters, numbers, dashes and underscores allowed</small>-->
<!--            </div>-->
            
            <div class="mb-4 form-control-validation">
               <label for="email" class="form-label">Email Address</label>
               <input
                  type="email"
                  class="form-control"
                  :class="{'is-invalid': form.errors.email}"
                  id="email"
                  v-model="form.email"
                  placeholder="Enter your email address"
                  required
               />
               <div v-if="form.errors.email" class="invalid-feedback">
                  {{ form.errors.email }}
               </div>
            </div>
            
            <!-- Phone Number -->
<!--            <div class="mb-4 form-control-validation">-->
<!--               <label for="phone" class="form-label">Phone Number</label>-->
<!--               <input-->
<!--                  type="tel"-->
<!--                  class="form-control"-->
<!--                  :class="{'is-invalid': form.errors.phone}"-->
<!--                  id="phone"-->
<!--                  v-model="form.phone"-->
<!--                  @input="formatPhone"-->
<!--                  placeholder="2547xxxxxxx"-->
<!--               />-->
<!--               <div v-if="form.errors.phone" class="invalid-feedback">-->
<!--                  {{ form.errors.phone }}-->
<!--               </div>-->
<!--            </div>-->
            
            <div class="mb-4 form-password-toggle form-control-validation">
               <label class="form-label" for="password">Password</label>
               <div class="input-group input-group-merge">
                  <input
                     :type="showPassword ? 'text' : 'password'"
                     id="password"
                     class="form-control"
                     :class="{'is-invalid': form.errors.password}"
                     v-model="form.password"
                     placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                     required
                  />
                  <span class="input-group-text cursor-pointer" @click="togglePassword">
                     <i class="icon-base bx" :class="showPassword ? 'bx-show' : 'bx-hide'"></i>
                  </span>
               </div>
               <div v-if="form.errors.password" class="invalid-feedback d-block">
                  {{ form.errors.password }}
               </div>
               
               <small class="text-muted d-block mt-1">Minimum 8 characters</small>
            </div>
            
            <div class="mb-4 form-password-toggle form-control-validation">
               <label class="form-label" for="password_confirmation">Confirm Password</label>
               <div class="input-group input-group-merge">
                  <input
                     :type="showPasswordConfirm ? 'text' : 'password'"
                     id="password_confirmation"
                     class="form-control"
                     :class="{'is-invalid': form.errors.password_confirmation || (form.password_confirmation && form.password !== form.password_confirmation)}"
                     v-model="form.password_confirmation"
                     placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                     required
                  />
                  <span class="input-group-text cursor-pointer" @click="togglePasswordConfirm">
                     <i class="icon-base bx" :class="showPasswordConfirm ? 'bx-show' : 'bx-hide'"></i>
                  </span>
               </div>
               <div v-if="form.errors.password_confirmation" class="invalid-feedback d-block">
                  {{ form.errors.password_confirmation }}
               </div>
               <div v-else-if="form.password_confirmation && form.password !== form.password_confirmation" class="invalid-feedback d-block">
                  Passwords do not match
               </div>
            </div>
            
            <!-- Terms & Conditions -->
<!--            <div class="mb-4">-->
<!--               <div class="form-check">-->
<!--                  <input-->
<!--                     class="form-check-input"-->
<!--                     :class="{'is-invalid': form.errors.terms}"-->
<!--                     type="checkbox"-->
<!--                     id="terms"-->
<!--                     v-model="form.terms"-->
<!--                     required-->
<!--                  />-->
<!--                  <label class="form-check-label" for="terms">-->
<!--                     I agree to the-->
<!--                     <Link :href="route('terms')" class="text-primary" target="_blank">Terms & Conditions</Link>-->
<!--                  </label>-->
<!--               </div>-->
<!--               <div v-if="form.errors.terms" class="invalid-feedback d-block">-->
<!--                  {{ form.errors.terms }}-->
<!--               </div>-->
<!--            </div>-->
            
            <!-- Submit Button -->
            <button
               class="btn btn-primary d-grid w-100"
               type="submit"
               :disabled="form.processing || !isFormValid"
            >
<!--               <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>-->
               {{ form.processing ? 'Creating Account...' : 'Create Account' }}
            </button>
         </form>
         
         <p class="text-center">
            <span>Already have an account?</span>
            <Link :href="route('customer.login')">
               <span class="fw-bold text-primary ms-1">Sign in instead</span>
            </Link>
         </p>
         
         <div class="text-center mt-4">
            <a href="/" class="text-muted small">
               <i class="bx bx-arrow-back me-1"></i>Back to Shop
            </a>
         </div>
      </div>
   </GuestLayout>
</template>

<script setup>
import {computed, ref} from 'vue';
import GuestLayout from "@layouts/Customer/GuestLayout.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";

const company = usePage().props.company;

const form = useForm({
   name: '',
   email: '',
   // phone: '',
   password: '',
   password_confirmation: '',
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const togglePassword = () => {
   showPassword.value = !showPassword.value;
};

const togglePasswordConfirm = () => {
   showPasswordConfirm.value = !showPasswordConfirm.value;
};


const formatPhone = (e) => {
   // Remove any non-digit characters
   let phone = e.target.value.replace(/\D/g, '');
   
   // Limit to 12 digits (254XXXXXXXXX)
   if (phone.length > 12) {
      phone = phone.substring(0, 12);
   }
   
   form.phone = phone;
};

const isFormValid = computed(() => {
   return form.name &&
      form.email &&
      // form.phone &&
      form.password &&
      form.password === form.password_confirmation;
});

const handleRegister = () => {
   form.post(route('customer.register.post'), {
      preserveScroll: true,
      onSuccess: () => {
         form.reset();
      },
      onError: (errors) => {
         console.error('Registration errors:', errors);
      }
   });
};
</script>

<style scoped>
.cursor-pointer {
   cursor: pointer;
}

.form-control.is-invalid,
.form-check-input.is-invalid {
   border-color: #dc3545;
}

.spinner-border-sm {
   width: 1rem;
   height: 1rem;
   border-width: 0.2em;
}

.alert {
   position: relative;
}

.btn:disabled {
   opacity: 0.65;
   cursor: not-allowed;
}

.w-px-500 {
   max-width: 500px;
}

.progress {
   background-color: #e9ecef;
   border-radius: 0.25rem;
   overflow: hidden;
}

.progress-bar {
   transition: width 0.3s ease;
}
</style>

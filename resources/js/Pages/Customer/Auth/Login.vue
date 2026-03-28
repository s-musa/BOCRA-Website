<template>
   <Head title="Login"></Head>
   <GuestLayout>
      <div class="w-px-400 mx-auto mt-sm-12 mt-8">
         <h4 class="mb-1">Welcome to <span class="text-primary">{{ company.name }}</span></h4>
         <p class="mb-6">Please sign-in to your account and start the adventure</p>
         
         <form @submit.prevent="handleLogin" class="mb-6">
            <div class="mb-6 form-control-validation">
               <label for="login" class="form-label">Email, Username or Phone</label>
               <input
                  type="text"
                  class="form-control"
                  :class="{'is-invalid': form.errors.login}"
                  id="login"
                  v-model="form.login"
                  placeholder="Enter your email, username or phone"
                  autofocus
                  required
               />
               <div v-if="form.errors.login" class="invalid-feedback">
                  {{ form.errors.login }}
               </div>
            </div>
            
            <div class="mb-6 form-password-toggle form-control-validation">
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
            </div>
            
            <div class="my-7">
               <div class="d-flex justify-content-between">
                  <div class="form-check mb-0">
                     <input
                        class="form-check-input"
                        type="checkbox"
                        id="remember-me"
                        v-model="form.remember"
                     />
                     <label class="form-check-label" for="remember-me">Remember Me</label>
                  </div>
                  <a href="#">
                     <p class="mb-0">Forgot Password?</p>
                  </a>
               </div>
            </div>
            
            <button
               class="btn btn-primary d-grid w-100"
               type="submit"
               :disabled="form.processing"
            >
               {{ form.processing ? 'Processing...' : 'Sign in' }}
            </button>
         </form>
         
         <p class="text-center">
            <span>New on our platform?</span>
            <Link :href="route('customer.register')">
               <span class="fw-bold text-primary ms-1">Create an account</span>
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
import { ref } from 'vue';
import GuestLayout from "@layouts/Customer/GuestLayout.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";

const company = usePage().props.company;

const form = useForm({
   login: '',
   password: '',
   remember: false
});

const showPassword = ref(false);

const togglePassword = () => {
   showPassword.value = !showPassword.value;
};

const handleLogin = () => {
   form.post(route('customer.login.post'), {
      preserveScroll: true,
      onSuccess: () => {
         form.reset('password');
      },
      onError: (errors) => {
         console.error('Login errors:', errors);
      }
   });
};
</script>

<style scoped>
.cursor-pointer {
   cursor: pointer;
}
.form-control.is-invalid {
   border-color: #dc3545;
}
.alert {
   position: relative;
}
.btn:disabled {
   opacity: 0.65;
   cursor: not-allowed;
}
</style>

<template>
   <GuestLayout>
      <Head title="Register"/>
      
      <div class="card px-sm-6 px-0">
         <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
               <div class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                     <img src="@/ecobiz-logo.png" alt="logo" style="width:200px;">
                  </span>
               </div>
            </div>
            <!-- /Logo -->
            <form @submit.prevent="submit" class="mb-6">
               <div class="mb-6">
                  <label for="name" class="form-label">Name</label>
                  <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Enter your full name"/>
                  <InputError class="mt-2" :message="form.errors.name"/>
               </div>
               <div class="mb-6">
                  <label for="email" class="form-label">Email</label>
                  <input v-model="form.email" type="text" class="form-control" id="email" placeholder="Enter your email" />
                  <InputError class="mt-2" :message="form.errors.email"/>
               </div>
               <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                     <input v-model="form.password" :type="showPassword ? 'text' : 'password'"  id="password" class="form-control" placeholder="Enter password"/>
                     <span class="input-group-text cursor-pointer" @click.prevent="toggleShowPassword">
                        <i :class="showPassword ? 'icon bx bx-hide' : 'icon bx bx-show'"></i>
                     </span>
                  </div>
                  <InputError class="mt-2" :message="form.errors.password"/>
               </div>
               <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password">Confirm Password</label>
                  <div class="input-group input-group-merge">
                     <input v-model="form.password_confirmation" :type="showConfirmedPassword ? 'text' : 'password'"  id="password" class="form-control" placeholder="Confirm password"/>
                     <span class="input-group-text cursor-pointer" @click.prevent="toggleShowConfirmedPassword">
                        <i :class="showConfirmedPassword ? 'icon bx bx-hide' : 'icon bx bx-show'"></i>
                     </span>
                  </div>
                  <InputError class="mt-2" :message="form.errors.password_confirmation"/>
               </div>
               <button class="btn btn-primary d-grid w-100">Sign up</button>
            </form>
            
            <p class="text-center">
               <span class="me-1">Already have an account?</span>
               <Link :href="route('login')">
                  <span>Sign in instead</span>
               </Link>
            </p>
         </div>
      </div>
   </GuestLayout>
</template>

<script>
import GuestLayout from '@layouts/GuestLayout.vue';
import InputError from '@components/InputError.vue';
import InputLabel from '@components/InputLabel.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import TextInput from '@components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

export default {
   components: {
      GuestLayout,
      InputError,
      InputLabel,
      PrimaryButton,
      TextInput,
      Head,
      Link,
   },
   data() {
      return {
         form: useForm({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
         }),
         showPassword: false,
         showConfirmedPassword: false,
      }
   },
   methods: {
      submit() {
         this.form.post(route('register'), {
            onFinish: () => this.form.reset('password', 'password_confirmation'),
         })
      },
      toggleShowPassword() {
         this.showPassword = !this.showPassword;
      },
      toggleShowConfirmedPassword() {
         this.showConfirmedPassword = !this.showConfirmedPassword;
      },
   },
   setup() {
      const form = useForm({
         name: '',
         email: '',
         password: '',
         password_confirmation: '',
      });
      
      const submit = () => {
         form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
         });
      };
   }
}
</script>

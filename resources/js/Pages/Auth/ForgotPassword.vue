<template>
   <GuestLayout>
      <Head title="Forgot Password"/>
      
<!--      <div v-if="status"-->
<!--           class="mb-4 text-sm fw-medium text-green"-->
<!--      >-->
<!--         {{ status }}-->
<!--      </div>-->
      
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
            <h4 class="mb-1">Forgot Password? 🔒</h4>
            <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
            <form class="mb-6" @submit.prevent="submit">
               <div class="mb-6">
                  <label for="email" class="form-label-md mb-1">Email</label>
                  <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Enter your email"/>
                  <InputError class="mt-2" :message="form.errors.email"/>
               </div>
               <button class="btn btn-primary d-grid w-100" :disabled="form.processing">Send Reset Link</button>
            </form>
            <div class="text-center">
               <Link :href="route('login')" class="d-flex justify-content-center">
                  <i class="bx bx-chevron-left me-1"></i>
                  Back to login
               </Link>
            </div>
         </div>
      </div>
   </GuestLayout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
   props: {
      status: {
         type: String,
      }
   },
   data() {
      return {
         form: useForm({
            email: '',
         }),
      }
   },
   methods: {
      submit() {
         this.form.post(route('password.email'), {
            onSuccess: () => {
               this.$toast.success(this.status, 'Success');
            },
            onError: () => {
               this.$toast.error('Something went wrong! Please try again later!');
            }
         });
      },
   },
}
</script>

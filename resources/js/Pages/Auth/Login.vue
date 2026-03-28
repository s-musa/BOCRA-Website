<template>
   <GuestLayout>
      <Head title="Login"/>
      
<!--      <div v-if="status" class="mb-4 text-sm font-medium text-green-600">-->
<!--         {{ status }}-->
<!--      </div>-->
      
      <div class="card px-sm-6 px-0">
         <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
               <a href="#" class="app-brand-link gap-2">
<!--                  <span v-if="logo" class="app-brand-logo demo">-->
<!--                     <img :src="logo" alt="logo" style="width:200px;">-->
<!--                  </span>-->
                  <span class="app-brand-logo demo">
                     <img src="@/ecobiz-logo.png" alt="logo" style="width:200px;">
                  </span>
               </a>
            </div>
            <!-- /Logo -->
<!--            <h4 class="mb-1 text-center">Welcome</h4>-->
            <p class="mb-6 text-center">Please sign-in to your account</p>
            <form id="formAuthentication" class="mb-6">
               <div class="mb-6">
                  <label for="email" class="form-label">Email</label>
                  <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Enter your email"/>
                  <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
               </div>
               <div class="mb-6 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                     <input
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        v-model="form.password"
                        class="form-control"
                        name="password"
                        placeholder="Enter your password"
                        aria-describedby="password"
                     />
                     <span class="input-group-text cursor-pointer" @click="toggleShow">
                     <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                  </span>
                  </div>
                  <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>
               </div>
               <div class="mb-8">
                  <div class="d-flex justify-content-between mt-8">
                     <div class="form-check mb-0 ms-2">
                        <Checkbox v-model:checked="form.remember" class="form-check-input" type="checkbox" id="remember" name="remember"/>
                        <label class="form-check-label" for="remember"> Remember Me </label>
                     </div>
                     <Link v-if="canResetPassword" :href="route('password.request')">
                        <span>Forgot Password?</span>
                     </Link>
                  </div>
               </div>
               <div class="mb-6">
                  <button type="submit" class="btn btn-primary d-grid w-100"
                          @click.prevent="submit" :disabled="form.processing">
                     {{ form.processing ? 'Processing...' : 'Sign in' }}
                  </button>
               </div>
            </form>
         </div>
      </div>
   </GuestLayout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

export default {
   components: {
      GuestLayout,
      Checkbox,
      InputError,
      InputLabel,
      PrimaryButton,
      TextInput,
      Head,
      Link
   },
   props: {
      canResetPassword: {
         type: Boolean,
      },
      status: {
         type: String,
      }
   },
   data() {
      return {
         form: useForm({
            email: '',
            password: '',
            remember: false,
         }),
         showPassword: false,
      }
   },
   computed: {
      logo() {
         return this.$page.props.logoUrl;
      },
   },
   mounted() {
      this.showStatus();
   },
   methods: {
      submit() {
         this.form.post(route('login'), {
            onFinish: () => this.form.reset('password'),
         });
      },
      toggleShow() {
         this.showPassword = !this.showPassword;
      },
      showStatus() {
         if(this.status) {
            this.$toast.info(this.status, 'Info');
         }
      }
   }
}
</script>

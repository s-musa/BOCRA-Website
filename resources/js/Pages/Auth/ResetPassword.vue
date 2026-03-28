<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref} from "vue";

const props = defineProps({
   email: {
      type: String,
      required: true,
   },
   token: {
      type: String,
      required: true,
   },
});

const form = useForm({
   token: props.token,
   email: props.email,
   password: '',
   password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmedPassword = ref(false);

const toggleShowPassword = () => {
   showPassword.value = !showPassword.value;
};
const toggleShowConfirmedPassword = () => {
   showConfirmedPassword.value = !showConfirmedPassword.value;
};

const submit = () => {
   form.post(route('password.store'), {
      onFinish: () => form.reset('password', 'password_confirmation'),
   });
};
</script>

<template>
   <GuestLayout>
      <Head title="Reset Password"/>
      
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
            <form @submit.prevent="submit">
               <div>
                  <InputLabel for="email" value="Email"/>
                  
                  <TextInput
                     id="email"
                     type="email"
                     class="mt-1 block w-full"
                     v-model="form.email"
                     required
                     autofocus
                     autocomplete="username"
                  />
                  
                  <InputError class="mt-2" :message="form.errors.email"/>
               </div>
               
               <div class="mt-4">
                  <InputLabel for="password" value="Password"/>
                  
<!--                  <TextInput-->
<!--                     id="password"-->
<!--                     type="password"-->
<!--                     class="mt-1 block w-full"-->
<!--                     v-model="form.password"-->
<!--                     required-->
<!--                     autocomplete="new-password"-->
<!--                  />-->
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
                     <span class="input-group-text cursor-pointer" @click="toggleShowPassword">
                        <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                     </span>
                  </div>
                  
                  <InputError class="mt-2" :message="form.errors.password"/>
               </div>
               
               <div class="mt-4">
                  <InputLabel
                     for="password_confirmation"
                     value="Confirm Password"
                  />
                  
<!--                  <TextInput-->
<!--                     id="password_confirmation"-->
<!--                     type="password"-->
<!--                     class="mt-1 block w-full"-->
<!--                     v-model="form.password_confirmation"-->
<!--                     required-->
<!--                     autocomplete="new-password"-->
<!--                  />-->
                  <div class="input-group input-group-merge">
                     <input
                        :type="showConfirmedPassword ? 'text' : 'password'"
                        id="password"
                        v-model="form.password_confirmation"
                        class="form-control"
                        name="password"
                        placeholder="Enter your password"
                        aria-describedby="password"
                     />
                     <span class="input-group-text cursor-pointer" @click="toggleShowConfirmedPassword">
                        <i :class="showConfirmedPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                     </span>
                  </div>
                  
                  <InputError
                     class="mt-2"
                     :message="form.errors.password_confirmation"
                  />
               </div>
               
               <div class="mt-5 flex items-center justify-end">
                  <PrimaryButton
                     class="w-100"
                     :class="{ 'opacity-25': form.processing }"
                     :disabled="form.processing"
                  >
                     Reset Password
                  </PrimaryButton>
               </div>
            </form>
         </div>
      </div>
   </GuestLayout>
</template>

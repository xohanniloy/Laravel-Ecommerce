<script setup>
import ImageUpload from './ImageUpload.vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({
    position: "top-right",
    duration: 3000,
});
const page = usePage();
const form = useForm({
    name: '',
    image: null
});

const submitBrand = () => {
    form.post('/brands', {
        onSuccess: () => {
            if (page.props.flash?.status == true) {
                toaster.success(page.props.flash?.message || 'Brand added successfully');
                router.get("/brands");
            } else {
                toaster.error(page.props.flash?.message || "Brand add failed. Please check your credentials.");
            }
        }
    });
}
</script>

<template>
       <h2 class="text-2xl font-bold mb-6">Add Brand</h2>
    <form @submit.prevent="submitBrand" class="bg-white shadow-md rounded-lg p-6">
      <div class="mb-4">
        <label for="brand-name" class="block text-gray-700 font-bold mb-2">Name</label>
        <input v-model="form.name" type="text" id="brand-name" class="w-full p-2 border rounded-md" placeholder="Enter brand name" required>
      </div>
      <div class="mb-4">
        <label for="brand-image" class="block text-gray-700 font-bold mb-2">Image</label>
        <!-- <input type="file" id="brand-image" class="w-full p-2 border rounded-md" required> -->
        <ImageUpload :productImg="form.image" @image="(e) => form.image = e" />
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Brand</button>
    </form>
</template>
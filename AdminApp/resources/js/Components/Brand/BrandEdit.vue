<script setup>
import ImageUpload from './ImageUpload.vue';
import { Link, usePage, useForm, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import { ref } from 'vue';

const toaster = createToaster({
    position: "top-right",
    duration: 3000,
});
const page = usePage();
const brand = page.props.brand;
const form = useForm({
    name: brand.name,
    image: brand.image,
    '_method': 'PUT',
});

const submitEdit = () => {
    form.post(`/brands/${brand.id}`, {
        onSuccess: () => {
            if (page.props.flash?.status == true) {
                toaster.success(page.props.flash?.message || 'Brand updated successfully');
                router.get("/brands");
            } else {
                toaster.error(page.props.flash?.message || "Brand update failedx. Please check your credentials.");
            }
        },
    });
};

</script>

<template>
    <h2 class="text-2xl font-bold mb-6">Edit Brand</h2>
  
 <form @submit.prevent="submitEdit" class="bg-white shadow-md rounded-lg p-6">
    <div class="mb-4 flex justify-end"> <Link :href="'/brands'" class="bg-blue-500 text-white px-4 py-2 rounded-md">Back</Link></div>
   <div class="mb-4">
     <label for="brand-name" class="block text-gray-700 font-bold mb-2">Name</label>
     <input v-model="form.name" type="text" id="brand-name" class="w-full p-2 border rounded-md" placeholder="Enter brand name" required>
   </div>
   <div class="mb-4">
     <label for="brand-image" class="block text-gray-700 font-bold mb-2">Image</label>
     <!-- <input type="file" id="brand-image" class="w-full p-2 border rounded-md" required> -->
     <ImageUpload :productImg="form.image" @image="(e) => form.image = e" />
   </div>
   <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Brand</button>
 </form>
</template>
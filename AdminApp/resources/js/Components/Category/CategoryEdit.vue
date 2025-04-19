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
const category = page.props.category;
const form = useForm({
    name: category.name,
    image: category.image,
    '_method': 'PUT',
});

const submitEdit = () => {
    form.post(`/categories/${category.id}`, {
        onSuccess: () => {
            if (page.props.flash?.status == true) {
                toaster.success(page.props.flash?.message || 'Category updated successfully');
                router.get("/categories");
            } else {
                toaster.error(page.props.flash?.message || "Category update failedx. Please check your credentials.");
            }
        },
    });
};

</script>

<template>
    <h2 class="text-2xl font-bold mb-6">Edit Category</h2>
  
 <form @submit.prevent="submitEdit" class="bg-white shadow-md rounded-lg p-6">
    <div class="mb-4 flex justify-end"> <Link :href="'/categories'" class="bg-blue-500 text-white px-4 py-2 rounded-md">Back</Link></div>
   <div class="mb-4">
     <label for="category-name" class="block text-gray-700 font-bold mb-2">Name</label>
     <input v-model="form.name" type="text" id="category-name" class="w-full p-2 border rounded-md" placeholder="Enter category name" required>
   </div>
   <div class="mb-4">
     <label for="category-image" class="block text-gray-700 font-bold mb-2">Image</label>
     <!-- <input type="file" id="category-image" class="w-full p-2 border rounded-md" required> -->
     <ImageUpload :productImg="form.image" @image="(e) => form.image = e" />
   </div>
   <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Category</button>
 </form>
</template>
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
    active: '',
    image: null
});

const submitSlider = () => {
    form.post('/sliders', {
        onSuccess: () => {
            if (page.props.flash?.status == true) {
                toaster.success(page.props.flash?.message || 'Slider added successfully');
                router.get("/sliders");
            } else {
                toaster.error(page.props.flash?.message || "Slider add failed. Please check your credentials.");
            }
        }
    });
}
</script>

<template>
    <h2 class="text-2xl font-bold mb-6">Add Slider</h2>

    <form @submit.prevent="submitSlider" class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4 flex justify-end">
            <Link :href="'/sliders'" class="bg-blue-500 text-white px-4 py-2 rounded-md">Back</Link>
        </div>
        <div class="mb-4">
            <label for="slide-active" class="block text-gray-700 font-bold mb-2">Is Active</label>
            <select id="slide-active" class="w-full p-2 border rounded-md" v-model="form.active" required>
                <option value="">Select</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="slider-image" class="block text-gray-700 font-bold mb-2">Image</label>
            <!-- <input type="file" id="slider-image" class="w-full p-2 border rounded-md" required> -->
            <ImageUpload :productImg="form.image" @image="(e) => form.image = e" />
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save Slider</button>
    </form>
</template>
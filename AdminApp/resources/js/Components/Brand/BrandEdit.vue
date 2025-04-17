<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import { ref } from 'vue';

const toaster = createToaster({
    position: "top-right",
    duration: 3000,
});
const page = usePage();
const brands = page.props.brands;
const Header = [
    { text: "Image", value: "image" },
    { text: "Name", value: "name" },
    { text: "Action", value: "action" },
];
const Item = ref(page.props.brands);
const searchValue = ref();
const searchField = ref();


const deleteBrand = (id) => {
    let text = "Do you went to delete"
    if (confirm(text) === true) {
        router.get(`/brands/${id}/delete`);
        toaster.success(page.props.flash?.message || "Customer Deleted successfully");
    } else {
        text = "you canceled!";
    }
}
</script>

<template>
    <!-- Page Header with Search and Add Button -->
    <!-- Page Title with Bottom Border -->
    <div class="border-b border-gray-300 pb-2 mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Brands</h2>
    </div>

    <!-- Search + Add Brand Button -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <!-- Search Input -->
        <input placeholder="Search..."
            class="w-full md:w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            type="text" v-model="searchValue" />

        <!-- Add Brand Button -->
        <Link href="/brands/create"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow transition duration-300">
        Add Brand
        </Link>
    </div>


    <!-- Table Card -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <EasyDataTable show-index buttons-pagination alternating :headers="Header" :items="Item" :rows-per-page="10"
            :search-field="searchField" :search-value="searchValue">
            <template #item-image="{ image }">
                <div class="flex items-center py-2">
                    <img :src="image" :alt="image" class="w-10 h-10 object-cover rounded-md border border-gray-300" />
                </div>
            </template>

            <template #item-action="{ id }">
                <div class="flex items-center gap-2">
                    <Link class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow"
                        :href="`/brands/${id}/edit`">
                    Edit
                    </Link>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow"
                        @click="DeleteClick(id)">
                        Delete
                    </button>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>
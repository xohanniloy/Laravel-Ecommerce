<script setup>
import ImageUpload from './ImageUpload.vue';
import { Link, useForm, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({
    position: "top-right",
    duration: 3000,
});
const page = usePage();
const product = page.props.product;

const categories = page.props.categories;
const brands = page.props.brands;

const form = useForm({
    title: product.title,
    short_des: product.short_des,
    price: product.price,
    discount_price: product.discount_price,
    is_discount: product.is_discount,
    image: product.image,
    in_stock: product.in_stock,
    stock: product.stock,
    remark: product.remark,
    category: product.category_id,
    brand: product.brand_id,
    img1: product.details.img1,
    img2: product.details.img2,
    img3: product.details.img3,
    img4: product.details.img4,
    description: product.details.description,
    color: product.details.color,
    size: product.details.size,
    '_method': 'PUT',
});

const submitProduct = () => {
    form.post(`/products/${product.id}`, {
        onSuccess: () => {
            if (page.props.flash?.status == true) {
                toaster.success(page.props.flash?.message || 'Product updated successfully');
                router.get("/products");
            } else {
                toaster.error(page.props.flash?.message || "Product update failed. Please check your credentials.");
            }
        }
    });
}
</script>

<template>
    <h2 class="text-2xl font-bold mb-6">Edit Product</h2>
    <form class="bg-white shadow-md rounded-lg p-6" @submit.prevent="submitProduct">
        <!-- Product Details -->
        <div class="mb-4">
            <label for="product-title" class="block text-gray-700 font-bold mb-2">Product Title</label>
            <input type="text" id="product-title" class="w-full p-2 border rounded-md" placeholder="Enter product title"
                v-model="form.title" required>
        </div>
        <div class="mb-4">
            <label for="short-description" class="block text-gray-700 font-bold mb-2">Short
                Description</label>
            <textarea id="short-description" class="w-full p-2 border rounded-md" rows="2"
                placeholder="Enter short description (max 500 characters)" maxlength="500" v-model="form.short_des"
                required></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-gray-700 font-bold mb-2">Price</label>
                <input type="number" step="0.01" id="price" class="w-full p-2 border rounded-md"
                    placeholder="Enter price" v-model="form.price" required>
            </div>
            <div>
                <label for="discount-price" class="block text-gray-700 font-bold mb-2">Discount
                    Price</label>
                <input type="number" step="0.01" id="discount-price" class="w-full p-2 border rounded-md"
                    placeholder="Enter discount price (optional)" v-model="form.discount_price">
            </div>
        </div>
        <div class="mb-4">
            <label for="is-discount" class="block text-gray-700 font-bold mb-2">Is Discounted?</label>
            <select id="is-discount" class="w-full p-2 border rounded-md" v-model="form.is_discount">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="product-image" class="block text-gray-700 font-bold mb-2">Main Image</label>
            <ImageUpload :productImg="form.image" @image="(e) => form.image = e" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="in-stock" class="block text-gray-700 font-bold mb-2">In Stock?</label>
                <select id="in-stock" class="w-full p-2 border rounded-md" v-model="form.in_stock">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label for="stock" class="block text-gray-700 font-bold mb-2">Stock Quantity</label>
                <input type="number" id="stock" class="w-full p-2 border rounded-md" placeholder="Enter stock quantity"
                    min="0" v-model="form.stock">
            </div>
        </div>
        <div class="mb-4">
            <label for="remark" class="block text-gray-700 font-bold mb-2">Remark</label>
            <select id="remark" class="w-full p-2 border rounded-md" v-model="form.remark">
                <option value="regular">Regular</option>
                <option value="popular">Popular</option>
                <option value="new">New</option>
                <option value="top">Top</option>
                <option value="special">Special</option>
                <option value="trending">Trending</option>
            </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
                <select id="category" class="w-full p-2 border rounded-md" v-model="form.category">
                    <option value="">Select Category</option>
                    <option v-for="(category, index) in categories" :key="index" :value="category.id">{{
                        category.name }}</option>
                </select>
            </div>
            <div>
                <label for="product" class="block text-gray-700 font-bold mb-2">Brand</label>
                <select id="product" class="w-full p-2 border rounded-md" v-model="form.brand">
                    <option value="">Select Brand</option>
                    <option v-for="(brand, index) in brands" :key="index" :value="brand.id">{{ brand.name }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Product Details Section -->
        <h3 class="text-xl font-bold mb-4">Product Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="img1" class="block text-gray-700 font-bold mb-2">Image 1</label>
                <ImageUpload :productImg="form.img1" @image="(e) => form.img1 = e" />
            </div>
            <div>
                <label for="img2" class="block text-gray-700 font-bold mb-2">Image 2</label>
                <ImageUpload :productImg="form.img2" @image="(e) => form.img2 = e" />
            </div>
            <div>
                <label for="img3" class="block text-gray-700 font-bold mb-2">Image 3</label>
                <ImageUpload :productImg="form.img3" @image="(e) => form.img3 = e" />
            </div>
            <div>
                <label for="img4" class="block text-gray-700 font-bold mb-2">Image 4</label>
                <ImageUpload :productImg="form.img4" @image="(e) => form.img4 = e" />
            </div>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Full Description</label>
            <textarea id="description" class="w-full p-2 border rounded-md" rows="6"
                placeholder="Enter full description" v-model="form.description"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="color" class="block text-gray-700 font-bold mb-2">Color</label>
                <input type="text" id="color" class="w-full p-2 border rounded-md" placeholder="Enter color"
                    v-model="form.color">
            </div>
            <div>
                <label for="size" class="block text-gray-700 font-bold mb-2">Size</label>
                <input type="text" id="size" class="w-full p-2 border rounded-md" placeholder="Enter size"
                    v-model="form.size">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Product</button>
    </form>
</template>
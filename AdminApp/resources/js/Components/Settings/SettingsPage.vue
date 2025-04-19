<script setup>
import { useForm, usePage, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({
	position: "top-right",
	duration: 3000,
});
const form = useForm({
	store_id: '',
	store_password: '',
	currency: '',
	success_url: '',
	fail_url: '',
	cancel_url: '',
	ipn_url: '',
	init_url: ''
});

const page = usePage();
const settingsx = page.props.setting;

form.store_id = settingsx.store_id;
form.store_password = settingsx.store_password;
form.currency = settingsx.currency;
form.success_url = settingsx.success_url;
form.fail_url = settingsx.fail_url;
form.cancel_url = settingsx.cancel_url;
form.ipn_url = settingsx.ipn_url;
form.init_url = settingsx.init_url;

const submitForm = () => {
	form.put(`/settings/${settingsx.id}`, {
		onSuccess: () => {
			if (page.props.flash?.status == true) {
				toaster.success(page.props.flash?.message || 'Profile updated successfully');
				router.get("/settings");
			} else {
				toaster.error(page.props.flash?.message || "Profile update failed. Please check your credentials.");
			}
		}
	});
}


</script>

<template>
	<h3 class="text-lg font-bold mb-4">SSLCommerz Payment Gateway Configuration</h3>
	<form class="bg-white shadow-md rounded-lg p-6" @submit.prevent="submitForm">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<div class="mb-4">
				<label for="store-id" class="block text-gray-700 font-bold mb-2">Store ID</label>
				<input type="text" id="store-id" class="w-full py-2 px-4 border border-gray-300 rounded-md" placeholder="Enter Store ID"
					required v-model="form.store_id">
			</div>
			<div class="mb-4">
				<label for="store-password" class="block text-gray-700 font-bold mb-2">Store
					Password</label>
				<input type="text" id="store-password" class="w-full py-2 px-4 border border-gray-300 rounded-md"
					placeholder="Enter Store Password" required v-model="form.store_password">
			</div>
			<div class="mb-4">
				<label for="currency" class="block text-gray-700 font-bold mb-2">Currency</label>
				<input type="text" id="currency" class="w-full py-2 px-4 border border-gray-300 rounded-md" placeholder="Enter Store ID"
					required v-model="form.currency">
			</div>
			<div class="mb-4">
				<label for="success-url" class="block text-gray-700 font-bold mb-2">Success URL</label>
				<input type="text" id="success-url" class="w-full py-2 px-4 border border-gray-300 rounded-md" v-model="form.success_url"
					required>
			</div>
			<div class="mb-4">
				<label for="fail-url" class="block text-gray-700 font-bold mb-2">Fail URL</label>
				<input type="text" id="fail-url" class="w-full py-2 px-4 border border-gray-300 rounded-md" v-model="form.fail_url" required>
			</div>
			<div class="mb-4">
				<label for="cancel-url" class="block text-gray-700 font-bold mb-2">Cancel URL</label>
				<input type="text" id="cancel-url" class="w-full py-2 px-4 border border-gray-300 rounded-md" v-model="form.cancel_url"
					required>
			</div>
			<div class="mb-4">
				<label for="ipn-url" class="block text-gray-700 font-bold mb-2">IPN URL</label>
				<input type="text" id="ipn-url" class="w-full py-2 px-4 border border-gray-300 rounded-md" v-model="form.ipn_url" required>
			</div>
			<div class="mb-4">
				<label for="init-url" class="block text-gray-700 font-bold mb-2">INIT URL</label>
				<input type="text" id="init-url" class="w-full py-2 px-4 border border-gray-300 rounded-md" v-model="form.init_url" required>
			</div>
		</div>
		<button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save Settings</button>
	</form>

</template>
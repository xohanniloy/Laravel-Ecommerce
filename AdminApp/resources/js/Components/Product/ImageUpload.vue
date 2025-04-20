<script setup>
import { ref } from 'vue';
const props = defineProps({
    productImg: String
});
const currentImg = props.productImg ? props.productImg : "/placeholder.png";
const previewImage = ref(currentImg);
const emit = defineEmits(['image']);
const imageSelected = (event) => {
    const file = event.target.files[0];
    if (file) {
        previewImage.value = URL.createObjectURL(file);
        emit('image', file);
    }
}
</script>

<template>
	<div class="flex flex-col gap-4">
		<div>
			<label for="image" class="text-sm font-medium text-gray-700">Upload Image</label>
			<input
				id="image"
				type="file"
				accept="image/*"
				@change="imageSelected"
				class="block w-full text-sm text-gray-700
					file:mr-4 file:py-2 file:px-4
					file:rounded-md file:border-0
					file:text-sm file:font-semibold
					file:bg-blue-50 file:text-blue-700
					hover:file:bg-blue-100
					transition duration-200
                    border-1 border-solid rounded-md p2"
			/>
		</div>

		<!-- ✅ Show Preview -->
		<img
			v-if="previewImage"
			:src="previewImage"
			alt="Selected Image"
			class="w-24 h-24 object-cover rounded-md border border-gray-300"
		/>
	</div>
</template>

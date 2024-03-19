<script setup>
import { ref } from 'vue';

const getRandomImagePath = () => {
    const imagePaths = [
        "https://dnshare02.nyc3.digitaloceanspaces.com/valorizar/public/capa-login-1.png",
        "https://dnshare02.nyc3.digitaloceanspaces.com/valorizar/public/capa-login-2.png",
        "https://dnshare02.nyc3.digitaloceanspaces.com/valorizar/public/capa-login-3.png",
    ];
    const randomIndex = Math.floor(Math.random() * imagePaths.length);
    return imagePaths[randomIndex];
};
const selectedImage = ref(getRandomImagePath());
</script>
<template>
    <div class="min-h-screen flex flex-row">
        <div class="basis-3/6 lg:basis-4/6 items-center overflow-hidden hidden md:block relative">
            <img :src="selectedImage" class="min-h-full object-cover" />
        </div>
        <div class="w-full md:basis-3/6 lg:basis-2/6 flex flex-col items-center justify-center">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden">
                <slot />
            </div>
        </div>
    </div>
</template>

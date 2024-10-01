<template>
    <div class="flex justify-between px-4 mt-4 sm:px-8">
        <h2 class="text-2xl text-gray-600">{{ title }} {{ optionalText }}</h2>

        <div class="flex items-center space-x-1 text-xs">
            <Link :href="route('dashboard')" class="font-bold text-indigo-700">Home</Link>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600">{{ name }}</span>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    title: String,
    name:String,
    optionalText:null

});
</script>

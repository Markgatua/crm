<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1  text-sm font-medium leading-5 text-white-900 focus:outline-none transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1  text-sm font-medium leading-5 text-white-500 hover:text-white focus:outline-none focus:text-green-500 focus:border-gray-300 transition duration-150 ease-in-out';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

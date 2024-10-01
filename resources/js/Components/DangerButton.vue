<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center justify-center px-4 py-2 bg-danger border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-danger active:bg-danger focus:outline-none focus:ring-2 focus:ring-danger focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>

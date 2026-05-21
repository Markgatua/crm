<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="loading"
        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-danger border border-transparent rounded-lg font-semibold text-[0.8125rem] text-white shadow-sm hover:bg-danger-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-danger/30 focus-visible:ring-offset-1 disabled:opacity-40 disabled:pointer-events-none transition-all duration-200"
        :class="{ 'opacity-60 pointer-events-none': loading }"
    >
        <svg v-if="loading" class="animate-spin -ml-0.5 mr-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <slot />
    </button>
</template>

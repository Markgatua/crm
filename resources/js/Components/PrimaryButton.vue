<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    loading: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg'].includes(v),
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="loading"
        class="crm-btn crm-btn-primary"
        :class="{
            'crm-btn-sm': size === 'sm',
            'crm-btn-lg': size === 'lg',
            'opacity-60 pointer-events-none': loading,
        }"
    >
        <svg v-if="loading" class="animate-spin -ml-0.5 mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <slot />
    </button>
</template>

<style scoped>
.crm-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    font-weight: 600;
    font-size: 0.8125rem;
    line-height: 1;
    letter-spacing: 0.01em;
    border-radius: 0.5rem;
    padding: 0.5625rem 1rem;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}
.crm-btn:focus-visible {
    outline: 2px solid var(--color-primary, #1A56DB);
    outline-offset: 2px;
}
.crm-btn-sm {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}
.crm-btn-lg {
    font-size: 0.875rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.625rem;
}
.crm-btn-primary {
    background: #1A56DB;
    color: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06), 0 1px 3px rgba(26,86,219,0.15);
}
.crm-btn-primary:hover {
    background: #1848C5;
    box-shadow: 0 4px 14px rgba(26,86,219,0.3);
    transform: translateY(-0.5px);
}
.crm-btn-primary:active {
    background: #1341B2;
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
}
</style>

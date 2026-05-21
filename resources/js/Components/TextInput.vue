<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    hasError: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="crm-input"
        :class="{ 'crm-input-error': hasError }"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>

<style scoped>
.crm-input {
    width: 100%;
    border-radius: 0.5rem;
    border: 1px solid #E5E7EB;
    background: #FFFFFF;
    padding: 0.5625rem 0.875rem;
    font-size: 0.8125rem;
    line-height: 1.5;
    color: #1F2937;
    transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1);
}
.crm-input::placeholder {
    color: #9CA3AF;
}
.crm-input:hover {
    border-color: #D1D5DB;
}
.crm-input:focus {
    border-color: #1A56DB;
    box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.12);
    outline: none;
}
.crm-input-error {
    border-color: #DC2626;
}
.crm-input-error:focus {
    border-color: #DC2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
}
</style>

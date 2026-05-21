<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close']);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 rounded-full bg-primary flex-shrink-0"></div>
                <div class="text-base font-bold text-gray-800">
                    <slot name="title" />
                </div>
            </div>
            <button
                v-if="closeable"
                @click="close"
                class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-6 py-5 text-sm text-gray-600">
            <slot name="content" />
        </div>

        <div class="flex flex-row justify-end gap-2 px-6 py-4 bg-gray-50/80 border-t border-gray-100">
            <slot name="footer" />
        </div>
    </Modal>
</template>

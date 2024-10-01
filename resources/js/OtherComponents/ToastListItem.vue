<script setup>
import {computed, onMounted} from "vue";
import {FaRegCheckCircle} from "vue3-icons/fa";
import {GiCancel} from "vue3-icons/gi";

const props = defineProps({
    message: String,
    type:Object,
    duration: {
        type: Number,
        default: 10000
    }
});

onMounted(() => {
    setTimeout(() => emit("remove"), props.duration);
});

const icon = computed(() => {
    return !!(props.type && props.type.success);
});

const emit = defineEmits(["remove"]);
</script>
<template>
    <div
        class="flex items-center rounded-lg bg-white p-4 text-gray-500 shadow"
        role="alert"
    >
        <div
            class="inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-500"
        >
            <FaRegCheckCircle v-if="icon" class="text-green-500" />
            <GiCancel v-else class="text-red-500" />
        </div>
        <div class="ml-3 text-sm text-black font-normal">{{ props.message }}</div>
        <button
            @click="emit('remove')"
            type="button"
            class="-mx-1.5 -my-1.5 ml-auto inline-flex items-center justify-center h-8 w-8 rounded-lg bg-white p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-900 focus:ring-2 focus:ring-gray-300"
            data-dismiss-target="#toast-default"
            aria-label="Close"
        >
            <GiCancel class="text-gray-500 w-full h-full" />
        </button>
    </div>
</template>

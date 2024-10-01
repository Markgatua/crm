<script setup>
import {onMounted, ref} from 'vue';

defineProps({
    modelValue: {
        type: [String],
    },
});


defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <div class="hidden lg:block">
        <div class="relative group">
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
                placeholder="Search..."
                autocomplete="off" type="text"
                class='form-input rounded-md bg-white-700 text-sm text-gray-700 pl-10 py-1.5 ml-5 border-transparent border-none outline-none focus:ring-0 focus:text-black transition-all duration-300 ease-in-out focus:w-60 w-48'
            />
            <span
                class="absolute left-44 bottom-2 text-gray-400 transition-all duration-300 ease-in-out group-focus-within:left-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
        </div>
    </div>
</template>

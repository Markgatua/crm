<template>
  <div class="relative w-full" ref="dropdownRef">
    <details class="relative p-2 cursor-pointer w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none">
      <summary class="font-medium text-gray-700">
        <span v-if="internalValue.length > 0">
          {{ internalValue.map(item => item.name).join(', ') }}
        </span>
        <span v-else>Select Items</span>
      </summary>

      <!-- Dropdown List -->
      <div class="absolute top-full left-0 z-10 shadow-lg mt-2 space-y-1 w-full rounded border border-stroke bg-white py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none">
        <div
          v-for="(item, index) in options"
          :key="index"
          class="flex items-center space-x-2"
        >
          <input
            type="checkbox"
            :id="`item-${index}`"
            :value="item"
            v-model="internalValue"
            class="cursor-pointer"
          />
          <label :for="`item-${index}`" class="cursor-pointer">
            {{ item.name }}
          </label>
        </div>
      </div>
    </details>

    <!-- Display selected items as chips -->
    <div class="mt-4 flex flex-wrap space-x-2">
      <div
        v-for="(item, index) in internalValue"
        :key="index"
        class="flex items-center space-x-1 bg-blue-100 text-blue-700 px-2 py-1 rounded-full"
      >
        <span>{{ item.name }}</span>
        <button
          @click="removeItem(item)"
          class="text-red-500 hover:text-red-700"
        >
          &times;
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { onClickOutside } from "@vueuse/core";

// Props for passing options from the parent component
const props = defineProps({
  options: {
    type: Array,
    required: true,
  },
  modelValue: {
    type: Array,
    default: () => [],
  }
});

const emit = defineEmits(['update:modelValue']);

const internalValue = ref([...props.modelValue]);
const dropdownRef = ref(null);

watch(internalValue, (newVal) => {
  emit('update:modelValue', newVal);
});

const removeItem = (item) => {
  internalValue.value = internalValue.value.filter((i) => i !== item);
};

onClickOutside(dropdownRef, () => {
  const details = dropdownRef.value.querySelector('details');
  if (details) {
    details.open = false;
  }
});
</script>

<style scoped>
/* Custom styling if needed */
</style>

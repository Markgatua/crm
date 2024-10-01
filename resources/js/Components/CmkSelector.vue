<template>
    <div ref="target" class="relative">
        <div class="relative cursor-pointer" @click="showSelector">
            <div>
                <input
                    ref="inputRef"
                    :placeholder="placeholder"
                    class="block w-full focus:outline-none text-black bg-white px-3 py-2 border rounded-md"
                    :type="inputType"
                    v-model="inputComputed"
                    autocomplete="off"
                    :maxlength="max"
                    :disabled="disabled"
                    readonly
                />

                <span v-if="error" class="text-[#fa2702] text-[14px] font-semibold">{{ error }}</span>
            </div>

            <FaAngleDown class="absolute top-1/2 transform -translate-y-1/2 right-1 text-lg text-gray-500"/>
        </div>

        <div v-show="isSelector" :class="dropdownClass" class="border rounded-md w-full bg-white p-2 absolute z-50 max-h-72 overflow-y-auto">
            <div class="relative mb-4">
                <input
                    type="text"
                    v-model="searchInput"
                    class="focus:outline-none w-full p-2 border-2 rounded-md"
                    placeholder="Search Item"
                />

                <CiSearch class="absolute top-1/2 transform -translate-y-1/2 right-2 text-gray-500"/>
            </div>

            <p class="border rounded-md p-2 mb-2 cursor-pointer flex justify-between items-center" v-for="item in handleSearch(items)" @click="selectItem(item)">{{ item[displayKey] }} <IoIosCheckmark v-if="(selected[displayKey] || inputComputed) === item[displayKey]" class="text-xl"/></p>
        </div>
    </div>
</template>

<script setup>
    import { FaAngleDown } from "vue3-icons/fa";
    import { IoIosCheckmark } from "vue3-icons/io";
    import { CiSearch } from "vue3-icons/ci";

    import { onClickOutside } from '@vueuse/core';
    import { ref, defineEmits, defineProps, toRefs, onMounted, computed } from "vue";

    const emit = defineEmits(['update:input','selectedItem']);

    const props = defineProps(['input', 'placeholder', 'inputType', 'max', 'autoFocus', 'error', 'disabled', 'items', 'displayKey']);
    const { input, placeholder, inputType, max, autoFocus, error, items, displayKey } = toRefs(props);

    const inputRef = ref(null);
    const target = ref(null);

    onMounted(() => {
        if (autoFocus.value) {
            inputRef.value.focus();
        }
    });

    const inputComputed = computed({
        get: () => input.value,
        set: (val) => emit('update:input', val)
    });

    let selected = ref('');
    let searchInput = ref('');
    let isSelector = ref(false);
    let dropdownClass = ref('');

    const showSelector = () => {
        const rect = target.value.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (rect.bottom + 200 < windowHeight) {
            dropdownClass.value = 'top-full mt-1';
        } else {
            dropdownClass.value = 'bottom-full mb-1';
        }

        isSelector.value = !isSelector.value;
    };

    const selectItem = (item) => {
        selected.value = item;
        emit('update:input', item[displayKey.value]);
        emit('selectedItem', item);
        isSelector.value = false;
    };

    onClickOutside(target, event => {
        isSelector.value = false;
    });

    const handleSearch = (items) => {
        return items.filter(item => item[displayKey.value].toLowerCase().includes(searchInput.value.toLowerCase()));
    };
</script>

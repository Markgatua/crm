<script setup lang="ts">
import { useSidebarStore } from '@/Stores/sidebar'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

const sidebarStore = useSidebarStore()

const props = defineProps(['items', 'page'])
const items = ref(props.items)

const handleItemClick = (index: number) => {
  const pageName =
    sidebarStore.selected === props.items[index].label ? '' : props.items[index].label
  sidebarStore.selected = pageName
}
</script>

<template>
  <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
    <template v-for="(childItem, index) in items" :key="index">
      <li>
        <Link
          :href="childItem.isNamed?route(childItem.route):childItem.route"
          @click="handleItemClick(index)"
          class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-white duration-300 ease-in-out"
          :class="{
            '!text-black-2': childItem.label === sidebarStore.selected
          }"
        >
          {{ childItem.label }}
        </Link>
      </li>
    </template>
  </ul>
</template>

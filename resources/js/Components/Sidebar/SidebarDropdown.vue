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
  <ul class="mt-0.5 mb-1 flex flex-col gap-0.5 pl-6">
    <template v-for="(childItem, index) in items" :key="index">
      <li>
        <Link
          :href="childItem.isNamed ? route(childItem.route) : childItem.route"
          @click="handleItemClick(index)"
          class="group flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-150"
          :class="childItem.label === sidebarStore.selected
            ? 'text-white font-semibold'
            : ''"
        >
          <span
            class="w-1 h-1 rounded-full flex-shrink-0 transition-colors"
            :class="childItem.label === sidebarStore.selected ? 'bg-primary' : 'bg-slate-600 group-hover:bg-slate-400'"
          ></span>
          {{ childItem.label }}
        </Link>
      </li>
    </template>
  </ul>
</template>

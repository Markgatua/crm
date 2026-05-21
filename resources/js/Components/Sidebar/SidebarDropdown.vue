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
  <ul class="mt-1 mb-1.5 flex flex-col gap-0.5 pl-5 ml-3 border-l border-slate-800">
    <template v-for="(childItem, index) in items" :key="index">
      <li>
        <Link
          :href="childItem.isNamed ? route(childItem.route) : childItem.route"
          @click="handleItemClick(index)"
          class="group flex items-center gap-2 rounded-md px-2.5 py-1.5 text-xs font-medium text-slate-400 hover:bg-white/[0.06] hover:text-slate-200 transition-all duration-150"
          :class="childItem.label === sidebarStore.selected
            ? 'text-white font-semibold bg-white/[0.04]'
            : ''"
        >
          <span
            class="w-1.5 h-1.5 rounded-full flex-shrink-0 transition-all duration-200"
            :class="childItem.label === sidebarStore.selected
              ? 'bg-primary scale-125 shadow-sm shadow-primary/50'
              : 'bg-slate-700 group-hover:bg-slate-500'"
          ></span>
          <span class="truncate">{{ childItem.label }}</span>
        </Link>
      </li>
    </template>
  </ul>
</template>

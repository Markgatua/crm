<script setup lang="ts">
import { useSidebarStore } from '@/Stores/sidebar'
import SidebarDropdown from './SidebarDropdown.vue'
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

const sidebarStore = useSidebarStore()

const props = defineProps(['item', 'index'])

interface SidebarItem {
  label: string
}

const handleItemClick = () => {
  const pageName = sidebarStore.page === props.item.label ? '' : props.item.label
  sidebarStore.page = pageName

  if (props.item.children) {
    return props.item.children.some((child: SidebarItem) => sidebarStore.selected === child.label)
  }
}
</script>

<template>
  <li v-if="item.condition">
    <Link
      :href="item.isNamed ? route(item.route) : item.route"
      class="group flex items-center gap-2.5 rounded-lg py-2 px-3 text-sm font-medium text-slate-400 transition-colors duration-150 hover:bg-slate-800 hover:text-white"
      @click.prevent="handleItemClick"
      :class="sidebarStore.page === item.label
        ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/25'
        : ''"
    >
      <Icon
        class="h-[17px] w-[17px] flex-shrink-0 transition-colors"
        :class="sidebarStore.page === item.label ? 'text-white' : 'text-slate-500 group-hover:text-white'"
        :icon="item.icon" :ssr="true"
      />
      <span class="flex-1 leading-none">{{ item.label }}</span>
      <span v-if="item.children"
        class="text-xs font-bold select-none transition-colors"
        :class="sidebarStore.page === item.label ? 'text-white/70' : 'text-slate-600'"
      >{{ sidebarStore.page === item.label ? '˅' : '›' }}</span>
    </Link>
    <div class="overflow-hidden transition-all duration-200" v-show="sidebarStore.page === item.label">
      <SidebarDropdown
        v-if="item.children"
        :items="item.children"
        :currentPage="currentPage"
        :page="item.label"
      />
    </div>
  </li>
</template>

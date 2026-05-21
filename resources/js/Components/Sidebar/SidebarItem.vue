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
      class="group relative flex items-center gap-2.5 rounded-lg py-2 px-3 text-[0.8125rem] font-medium text-slate-400 transition-all duration-200 hover:bg-white/[0.06] hover:text-white"
      @click.prevent="handleItemClick"
      :class="sidebarStore.page === item.label
        ? 'bg-primary text-white font-semibold shadow-lg shadow-primary/20'
        : ''"
    >
      <Icon
        class="h-[17px] w-[17px] flex-shrink-0 transition-colors duration-200"
        :class="sidebarStore.page === item.label ? 'text-white' : 'text-slate-500 group-hover:text-slate-300'"
        :icon="item.icon" :ssr="true"
      />
      <span class="flex-1 leading-none truncate">{{ item.label }}</span>
      <svg
        v-if="item.children"
        class="w-3.5 h-3.5 flex-shrink-0 transition-transform duration-200"
        :class="[
          sidebarStore.page === item.label ? 'text-white/70 rotate-90' : 'text-slate-600',
        ]"
        fill="none" stroke="currentColor" viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </Link>
    <transition
      enter-active-class="transition-all duration-200 ease-out"
      enter-from-class="max-h-0 opacity-0"
      enter-to-class="max-h-96 opacity-100"
      leave-active-class="transition-all duration-150 ease-in"
      leave-from-class="max-h-96 opacity-100"
      leave-to-class="max-h-0 opacity-0"
    >
      <div class="overflow-hidden" v-show="sidebarStore.page === item.label">
        <SidebarDropdown
          v-if="item.children"
          :items="item.children"
          :currentPage="currentPage"
          :page="item.label"
        />
      </div>
    </transition>
  </li>
</template>

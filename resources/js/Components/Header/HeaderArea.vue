<script setup lang="ts">
import { useSidebarStore } from "@/Stores/sidebar";
import DropdownMessage from "./DropdownMessage.vue";
import DropdownNotification from "./DropdownNotification.vue";
import DropdownUser from "./DropdownUser.vue";
import { Link } from "@inertiajs/vue3";

const { toggleSidebar } = useSidebarStore();
const sidebarStore = useSidebarStore();
</script>

<template>
  <header class="sticky top-0 z-[999] flex w-full bg-white/80 backdrop-blur-xl border-b border-gray-200/60">
    <div class="flex flex-grow items-center justify-between py-2.5 px-4 md:px-6 lg:px-8">
      <!-- Mobile hamburger + logo -->
      <div class="flex items-center gap-3 lg:hidden">
        <button
          class="p-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
          @click="toggleSidebar()"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <Link href="/" class="flex items-center gap-2">
          <div class="flex items-center justify-center w-7 h-7 rounded-md bg-gradient-to-br from-primary to-blue-600">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
          </div>
          <span class="font-bold text-gray-800">XCRM</span>
        </Link>
      </div>

      <!-- Search bar (desktop) -->
      <div class="hidden lg:flex items-center">
        <div class="relative group">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            type="text"
            placeholder="Search accounts, deals, contacts..."
            class="pl-9 pr-4 py-2 text-[0.8125rem] rounded-lg border border-gray-200 bg-gray-50/80 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary/40 w-72 transition-all placeholder:text-gray-400"
          />
          <kbd class="hidden xl:inline-flex absolute right-3 top-1/2 -translate-y-1/2 px-1.5 py-0.5 text-[0.625rem] font-medium text-gray-400 bg-gray-100 rounded border border-gray-200">⌘K</kbd>
        </div>
      </div>

      <!-- Right actions -->
      <div class="flex items-center gap-1.5">
        <DropdownNotification />
        <DropdownMessage />
        <div class="w-px h-6 bg-gray-200 mx-1.5 hidden sm:block"></div>
        <DropdownUser />
      </div>
    </div>
  </header>
</template>

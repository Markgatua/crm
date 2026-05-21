<script setup lang="ts">
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'

const target = ref(null)
const dropdownOpen = ref(false)
const notifying = ref(true)

onClickOutside(target, () => {
  dropdownOpen.value = false
})

const notificationItems = ref([
  {
    route: '#',
    title: 'Edit your information in a swipe',
    details: 'Sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.',
    time: '12 May, 2025',
    icon: '✏️',
  },
  {
    route: '#',
    title: 'It is a long established fact',
    details: 'that a reader will be distracted by the readable.',
    time: '24 Feb, 2025',
    icon: '📋',
  },
  {
    route: '#',
    title: 'There are many variations',
    details: 'of passages of Lorem Ipsum available, but the majority have suffered',
    time: '04 Jan, 2025',
    icon: '🔔',
  },
  {
    route: '#',
    title: 'There are many variations',
    details: 'of passages of Lorem Ipsum available, but the majority have suffered',
    time: '01 Dec, 2024',
    icon: '📊',
  }
])
</script>

<template>
  <div class="relative" ref="target">
    <button
      class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all duration-200"
      @click.prevent="(dropdownOpen = !dropdownOpen), (notifying = false)"
    >
      <span
        v-if="notifying"
        class="absolute -top-0.5 -right-0.5 z-10 h-2 w-2 rounded-full bg-danger border-2 border-white"
      >
        <span class="absolute inset-0 inline-flex h-full w-full animate-ping rounded-full bg-danger opacity-60"></span>
      </span>
      <svg class="w-[18px] h-[18px] text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
    </button>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95 -translate-y-1"
      enter-to-class="opacity-100 scale-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100 translate-y-0"
      leave-to-class="opacity-0 scale-95 -translate-y-1"
    >
      <div
        v-show="dropdownOpen"
        class="absolute right-0 mt-2 w-80 max-h-[28rem] flex flex-col rounded-xl border border-gray-200/80 bg-white shadow-dropdown z-50 origin-top-right overflow-hidden"
      >
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
          <h5 class="text-[0.8125rem] font-bold text-gray-800">Notifications</h5>
          <span class="badge badge-primary">{{ notificationItems.length }} new</span>
        </div>

        <ul class="flex-1 overflow-y-auto thin-scrollbar divide-y divide-gray-50">
          <template v-for="(item, index) in notificationItems" :key="index">
            <li>
              <a
                :href="item.route"
                class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-50/80 transition-colors group"
              >
                <span class="text-lg flex-shrink-0 mt-0.5">{{ item.icon }}</span>
                <div class="flex-1 min-w-0">
                  <p class="text-[0.8125rem] font-semibold text-gray-800 group-hover:text-primary transition-colors leading-snug">
                    {{ item.title }}
                  </p>
                  <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ item.details }}</p>
                  <p class="text-[0.6875rem] text-gray-400 mt-1.5 font-medium">{{ item.time }}</p>
                </div>
              </a>
            </li>
          </template>
        </ul>

        <div class="border-t border-gray-100 px-5 py-2.5">
          <a href="#" class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors">View all notifications →</a>
        </div>
      </div>
    </transition>
  </div>
</template>

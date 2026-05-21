<script setup lang="ts">
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3';

declare function route(name: string): string;

const target = ref(null)
const dropdownOpen = ref(false)
const page = usePage();

onClickOutside(target, () => {
  dropdownOpen.value = false
})

const logout = () => {
  router.post(route('logout'));
};

const initials = () => {
  const u = (page.props.auth as any).user;
  return ((u.first_name?.[0] ?? '') + (u.last_name?.[0] ?? '')).toUpperCase();
};
</script>

<template>
  <div class="relative" ref="target">
    <button
      class="flex items-center gap-2.5 cursor-pointer p-1 rounded-lg hover:bg-gray-50 transition-colors"
      @click.prevent="dropdownOpen = !dropdownOpen"
    >
      <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-indigo-600 text-white text-xs font-bold shadow-sm">
        {{ initials() }}
      </div>
      <span class="hidden lg:block text-left">
        <span class="block text-[0.8125rem] font-semibold text-gray-800 leading-tight">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</span>
        <span class="block text-[0.6875rem] text-gray-400 leading-tight">{{ $page.props.auth.user.email }}</span>
      </span>
      <svg class="hidden sm:block w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
        class="absolute right-0 mt-2 w-60 rounded-xl border border-gray-200/80 bg-white shadow-dropdown py-1.5 z-50 origin-top-right"
      >
        <div class="px-4 py-3 border-b border-gray-100">
          <p class="text-[0.8125rem] font-semibold text-gray-800">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</p>
          <p class="text-[0.6875rem] text-gray-400 truncate mt-0.5">{{ $page.props.auth.user.email }}</p>
        </div>
        <div class="py-1">
          <Link
            :href="route('profile.show')"
            class="flex items-center gap-3 px-4 py-2 text-[0.8125rem] text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors rounded-md mx-1"
          >
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            My Profile
          </Link>
          <Link
            :href="route('profile.show')"
            class="flex items-center gap-3 px-4 py-2 text-[0.8125rem] text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors rounded-md mx-1"
          >
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Account Settings
          </Link>
        </div>
        <div class="border-t border-gray-100 py-1">
          <button
            @click.prevent="logout"
            class="flex w-full items-center gap-3 px-4 py-2 text-[0.8125rem] text-danger hover:bg-danger-light transition-colors rounded-md mx-1"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Sign Out
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

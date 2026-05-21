<script setup>
import SidebarArea from "@/Components/Sidebar/SidebarArea.vue";
import HeaderArea from "@/Components/Header/HeaderArea.vue";
import { Head } from "@inertiajs/vue3";
import ToastList from "@/OtherComponents/ToastList.vue";
import Banner from "@/Components/Banner.vue";

const props = defineProps({
  title: {
    type: String,
    required: true,
  }
});
</script>

<template>
  <Head :title="title + ' | CRM'" />
  <ToastList />
  <Banner />
  <div class="flex h-screen overflow-hidden bg-[#F0F2F5]">
    <SidebarArea />
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <HeaderArea />
      <main class="flex-1">
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 lg:p-8">
          <header v-if="$slots.header">
            <div class="mb-5 py-3.5 px-5 bg-white rounded-xl shadow-card border border-gray-100 flex justify-between items-center">
              <slot name="header" />
            </div>
          </header>
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

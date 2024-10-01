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
  <Head :title="title" />
  <ToastList />
  <Banner />
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- ===== Sidebar Start ===== -->
    <SidebarArea />
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div
      class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden"
    >
      <!-- ===== Header Start ===== -->
      <HeaderArea />
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <!-- Page Heading -->
          <header v-if="$slots.header">
            <div
              class="mx-auto py-6 px-4 bg-gray-100 sm:px-6 lg:px-8 flex justify-between"
            >
              <slot name="header" />
            </div>
          </header>
          <slot></slot>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
  </div>
  <!-- ===== Page Wrapper End ===== -->
</template>

<script setup lang="ts">
import { useSidebarStore } from "@/Stores/sidebar";
import { onClickOutside } from "@vueuse/core";
import { ref } from "vue";
import SidebarItem from "./SidebarItem.vue";
import { Icon } from "@iconify/vue";
import {  usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const target = ref(null);

const sidebarStore = useSidebarStore();

onClickOutside(target, () => {
  sidebarStore.isSidebarOpen = false;
});

const page = usePage();

const menuGroups = ref([
  {
    name: "Dashboard",
    icon: "material-symbols:folder-outline",
    condition: true,
    menuItems: [
      {
        isNamed: true,
        label: "Dashboard",
        route: "dashboard",
        icon: "material-symbols:folder-outline",
        condition: true,
      },
    ],
  },
  {
    name: "Settings",
    icon: "material-symbols:settings-outline",
    condition:
      page.props.auth.user.role_id === 1 || page.props.auth.user.role_id === 5,
    menuItems: [
      {
        label: "Settings",
        icon: "material-symbols:settings-outline",
        condition:
          page.props.auth.user.role_id === 1 ||
          page.props.auth.user.role_id === 5,
        children: [
          { label: "Departments", route: "departments.index", isNamed: true },
          {
            label: "Solution Types",
            route: "solution-type.index",
            isNamed: true,
          },
          { label: "Solution", route: "solution.index", isNamed: true },
          {
            label: "Solution Sub Type",
            route: "solution-sub-type.index",
            isNamed: true,
          },
          {
            label: "Industries/Sectors",
            route: "industries.index",
            isNamed: true,
          },
          {
            label: "Document Types",
            route: "document-type.index",
            isNamed: true,
          },
        ],
      },
    ],
  },
  {
    name: "Presales",
    condition: page.props.auth.user.role_id === 6,
    menuItems: [
      {
        label: "Scooping",
        route: "presales.scoping",
        isNamed: true,
        icon: "solar:stethoscope-linear",
        condition: page.props.auth.user.role_id === 6,
      },
      {
        label: "Pre-Sales(RFQ/TENDERS)",
        route: "presales.tender.rfq",
        isNamed: true,
        icon: "catppuccin:folder-pre-commit-open",
        condition: page.props.auth.user.role_id === 6,
      },
    ],
  },
  {
    name: "User Management",
    condition: page.props.auth.user.role_id === 1,
    menuItems: [
      {
        label: "User Management",
        icon: "la:users-cog",
        condition: page.props.auth.user.role_id === 1,
        children: [{ label: "Users", route: "users.index", isNamed: true }],
      },
    ],
  },
  {
    name: "Accounts",
    condition: page.props.auth.user.role_id === 1,
    menuItems: [
      {
        label: "Accounts",
        icon: "mdi:account-balance-wallet-outline",
        condition: page.props.auth.user.role_id === 1,
        children: [
          { label: "All", route: "clients/all", isNamed: false },
          { label: "Prospects", route: "/clients/bs/1", isNamed: false },
          { label: "Scooping", route: "/clients/bs/2", isNamed: false },
          { label: "Evaluation", route: "/clients/bs/3", isNamed: false },
          { label: "Approval", route: "/clients/bs/4", isNamed: false },
          { label: "Closed Deals", route: "/clients/bs/5", isNamed: false },
          { label: "Lost Deals", route: "/clients/bs/6", isNamed: false },
          { label: "Presales/BDM", route: "/clients/bs/7", isNamed: false },
          { label: "Projects", route: "/clients/bs/8", isNamed: false },
          { label: "Overdue", route: "/clients/bs/9", isNamed: false },
          { label: "Existing", route: "clients/existing", isNamed: true },
        ],
      },
    ],
  },
  {
    name: "Sales Personnels",
    condition: page.props.auth.user.role_id === 1,
    menuItems: [
      {
        label: "Sales Personnels",
        icon: "carbon:sales-ops",
        route: "personnels",
        condition: page.props.auth.user.role_id === 1,
        isNamed: true,
      },
    ],
  },
  {
    name: "Reports",
    condition:
      page.props.auth.user.role_id === 1 || page.props.auth.user.role_id === 5,
    menuItems: [
      {
        label: "Reports",
        icon: "icomoon-free:stats-dots",
        disclosure: true,
        condition:
          page.props.auth.user.role_id === 1 ||
          page.props.auth.user.role_id === 5,
        children: [
          {
            label: "Accounts Sheet",
            route: "reports.accounts.sheet",
            isNamed: true,
          },
          {
            label: "Custom Reports",
            route: "reports.accounts.custom",
            isNamed: true,
          },
        ],
      },
    ],
  },
  {
    name: "My Accounts",
    condition:
      page.props.auth.user.role_id === 1 || page.props.auth.user.role_id === 4,
    menuItems: [
      {
        label: "My Accounts (Grouped By Client)",
        icon: "mdi:account-balance-wallet-outline",
        disclosure: true,
        condition:
          page.props.auth.user.role_id === 1 ||
          page.props.auth.user.role_id === 4,
        children: [
          {
            label: "All accounts",
            route: "sales_person.accounts",
            isNamed: true,
          },
          {
            label: "Prospects",
            route: "/sales_person/clients/bs/1",
            isNamed: false,
          },
          {
            label: "Scooping",
            route: "/sales_person/clients/bs/2",
            isNamed: false,
          },
          {
            label: "Evaluation",
            route: "/sales_person/clients/bs/3",
            isNamed: false,
          },
          {
            label: "Approval",
            route: "/sales_person/clients/bs/4",
            isNamed: false,
          },
          {
            label: "Closed Deals",
            route: "/sales_person/clients/bs/5",
            isNamed: false,
          },
          {
            label: "Lost Deals",
            route: "/sales_person/clients/bs/6",
            isNamed: false,
          },
          {
            label: "Presales/BDM",
            route: "/sales_person/clients/bs/7",
            isNamed: false,
          },
          {
            label: "Projects",
            route: "/sales_person/clients/bs/8",
            isNamed: false,
          },
          {
            label: "Overdue",
            route: "/sales_person/clients/bs/9",
            isNamed: false,
          },
          {
            label: "Accounts in CRM",
            route: "/sales_person/clients/bs/10",
            isNamed: false,
          },
        ],
      },
    ],
  },
  {
    name: "My Reports",
    condition: page.props.auth.user.role_id === 4,
    menuItems: [
      {
        label: "My Reports",
        icon: "icomoon-free:stats-dots",
        condition: page.props.auth.user.role_id === 4,
        children: [
          {
            label: "Custom Report",
            route: "reports.accounts.mycustom",
            isNamed: true,
          },
          { label: "Revenue Reports", route: "dashboard", isNamed: true },
          { label: "KPI Reports", route: "dashboard", isNamed: true },
        ],
      },
    ],
  },
  {
    name: "SMART GOALS",
    condition: page.props.auth.user.role_id === 4,
    menuItems: [
      {
        label: "SMART GOALS",
        icon: "material-symbols:folder-delete-outline",
        condition: page.props.auth.user.role_id === 4,
        children: [
          { label: "Set Monthly Goals", route: "dashboard", isNamed: true },
          { label: "Monthly Goal Reports", route: "dashboard", isNamed: true },
          { label: "Set Quarterly Goals", route: "dashboard", isNamed: true },
          {
            label: "Quarterly Goal Reports",
            route: "dashboard",
            isNamed: true,
          },
        ],
      },
    ],
  },
  {
    name: "CRM",
    condition: page.props.auth.user.role_id === 1,
    menuItems: [
      {
        label: "CRM Accounts",
        icon: "mingcute:content-ai-fill",
        condition: page.props.auth.user.role_id === 1,
        route: "/crm/accounts",
        isNamed: false,
      },
    ],
  },
  {
    name: "Contacts",
    condition:
      page.props.auth.user.role_id === 7 || page.props.auth.user.role_id === 1,
    menuItems: [
      {
        label: "Contacts",
        icon: "fluent-mdl2:contact-list",
        condition:
          page.props.auth.user.role_id === 7 ||
          page.props.auth.user.role_id === 1,
        route: "marketing",
        isNamed: true,
      },
    ],
  },
]);
</script>

<template>
  <aside
    class="absolute left-0 top-0 z-9999 flex h-screen w-80 flex-col overflow-y-hidden bg-primary duration-300 ease-linear lg:static lg:translate-x-0"
    :class="{
      'translate-x-0': sidebarStore.isSidebarOpen,
      '-translate-x-full': !sidebarStore.isSidebarOpen,
    }"
    ref="target"
  >
    <!-- SIDEBAR HEADER -->
    <div
      class="flex menuItems-center justify-between gap-2 px-6 py-5.5 lg:py-6.5"
    >
      <ApplicationLogo />

      <button
        class="block lg:hidden"
        @click="sidebarStore.isSidebarOpen = false"
      >
        <Icon
          class="text-white h-10 w-10"
          icon="weui:back-filled"
          :ssr="true"
        />
      </button>
    </div>
    <!-- SIDEBAR HEADER -->

    <div
      class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear"
    >
      <!-- Sidebar Menu -->
      <nav class="mt-5 py-4 px-4 lg:mt-4 lg:px-6">
        <template v-for="menuGroup in menuGroups" :key="menuGroup.name">
          <div v-if="menuGroup.condition">
            <h3 class="mb-4 ml-4 text-sm font-medium text-gray">
              {{ menuGroup.name.toUpperCase() }}
            </h3>
            <ul class="mb-6 flex flex-col gap-1.5">
              <SidebarItem
                v-for="(menuItem, index) in menuGroup.menuItems"
                :item="menuItem"
                :key="index"
                :index="index"
              />
            </ul>
          </div>
        </template>
      </nav>
      <!-- Sidebar Menu -->

      <div
        class="mx-auto mb-10 w-full max-w-60 rounded-sm bg-white py-6 px-4 text-center  shadow-default"
      >
        <h3 class="mb-1 font-semibold text-black">CRM</h3>
        <p class="mb-4 text-xs text-black">This is version 0.0.3</p>
        <span
          class="flex items-center justify-center rounded-md bg-primary p-2 font-medium text-white hover:bg-opacity-90"
        >
          @XRX
        </span>
      </div>
    </div>
  </aside>
</template>

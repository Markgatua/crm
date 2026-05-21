<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import UpdatePresalesStageModal from "@/Pages/Presales/Partials/UpdatePresalesStageModal.vue";
import { Icon } from "@iconify/vue";
import * as XLSX from 'xlsx';

const props = defineProps({
  client: Object,
  clients: Array,
  clienttypes: Array,
  projectstages: Array,
  solutiontypes: Array,
  solutions: Array,
  solutionsubtypes: Array,
  presales: Array,
  accounts: Array,
});

const filters = ref({
  global: "",
});

const clients = ref(props.accounts ?? []);

const filteredClients = computed(() => {
  const keyword = filters.value.global
    ? filters.value.global.toLowerCase()
    : "";
  if (!keyword) {
    return clients.value;
  }
  return clients.value.filter((client) => {
    return (
      client.solution_name?.toLowerCase().includes(keyword) ||
      client.business_name?.toLowerCase().includes(keyword) ||
      client.latest_stage?.project_stage?.name?.toLowerCase().includes(keyword)
    );
  });
});

const updatingPresalesModal = ref(false);
const updateAccount = ref(null);

const closePresalesModal = () => {
  updatingPresalesModal.value = false;
  updateAccount.value = null;
};
const openPresalesModal = (account) => {
  updateAccount.value = account || null;
  updatingPresalesModal.value = true;
};

const exportToExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.json_to_sheet(filteredClients.value);
  XLSX.utils.book_append_sheet(workbook, worksheet, "Scoping Clients");
  XLSX.writeFile(workbook, "scoping.xlsx");
};

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
  <AppLayout title="Scoping Accounts">
    <PageHeader title="Scoping Accounts" name="Scoping Accounts" />

    <div class="space-y-4">
      <!-- Search, Excel Bar -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
        <div class="relative flex-1 max-w-md">
          <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input type="text" v-model="filters.global" placeholder="Search scoping accounts by name, solution, stage..."
            class="w-full pl-9.5 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary transition-all">
        </div>
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-500 shadow-xs">
            {{ filteredClients.length }} Account{{ filteredClients.length !== 1 ? 's' : '' }}
          </span>
          <SecondaryButton @click="exportToExcel">
            <div class="flex items-center gap-1.5">
              <Icon icon="material-symbols:download" class="h-4 w-4" />
              Excel Export
            </div>
          </SecondaryButton>
        </div>
      </div>

      <!-- Table Card -->
      <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
        <div class="overflow-x-auto thin-scrollbar">
          <table class="min-w-full text-xs text-left">
            <thead>
              <tr class="border-b border-gray-200/80 bg-gray-50/50 text-gray-500 uppercase font-bold tracking-wider">
                <th class="px-6 py-3.5">Account & Solution Name</th>
                <th class="px-6 py-3.5">Solution Type</th>
                <th class="px-6 py-3.5">Instructions & Actions</th>
                <th class="px-6 py-3.5">Current Stage Info</th>
                <th class="px-6 py-3.5 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="(account, index) in filteredClients" :key="index"
                  class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-extrabold flex-shrink-0 shadow-sm ${avatarBg(account.id)}`">
                      {{ (account.business_name?.[0] ?? '?').toUpperCase() }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-bold text-gray-900 truncate">{{ account.business_name }}</p>
                      <p class="text-[10px] text-gray-400 font-semibold truncate max-w-[200px] mt-0.5">{{ account.solution_name }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="badge badge-primary font-bold">
                    {{ account.solution_type_name ?? '—' }}
                  </span>
                </td>
                <td class="px-6 py-4 font-semibold text-gray-600 max-w-[250px] truncate" :title="account.presale_scoping_actions">
                  {{ account.presale_scoping_actions ?? '—' }}
                </td>
                <td class="px-6 py-4">
                  <div v-if="account.latest_stage?.project_stage" class="min-w-[150px]">
                    <Menu as="div" class="relative inline-block text-left">
                      <MenuButton class="text-left focus:outline-none bg-gray-50 hover:bg-gray-100 p-2 rounded-lg border border-gray-200/50 transition-all">
                        <p class="font-bold text-gray-800">{{ account.latest_stage.project_stage.name }}</p>
                        <p class="text-[10px] text-gray-400 font-semibold truncate max-w-[140px] mt-0.5" :title="account.latest_stage.stage_information">
                          {{ account.latest_stage.stage_information || 'No notes' }}
                        </p>
                      </MenuButton>

                      <transition
                        enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0"
                        enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0"
                      >
                        <MenuItems class="absolute right-0 mt-2 w-[280px] origin-top-right z-30 divide-y divide-gray-100 rounded-lg bg-white shadow-card border border-gray-200 focus:outline-none p-3 space-y-2">
                          <MenuItem v-slot="{ active }">
                            <div class="text-[10px] space-y-2 font-medium text-gray-600 max-h-[220px] overflow-y-auto thin-scrollbar">
                              <div v-for="item in account.latest_stage.meta" :key="item.key" class="p-1.5 rounded bg-gray-50 border border-gray-100">
                                 <span class="font-bold text-gray-800 uppercase tracking-wider block mb-1">{{ item.key.replace(/_/g, ' ') }}:</span>
                                 <template v-if="Array.isArray(item.value)">
                                    <ul class="space-y-1 mt-1 pl-1">
                                      <li v-for="subItem in item.value" class="flex items-center gap-1.5" :key="subItem.key">
                                        <template v-if="item.key === 'Documents'">
                                          <Icon icon="flat-color-icons:document" class="h-3.5 w-3.5 flex-shrink-0"/>
                                          <a :href="subItem.value" target="_blank" class='text-primary hover:underline font-bold truncate max-w-[200px]'>{{ subItem.key }}</a>
                                        </template>
                                        <template v-else>
                                          <Icon icon="mingcute:choice-fill" class="text-primary h-3 w-3 flex-shrink-0"/>
                                          <span class="truncate font-semibold">{{ subItem.key.replace(/_/g, ' ') }}: {{ subItem.value }}</span>
                                        </template>
                                      </li>
                                    </ul>
                                  </template>
                                 <template v-else>
                                   <span class="font-semibold text-gray-700">{{ item.value }}</span>
                                 </template>
                              </div>
                            </div>
                          </MenuItem>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                  <span v-else class="text-gray-400">—</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Link :href="route('sales_person.accounts.account', { id: account.id })">
                      <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                        View
                      </button>
                    </Link>
                    <button @click="openPresalesModal(account)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200/50 rounded-lg text-[10px] font-bold text-amber-700 hover:bg-amber hover:text-white transition-all duration-200">
                      Update
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredClients.length === 0">
                <td colspan="5" class="px-6 py-16 text-center">
                  <div class="max-w-xs mx-auto">
                    <p class="text-3xl mb-3">🤝</p>
                    <p class="text-gray-900 font-bold text-sm">No Scoping Accounts Found</p>
                    <p class="text-gray-400 text-xs mt-1 font-medium">Try matching other keywords.</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <UpdatePresalesStageModal
      :show="updatingPresalesModal"
      @close="closePresalesModal"
      :account="updateAccount"
      :nextStage="4"
    />
  </AppLayout>
</template>

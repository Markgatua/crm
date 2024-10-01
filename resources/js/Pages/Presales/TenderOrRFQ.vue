<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
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

const clients = ref(props.accounts);

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
  console.log("Account before opening modal:", account);
  updateAccount.value = account || null;
  console.log("UpdateAccount after assigning:", updateAccount.value);
  updatingPresalesModal.value = true;
};

const exportToExcel = () => {
  const workbook = XLSX.utils.book_new();
  const worksheet = XLSX.utils.json_to_sheet(filteredClients.value);
  XLSX.utils.book_append_sheet(workbook, worksheet, "Sccoping Clients");
  XLSX.writeFile(workbook, "scooping.xlsx");
};
</script>
<template>
  <AppLayout title="Presales/Tenders/RFQ">
  <PageHeader title="Presales/Tenders/RFQ" name="Presales/Tenders/RFQ" />

    <section class="px-4 mt-8 sm:px-8">
      <div
        class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default sm:px-7.5 xl:pb-1"
      >
        <div class="max-w-full overflow-x-auto">
        <div class="mb-4">
          <SecondaryButton @click="exportToExcel">Export to Excel</SecondaryButton>
        </div>
          <table class="w-full table-auto">
            <thead>
              <tr class="bg-gray-2 text-left">
                <th
                  class="min-w-[220px] py-4 px-4 font-medium text-black xl:pl-11"
                >
                  Name
                </th>
                <th class="min-w-[150px] py-4 px-4 font-medium text-black">
                  Solution
                </th>
                <th class="min-w-[150px] py-4 px-4 font-medium text-black">
                  Instructions
                </th>
                <th class="min-w-[150px] py-4 px-4 font-medium text-black">
                  Current stage
                </th>

                <th class="py-4 px-4 font-medium text-black">Actions</th>
              </tr>
            </thead>
            <tbody>
            <tr v-if="filteredClients.length==0">
            <td colspan="5" class="text-center py-6 font-bold">
            No presale/account/rfq accounts found!
            </td>
            </tr>
              <tr v-for="(account, index) in filteredClients" :key="index">
                <td class="py-5 px-4 pl-9 xl:pl-11">
                  <h5 class="font-medium text-black">
                    {{ account.business_name }}
                  </h5>
                  <p class="text-sm">
                    {{ account.solution_name }}
                  </p>
                </td>
                <td class="py-5 px-4">
                  <p class="text-black">
                    {{ account.solution_type_name }}
                  </p>
                </td>
                <td class="py-5 px-4">
                  <p class="text-black">
                    {{ account.presale_scoping_actions }}
                  </p>
                </td>
                <td class="py-5 px-4">
                <div class="top-16 w-56 text-right">
                  <Menu as="div" class="relative inline-block text-left">
                    <MenuButton
                      class="inline-flex w-full justify-center rounded-md x-4 py-2 text-sm font-medium text-black focus:outline-none"
                    >
                      <div class="px-4 py-3">
                        {{ account.latest_stage.project_stage.name }}
                      </div>
                      <div class="px-4 py-1">
                        {{ account.latest_stage.stage_information }}
                      </div>
                    </MenuButton>

                    <transition
                      enter-active-class="transition duration-100 ease-out"
                      enter-from-class="transform scale-95 opacity-0"
                      enter-to-class="transform scale-100 opacity-100"
                      leave-active-class="transition duration-75 ease-in"
                      leave-from-class="transform scale-100 opacity-100"
                      leave-to-class="transform scale-95 opacity-0"
                    >
                      <MenuItems
                        class="absolute right-0 mt-2 w-fit origin-top-right z-9999 divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                      >
                        <div class="px-2 py-1 ">
                          <MenuItem v-slot="{ active }">
                            <button
                              :class="[
                                active
                                  ? 'bg-gray-2 text-black'
                                  : 'text-gray-900',
                                'group flex w-full items-center flex-col rounded-md px-2 py-2 text-sm',
                              ]"
                            >
                            <li v-for="item in account.latest_stage.meta" :key="item.key">
                               {{ item.key.replace(/_/g, ' ') }}: 
                               <template v-if="Array.isArray(item.value)">
                                      <ul class="list-outside pl-6">
                                        <li v-for="subItem in item.value" class="flex items-center gap-2" :key="subItem.key">
                                          <!-- Check if the key is 'Documents' -->
                                          <template v-if="item.key === 'Documents'">
                                          <Icon icon="flat-color-icons:document"/>
                                            <a :href="subItem.value" target="_blank" class='text-blue-500 font-bold'>{{ subItem.key }}: {{ subItem.value }}</a>
                                          </template>
                                          <template v-else>
                                          <Icon icon="mingcute:choice-fill" class="text-blue-500"/>
                                            {{ subItem.key.replace(/_/g, ' ') }}: {{ subItem.value }}
                                          </template>
                                        </li>
                                      </ul>
                                    </template>
                               <template v-else>
                                 {{ item.value }}
                               </template>
                             </li>
                            </button>
                          </MenuItem>
                        </div>
                      </MenuItems>
                    </transition>
                  </Menu>
                </div>
                </td>

                <td class="py-5 px-4">
                  <div class="flex items-center space-x-3.5">
                    <Link
                      :href="
                        route('sales_person.accounts.account', {
                          id: account.id,
                        })
                      "
                    >
                      <WarningButton>View</WarningButton>
                    </Link>
                    <SecondaryButton
                      @click="openPresalesModal(account)"
                      class="p-button-secondary"
                      >Update</SecondaryButton
                    >
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <UpdatePresalesStageModal
      :show="updatingPresalesModal"
      @close="closePresalesModal"
      :account="updateAccount"
      :nextStage=8
    />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {ref, computed} from "vue";
import {router, useForm,Link} from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {IoMdAdd} from "vue3-icons/io";
import DangerButton from "@/Components/DangerButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from "@/Components/TextInput.vue";
import CustomTextArea from "@/CustomComponents/CustomTextArea.vue";

const props = defineProps({
    clients: Object
})

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    designation: '',
    password: '',
    role_id: '',
    department_id: ''
});

const addingUserModal = ref(false);
const editingUserModal = ref(false);

const editItem = ref(null)

const openAddUserModal = () => {
    addingUserModal.value = true;
};

const saveUser = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeAddUserModal()
            form.reset()
        }
    });
};

const closeAddUserModal = () => {
    addingUserModal.value = false;
    form.reset();
};

const search = ref('');
const filteredClients = computed(() => {
    if (!search.value) return props.clients ?? [];
    const kw = search.value.toLowerCase();
    return (props.clients ?? []).filter(c =>
        `${c.clientname} ${c.clientemail} ${c.clientphone} ${c.industry} ${c.business_name}`.toLowerCase().includes(kw)
    );
});

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-green-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];


const toggleSwitch = (user) => {
    router.put('user/switch', {id:user.id}, {
  onBefore: (visit) => {},
  onStart: (visit) => {},
  onProgress: (progress) => {},
  onSuccess: (page) => {

  },
  onError: (errors) => {},
  onCancel: () => {},
  onFinish: visit => {},
})
    //   axios
    //     .put(`user/switch`, {id:user.id})
    //     .then((response) => {
    //         console.log(user.id);
    //       console.log("Switch updated successfully");
    //     })
    //     .catch((error) => {
    //       console.error("Error updating switch:", error);
    //     });
}

const editUser = (item) => {
    editingUserModal.value = true
    editItem.value = item;
    form.first_name=item.first_name;
    form.last_name=item.last_name;
    form.email=item.email;
    form.phone=item.phone;
    form.designation=item.designation;
    form.department_id=item.department_id;
    form.role_id=item.role_id;
}

const updateUser = () => {
    form.put(route('users.update',{ user: editItem.value }), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal()
            form.reset()
        }
    })
}

const closeEditModal = () => {
    editingUserModal.value = false;
    form.reset();
};

const destroy=(item)=>{
    if (!confirm('Are You sure your want to delete ' + item.name)){
        return
    }
    router.delete(route('users.destroy',{ User: item }))
}
</script>


<template>
    <AppLayout title="Accounts">
        <PageHeader title="Accounts" name="Accounts" />

        <section class="px-4 pb-10 sm:px-8 mt-4 space-y-4">
            <!-- Search bar -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input v-model="search" type="text" placeholder="Search clients…"
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                </div>
                <span class="text-xs text-gray-400 bg-white border border-gray-100 px-3 py-2 rounded-xl shadow-sm font-medium">
                    {{ filteredClients.length }} record{{ filteredClients.length !== 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Table card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/60">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Industry</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account Manager</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Business</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Solution</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in filteredClients" :key="item.id"
                                class="hover:bg-blue-50/30 transition-colors group">
                                <!-- Client name + avatar -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0 ${avatarBg(item.id)}`">
                                            {{ (item.clientname?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ item.clientname }}</p>
                                            <a v-if="item.clientwebsiteurl" :href="item.clientwebsiteurl" target="_blank"
                                               class="text-xs text-primary hover:underline">{{ item.clientwebsiteurl }}</a>
                                        </div>
                                    </div>
                                </td>
                                <!-- Contact info -->
                                <td class="px-5 py-4">
                                    <p class="text-gray-700">{{ item.clientemail }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ item.clientphone }}</p>
                                    <p class="text-gray-400 text-xs">{{ item.clientlocation }}</p>
                                </td>
                                <!-- Industry badge -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-medium">
                                        {{ item.industry }}
                                    </span>
                                </td>
                                <!-- Manager -->
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-700">{{ item.managerfirstname }} {{ item.managerlastname }}</p>
                                    <p class="text-xs text-gray-400">{{ item.manageremail }}</p>
                                    <p class="text-xs text-gray-400">{{ item.managerphone }}</p>
                                </td>
                                <!-- Business -->
                                <td class="px-5 py-4 text-gray-600">{{ item.business_name }}</td>
                                <!-- Solution -->
                                <td class="px-5 py-4">
                                    <span v-if="item.solution_name" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-xs font-medium">
                                        {{ item.solution_name }}
                                    </span>
                                </td>
                                <!-- Actions -->
                                <td class="px-5 py-4 text-right">
                                    <Link :href="route('accounts.account', { id: item.id })">
                                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/20 rounded-lg text-xs font-semibold text-primary hover:bg-primary hover:text-white transition">
                                            View
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <p class="text-4xl mb-2">🏢</p>
                                    <p class="text-gray-500 font-medium">No clients found</p>
                                    <p class="text-gray-400 text-xs mt-1">Try adjusting your search.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

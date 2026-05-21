<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {ref, computed} from "vue";
import {router, useForm, Link} from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];

const toggleSwitch = (user) => {
    router.put('user/switch', {id:user.id}, {
        preserveScroll: true
    })
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
        <PageHeader title="All Accounts" name="Accounts" />

        <div class="space-y-4">
            <!-- Search & Count Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input v-model="search" type="text" placeholder="Search accounts, emails, business names..."
                        class="w-full pl-9.5 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary transition-all">
                </div>
                <span class="inline-flex self-start sm:self-auto items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-500 shadow-xs">
                    {{ filteredClients.length }} Account{{ filteredClients.length !== 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="overflow-x-auto thin-scrollbar">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/50 text-gray-500 uppercase font-bold tracking-wider">
                                <th class="px-6 py-3.5">Client & Organization</th>
                                <th class="px-6 py-3.5">Contact Details</th>
                                <th class="px-6 py-3.5">Industry</th>
                                <th class="px-6 py-3.5">Account Manager</th>
                                <th class="px-6 py-3.5">Business / Deal</th>
                                <th class="px-6 py-3.5">Core Solution</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in filteredClients" :key="item.id"
                                class="hover:bg-gray-50/50 transition-colors group">
                                <!-- Client name + avatar -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-extrabold flex-shrink-0 shadow-sm ${avatarBg(item.id)}`">
                                            {{ (item.clientname?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ item.clientname }}</p>
                                            <a v-if="item.clientwebsiteurl" :href="item.clientwebsiteurl" target="_blank"
                                               class="text-[10px] text-primary hover:underline font-medium block truncate max-w-[150px] mt-0.5">{{ item.clientwebsiteurl }}</a>
                                        </div>
                                    </div>
                                </td>
                                <!-- Contact info -->
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-700">{{ item.clientemail }}</p>
                                    <p class="text-gray-400 font-medium text-[10px] mt-0.5">{{ item.clientphone }}</p>
                                    <p class="text-gray-400 font-medium text-[10px]">{{ item.clientlocation }}</p>
                                </td>
                                <!-- Industry badge -->
                                <td class="px-6 py-4">
                                    <span class="badge badge-primary font-bold">
                                        {{ item.industry }}
                                    </span>
                                </td>
                                <!-- Manager -->
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ item.managerfirstname }} {{ item.managerlastname }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 font-medium">{{ item.manageremail }}</p>
                                </td>
                                <!-- Business -->
                                <td class="px-6 py-4 text-gray-600 font-semibold">{{ item.business_name }}</td>
                                <!-- Solution -->
                                <td class="px-6 py-4">
                                    <span v-if="item.solution_name" class="badge badge-success font-bold">
                                        {{ item.solution_name }}
                                    </span>
                                    <span v-else class="text-gray-400 italic">None</span>
                                </td>
                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <Link :href="route('accounts.account', { id: item.id })" class="inline-block">
                                        <button class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                                            View
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <p class="text-3xl mb-3">🏢</p>
                                        <p class="text-gray-900 font-bold text-sm">No Accounts Found</p>
                                        <p class="text-gray-400 text-xs mt-1 font-medium">Try modifying your search keywords or filter settings.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

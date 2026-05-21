<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { ref, computed } from "vue";
import { router, useForm, Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { IoMdAdd } from "vue3-icons/io";
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

const enabled = ref(false)

const toggleSwitch = (user) => {
    router.put('user/switch', { id: user.id }, {
        onBefore: (visit) => { },
        onStart: (visit) => { },
        onProgress: (progress) => { },
        onSuccess: (page) => { },
        onError: (errors) => { },
        onCancel: () => { },
        onFinish: visit => { },
    })
}

const editUser = (item) => {
    editingUserModal.value = true
    editItem.value = item;
    form.first_name = item.first_name;
    form.last_name = item.last_name;
    form.email = item.email;
    form.phone = item.phone;
    form.designation = item.designation;
    form.department_id = item.department_id;
    form.role_id = item.role_id;
}

const updateUser = () => {
    form.put(route('users.update', { user: editItem.value }), {
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

const destroy = (item) => {
    if (!confirm('Are You sure your want to delete ' + item.name)) {
        return
    }
    router.delete(route('users.destroy', { User: item }))
}

const search = ref('');
const filteredClients = computed(() => {
    if (!search.value) return props.clients ?? [];
    const kw = search.value.toLowerCase();
    return (props.clients ?? []).filter(c =>
        `${c.name} ${c.email} ${c.phone} ${c.location} ${c.industry}`.toLowerCase().includes(kw)
    );
});

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
    <AppLayout title="Clients Directory">
        <PageHeader title="Clients Directory" name="Clients" />

        <div class="space-y-4">
            <!-- Search Count Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" v-model="search" placeholder="Search clients directory by name, email, industry..."
                        class="w-full pl-9.5 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary transition-all">
                </div>
                <span class="inline-flex self-start sm:self-auto items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-500 shadow-xs">
                    {{ filteredClients.length }} Client{{ filteredClients.length !== 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="overflow-x-auto thin-scrollbar">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/50 text-gray-500 uppercase font-bold tracking-wider">
                                <th class="px-6 py-3.5">Name & Website</th>
                                <th class="px-6 py-3.5">Email Address</th>
                                <th class="px-6 py-3.5">Phone Number</th>
                                <th class="px-6 py-3.5">Physical Location</th>
                                <th class="px-6 py-3.5">Industry</th>
                                <th class="px-6 py-3.5">Key Contact Person</th>
                                <th class="px-6 py-3.5 text-right">Accounts</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in filteredClients" :key="item.id"
                                class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-extrabold flex-shrink-0 shadow-sm ${avatarBg(item.id)}`">
                                            {{ (item.name?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ item.name }}</p>
                                            <a v-if="item.website_url" :href="item.website_url" target="_blank"
                                               class="text-[10px] text-primary hover:underline font-medium block truncate max-w-[150px] mt-0.5">{{ item.website_url }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ item.email }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ item.phone }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ item.location }}</td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-primary font-bold">
                                        {{ item.industry ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="item.main_contact_person_name" class="p-2 rounded bg-gray-50 border border-gray-100 min-w-[180px]">
                                        <p class="font-bold text-gray-800">{{ item.main_contact_person_name }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ item.main_contact_person_phone }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold">{{ item.main_contact_person_email }}</p>
                                        <p class="text-[9px] text-primary font-bold uppercase mt-1">{{ item.main_contact_person_designation }}</p>
                                    </div>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Link :href="route('accounts.client', { id: item.id })" class="inline-block">
                                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                                            Accounts
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
                                        <p class="text-gray-900 font-bold text-sm">No Client Records Found</p>
                                        <p class="text-gray-400 text-xs mt-1 font-medium">Try matching other keywords.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create User Modal (Retained to avoid regressions) -->
        <DialogModal :show="addingUserModal" @close="closeAddUserModal">
            <template #title>Create User</template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div class="grid grid-cols-2 gap-3">
                        <TextInput v-model="form.first_name" type="text" placeholder="First Name"/>
                        <TextInput v-model="form.last_name" type="text" placeholder="Last Name"/>
                    </div>
                    <TextInput v-model="form.email" type="text" placeholder="Email Address" class="w-full"/>
                    <div class="grid grid-cols-2 gap-3">
                        <TextInput v-model="form.phone" type="text" placeholder="Phone Number"/>
                        <TextInput v-model="form.designation" type="text" placeholder="Designation"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <select v-model="form.department_id" class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-sm transition-all py-2.5">
                            <option value="" disabled>Select Department</option>
                            <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                        </select>
                        <select v-model="form.role_id" class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-sm transition-all py-2.5">
                            <option value="" disabled>Select Role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                    </div>
                    <TextInput v-model="form.password" type="password" placeholder="Password" class="w-full"/>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeAddUserModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="saveUser">Save</PrimaryButton>
            </template>
        </DialogModal>

        <!-- Edit User Modal (Retained to avoid regressions) -->
        <DialogModal :show="editingUserModal" @close="closeEditModal">
            <template #title>Edit User Profile</template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div class="grid grid-cols-2 gap-3">
                        <TextInput v-model="form.first_name" type="text" placeholder="First Name"/>
                        <TextInput v-model="form.last_name" type="text" placeholder="Last Name"/>
                    </div>
                    <TextInput v-model="form.email" type="text" placeholder="Email Address" class="w-full"/>
                    <div class="grid grid-cols-2 gap-3">
                        <TextInput v-model="form.phone" type="text" placeholder="Phone Number"/>
                        <TextInput v-model="form.designation" type="text" placeholder="Designation"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <select v-model="form.department_id" class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-sm transition-all py-2.5">
                            <option value="" disabled>Select Department</option>
                            <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                        </select>
                        <select v-model="form.role_id" class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-sm transition-all py-2.5">
                            <option value="" disabled>Select Role</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                    </div>
                    <TextInput v-model="form.password" type="password" placeholder="Password" class="w-full"/>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeEditModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="updateUser()">Update</PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
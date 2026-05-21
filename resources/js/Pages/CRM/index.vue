<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { IoMdAdd } from "vue3-icons/io";
import DangerButton from "@/Components/DangerButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from '@/Components/TextInput.vue';
import { MdDelete } from "vue3-icons/md";
import toast from "@/Stores/toast.js";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import MultiSelect from 'primevue/multiselect';
import InputText from 'primevue/inputtext';
import CmkSelector from '@/Components/CmkSelector.vue';

const props = defineProps({
    clients: Array,
    industries: Array,
    existingclients: Array
});

const filters = ref({
    global: '',
});

const existingclients = ref(props.existingclients);

let selectedId = ref(null);
let selectedValue = ref('');

const filteredClients = computed(() => {
    const keyword = filters.value.global.toLowerCase();
    if (!keyword) {
        return existingclients.value;
    }
    return existingclients.value.filter(client => {
        return (
            client.name.toLowerCase().includes(keyword) ||
            (client.industry && client.industry.name.toLowerCase().includes(keyword)) ||
            client.location.toLowerCase().includes(keyword) ||
            client.phone.toLowerCase().includes(keyword) ||
            client.email.toLowerCase().includes(keyword)
        );
    });
});

const loading = ref(false);

const form = useForm({
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: '',
    comments: '',
    contact_information: []
});

const handleSelect = (item) => {
    selectedId.value = item.id;
    form.name = item.name;
    form.phone = item.phone;
    form.email = item.email;
    form.location = item.location;
    form.website_url = item.website_url;
    form.industry_id = item.industry_id;
    form.contact_information = item.contact_information ? JSON.parse(item.contact_information) : [];
}

const editForm = useForm({
    id: -1,
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: '',
    comments: '',
    contact_information: []
});

const addingModal = ref(false);
const editingModal = ref(false);

const openAddModal = () => {
    addingModal.value = true;
};

const addContacts = () => {
    form.contact_information.push({
        id: Math.random(),
        name: "",
        email: "",
        phone: "",
        designation: ""
    });
};

const addEditContacts = () => {
    editForm.contact_information.push({
        id: Math.random(),
        name: "",
        email: "",
        phone: "",
        designation: ""
    });
};

const removeEditContact = (contact) => {
    editForm.contact_information = editForm.contact_information.filter(x => x.id != contact.id);
};

const removeContact = (contact) => {
    form.contact_information = form.contact_information.filter(x => x.id != contact.id);
};

const editClient = (client) => {
    editingModal.value = true;

    editForm.name = client.name;
    editForm.id = client.id;
    editForm.location = client.location;
    editForm.email = client.email;
    editForm.phone = client.phone;
    editForm.website_url = client.website_url;
    editForm.comments = client.comments;
    editForm.industry_id = client.industry_id;
    editForm.contact_information = client.contact_information ? JSON.parse(client.contact_information) : [];
};

const save = () => {
    form.post(route('crm.accounts.create', { id: selectedId.value }), {
        preserveScroll: true,
        onSuccess: () => {
            closeAddModal();
            form.reset();
            location.reload();
        },
        onError: (e) => {
            toast.add(e);
        }
    });
};

const closeAddModal = () => {
    addingModal.value = false;
    form.reset();
};

const update = () => {
    editForm.put(route('update.existing.user', { user: editForm.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal();
            editForm.reset();
            location.reload();
        }
    });
};

const closeEditModal = () => {
    editingModal.value = false;
    form.reset();
};

const destroy = (item) => {
    if (!confirm('Are you sure you want to delete ' + item.name + '?')) {
        return;
    }
    router.delete(route('users.destroy', { User: item }));
    location.reload();
};

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
    <AppLayout title="CRM - Accounts">
        <PageHeader title="CRM Accounts" name="CRM - Accounts">
            <template #actions>
                <PrimaryButton @click="openAddModal()">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Account
                    </div>
                </PrimaryButton>
            </template>
        </PageHeader>

        <div class="space-y-4">
            <!-- Search Count Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" v-model="filters.global" placeholder="Search accounts by name, location, contact..."
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
                                <th class="px-6 py-3.5">Name</th>
                                <th class="px-6 py-3.5">Industry</th>
                                <th class="px-6 py-3.5">Location</th>
                                <th class="px-6 py-3.5">Contact</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
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
                                <td class="px-6 py-4">
                                    <span class="badge badge-primary font-bold">
                                        {{ item.industry?.name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ item.location }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-700">{{ item.email }}</p>
                                    <p class="text-gray-400 font-medium text-[10px] mt-0.5">{{ item.phone }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editClient(item)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-primary-light transition-all duration-200"
                                            title="Edit Account">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <a :href="`/crm/account/businesses/${item.id}`" class="inline-block">
                                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                                                Businesses
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <p class="text-3xl mb-3">🏢</p>
                                        <p class="text-gray-900 font-bold text-sm">No Accounts Found</p>
                                        <p class="text-gray-400 text-xs mt-1 font-medium">Try search keywords or click 'Add Account'.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>Add CRM Account</template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-100">
                        <label class="block text-xs font-bold text-blue-800 uppercase tracking-wider mb-2">Import Existing Client Database</label>
                        <CmkSelector
                            v-model:input="selectedValue"
                            placeholder="Type client name to load..."
                            inputType="text"
                            :max="50"
                            :autoFocus="false"
                            error=""
                            :items="clients"
                            @selectedItem="handleSelect"
                            displayKey="name"
                        />
                    </div>
                    <div class="flex items-center gap-2 py-1">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">or manually input details</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Company Name</label>
                        <TextInput v-model="form.name" type="text" class="block w-full" placeholder="e.g. Acme Corp"/>
                        <InputError :message="form.errors.name" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <TextInput v-model="form.phone" type="text" class="block w-full" placeholder="e.g. +254 700 000 000"/>
                            <InputError :message="form.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <TextInput v-model="form.email" type="email" class="block w-full" placeholder="e.g. contact@acme.com"/>
                            <InputError :message="form.errors.email" class="mt-1"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Website URL</label>
                            <TextInput v-model="form.website_url" type="text" class="block w-full" placeholder="e.g. https://acme.com"/>
                            <InputError :message="form.errors.website_url" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Physical Location</label>
                            <TextInput v-model="form.location" type="text" class="block w-full" placeholder="e.g. Nairobi, Kenya"/>
                            <InputError :message="form.errors.location" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Industry Sector</label>
                        <select v-model="form.industry_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                            <option value="" disabled>Select Industry</option>
                            <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{ industry.name }}</option>
                        </select>
                        <InputError :message="form.errors.industry_id" class="mt-1"/>
                    </div>

                    <!-- Key Contacts inside manual add -->
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Key Contacts</label>
                            <button type="button" @click="addContacts()" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Contact
                            </button>
                        </div>
                        <InputError :message="form.errors.contact_information" class="mb-3" />

                        <div class="space-y-3">
                            <div v-for="(contact, ci) in form.contact_information" :key="contact.id" 
                                 class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 p-3 rounded-lg bg-gray-50/50 border border-gray-100 relative">
                                <div class="flex-grow">
                                    <TextInput v-model="contact.name" type="text" class="w-full !py-1.5 !text-xs" placeholder="Name" />
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.email" type="email" class="w-full !py-1.5 !text-xs" placeholder="Email" />
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.phone" type="text" class="w-full !py-1.5 !text-xs" placeholder="Phone" />
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.designation" type="text" class="w-full !py-1.5 !text-xs" placeholder="Title" />
                                </div>
                                <button type="button" @click="removeContact(contact)" 
                                        class="flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all self-end sm:self-auto flex-shrink-0">
                                    <MdDelete class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeAddModal()">Cancel</SecondaryButton>
                <PrimaryButton class="ms-2" :class="{'opacity-25': form.processing}" :disabled="form.processing" @click.prevent="save">Save Account</PrimaryButton>
            </template>
        </DialogModal>

        <!-- Edit Modal -->
        <DialogModal :show="editingModal" @close="closeEditModal">
            <template #title>Edit Account Details</template>
            <template #content>
                <div class="space-y-4 mt-2">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Company Name</label>
                        <TextInput v-model="editForm.name" type="text" class="block w-full" placeholder="Name"/>
                        <InputError :message="editForm.errors.name" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <TextInput v-model="editForm.phone" type="text" class="block w-full" placeholder="Phone"/>
                            <InputError :message="editForm.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Email Address</label>
                            <TextInput v-model="editForm.email" type="email" class="block w-full" placeholder="Email"/>
                            <InputError :message="editForm.errors.email" class="mt-1"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Website URL</label>
                            <TextInput v-model="editForm.website_url" type="text" class="block w-full" placeholder="https://"/>
                            <InputError :message="editForm.errors.website_url" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Physical Location</label>
                            <TextInput v-model="editForm.location" type="text" class="block w-full" placeholder="Location"/>
                            <InputError :message="editForm.errors.location" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Industry Sector</label>
                        <select v-model="editForm.industry_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                            <option value="" disabled>Select Industry</option>
                            <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{ industry.name }}</option>
                        </select>
                        <InputError :message="editForm.errors.industry_id" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Comments / Notes</label>
                        <TextInput v-model="editForm.comments" type="text" class="block w-full" placeholder="Comments"/>
                        <InputError :message="editForm.errors.comments" class="mt-1"/>
                    </div>

                    <!-- Key Contacts inside edit -->
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Key Contacts</label>
                            <button type="button" @click="addEditContacts()" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Contact
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(contact, ci) in editForm.contact_information" :key="contact.id" 
                                 class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 p-3 rounded-lg bg-gray-50/50 border border-gray-100 relative">
                                <div class="flex-grow">
                                    <TextInput v-model="contact.name" type="text" class="w-full !py-1.5 !text-xs" placeholder="Name"/>
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.email" type="email" class="w-full !py-1.5 !text-xs" placeholder="Email"/>
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.phone" type="text" class="w-full !py-1.5 !text-xs" placeholder="Phone"/>
                                </div>
                                <div class="flex-grow">
                                    <TextInput v-model="contact.designation" type="text" class="w-full !py-1.5 !text-xs" placeholder="Title"/>
                                </div>
                                <button type="button" @click="removeEditContact(contact)" 
                                        class="flex items-center justify-center p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all self-end sm:self-auto flex-shrink-0">
                                    <MdDelete class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeEditModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-2" :class="{'opacity-25': form.processing}" :disabled="form.processing" @click.prevent="update()">Update Account</PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Icon } from "@iconify/vue";
import toast from "@/Stores/toast.js";

const props = defineProps({
    clients: Array,
    industries: Array
});

const filters = ref({
    global: '',
});

const clients = ref(props.clients ?? []);

const filteredClients = computed(() => {
    const keyword = filters.value.global.toLowerCase();
    if (!keyword) {
        return clients.value;
    }
    return clients.value.filter(client => {
        return (
            client.name?.toLowerCase().includes(keyword) ||
            (client.industry && client.industry.name?.toLowerCase().includes(keyword)) ||
            client.location?.toLowerCase().includes(keyword) ||
            client.phone?.toLowerCase().includes(keyword) ||
            client.email?.toLowerCase().includes(keyword)
        );
    });
});

const form = useForm({
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: -1,
    comments: '',
    contact_information: []
});

const editForm = useForm({
    id: -1,
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: -1,
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
        designation: "",
        tag: "",
    });
};

const addEditContacts = () => {
    editForm.contact_information.push({
        id: Math.random(),
        name: "",
        email: "",
        phone: "",
        designation: "",
        tag: "",
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
    try {
        editForm.contact_information = typeof client.contact_information === 'string' 
            ? JSON.parse(client.contact_information) 
            : (client.contact_information ?? []);
    } catch(e) {
        editForm.contact_information = [];
    }
};

const save = () => {
    form.post(route('sales_person.accounts.create'), {
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
    editForm.post(route('sales_person.accounts.update', { id: editForm.id }), {
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
    editForm.reset();
};

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
    <AppLayout title="Sales Accounts">
        <PageHeader title="Sales Accounts" name="Sales Accounts" />

        <div class="space-y-4">
            <!-- Search & Actions Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" v-model="filters.global" placeholder="Search sales accounts by name, industry, location..."
                        class="w-full pl-9.5 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-500 shadow-xs">
                        {{ filteredClients.length }} Account{{ filteredClients.length !== 1 ? 's' : '' }}
                    </span>
                    <PrimaryButton @click="openAddModal">
                        <div class="flex items-center gap-1.5">
                            <Icon icon="material-symbols:add" class="h-4 w-4" />
                            Create Account
                        </div>
                    </PrimaryButton>
                </div>
            </div>

            <!-- Spreadsheet Table Card -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="overflow-x-auto thin-scrollbar">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/50 text-gray-500 uppercase font-bold tracking-wider">
                                <th class="px-6 py-3.5">Account & Website</th>
                                <th class="px-6 py-3.5">Industry</th>
                                <th class="px-6 py-3.5">Location</th>
                                <th class="px-6 py-3.5">Phone</th>
                                <th class="px-6 py-3.5">Email</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="client in filteredClients" :key="client.id"
                                class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-extrabold flex-shrink-0 shadow-sm ${avatarBg(client.id)}`">
                                            {{ (client.name?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ client.name }}</p>
                                            <a v-if="client.website_url" :href="client.website_url" target="_blank"
                                               class="text-[10px] text-primary font-bold hover:underline truncate block mt-0.5">{{ client.website_url }}</a>
                                            <span v-else class="text-[10px] text-gray-400 font-semibold mt-0.5">—</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="client.industry?.name" class="badge badge-primary font-bold">
                                        {{ client.industry.name }}
                                    </span>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ client.location || '—' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ client.phone || '—' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-600">{{ client.email || '—' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editClient(client)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                                            Edit
                                        </button>
                                        <a :href="`/sales_person/account/businesses/${client.id}`">
                                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200/50 rounded-lg text-[10px] font-bold text-amber-700 hover:bg-amber hover:text-white transition-all duration-200">
                                                Businesses
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <p class="text-3xl mb-3">💼</p>
                                        <p class="text-gray-900 font-bold text-sm">No Accounts Found</p>
                                        <p class="text-gray-400 text-xs mt-1 font-medium">Try matching other keywords or add a new account.</p>
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
            <template #title>Create Account</template>
            <template #content>
                <div class="space-y-4">
                    <div>
                        <InputLabel value="Company Name" />
                        <TextInput v-model="form.name" type="text" class="mt-1.5 block w-full text-xs" placeholder="Enterprise Name" />
                        <InputError :message="form.errors.name" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Phone" />
                            <TextInput v-model="form.phone" type="text" class="mt-1.5 block w-full text-xs" placeholder="+254 700 000 000" />
                            <InputError :message="form.errors.phone" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="Email Address" />
                            <TextInput v-model="form.email" type="email" class="mt-1.5 block w-full text-xs" placeholder="info@company.co.ke" />
                            <InputError :message="form.errors.email" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Website URL" />
                            <TextInput v-model="form.website_url" type="text" class="mt-1.5 block w-full text-xs" placeholder="https://example.com" />
                            <InputError :message="form.errors.website_url" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="Physical Location" />
                            <TextInput v-model="form.location" type="text" class="mt-1.5 block w-full text-xs" placeholder="HQ, Nairobi" />
                            <InputError :message="form.errors.location" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Industry Vertical" />
                            <select v-model="form.industry_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="-1" disabled>Select Industry</option>
                                <option v-for="industry in industries" :key="industry.id" :value="industry.id">
                                    {{ industry.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.industry_id" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="General Comments" />
                            <TextInput v-model="form.comments" type="text" class="mt-1.5 block w-full text-xs" placeholder="Important details" />
                            <InputError :message="form.errors.comments" class="mt-1" />
                        </div>
                    </div>

                    <!-- Contact details lists -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900 text-xs">Contacts ({{ form.contact_information.length }})</h4>
                            <SecondaryButton @click="addContacts" type="button" class="!py-1.5 !px-3">
                                <div class="flex items-center gap-1">
                                    <Icon icon="material-symbols:add" class="h-4 w-4" />
                                    Add Contact
                                </div>
                            </SecondaryButton>
                        </div>

                        <div class="space-y-3 max-h-[220px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(contact, index) in form.contact_information" :key="contact.id" 
                                 class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-3 relative group">
                                <button type="button" @click="removeContact(contact)"
                                        class="absolute top-2.5 right-2.5 text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <InputLabel value="Contact Name" />
                                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full text-xs" placeholder="Full Name" />
                                    </div>
                                    <div>
                                        <InputLabel value="Designation" />
                                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full text-xs" placeholder="e.g. CTO" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="md:col-span-1">
                                        <InputLabel value="Role Tag" />
                                        <select v-model="contact.tag"
                                            class="w-full mt-1 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2 transition-all">
                                            <option value="" disabled>Select Tag</option>
                                            <option value="Key Contact">Key Contact</option>
                                            <option value="Primary Contact">Primary Contact</option>
                                            <option value="Secondary Contact">Secondary Contact</option>
                                            <option value="Finance Contact">Finance Contact</option>
                                            <option value="Technical Contact">Technical Contact</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Phone" />
                                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full text-xs" placeholder="Phone" />
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Email" />
                                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full text-xs" placeholder="Email" />
                                    </div>
                                </div>
                            </div>

                            <div v-if="form.contact_information.length === 0" class="text-center py-6 text-gray-400 font-semibold text-xs border border-dashed border-gray-200 rounded-xl bg-gray-50/20">
                                No contact persons defined yet.
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <SecondaryButton @click="closeAddModal">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="save">
                        Create Account
                    </PrimaryButton>
                </div>
            </template>
        </DialogModal>

        <!-- Edit Modal -->
        <DialogModal :show="editingModal" @close="closeEditModal">
            <template #title>Edit Account</template>
            <template #content>
                <div class="space-y-4">
                    <div>
                        <InputLabel value="Company Name" />
                        <TextInput v-model="editForm.name" type="text" class="mt-1.5 block w-full text-xs" placeholder="Enterprise Name" />
                        <InputError :message="editForm.errors.name" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Phone" />
                            <TextInput v-model="editForm.phone" type="text" class="mt-1.5 block w-full text-xs" placeholder="+254 700 000 000" />
                            <InputError :message="editForm.errors.phone" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="Email Address" />
                            <TextInput v-model="editForm.email" type="email" class="mt-1.5 block w-full text-xs" placeholder="info@company.co.ke" />
                            <InputError :message="editForm.errors.email" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Website URL" />
                            <TextInput v-model="editForm.website_url" type="text" class="mt-1.5 block w-full text-xs" placeholder="https://example.com" />
                            <InputError :message="editForm.errors.website_url" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="Physical Location" />
                            <TextInput v-model="editForm.location" type="text" class="mt-1.5 block w-full text-xs" placeholder="HQ, Nairobi" />
                            <InputError :message="editForm.errors.location" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Industry Vertical" />
                            <select v-model="editForm.industry_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="-1" disabled>Select Industry</option>
                                <option v-for="industry in industries" :key="industry.id" :value="industry.id">
                                    {{ industry.name }}
                                </option>
                            </select>
                            <InputError :message="editForm.errors.industry_id" class="mt-1" />
                        </div>
                        <div>
                            <InputLabel value="General Comments" />
                            <TextInput v-model="editForm.comments" type="text" class="mt-1.5 block w-full text-xs" placeholder="Important details" />
                            <InputError :message="editForm.errors.comments" class="mt-1" />
                        </div>
                    </div>

                    <!-- Contact details lists -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900 text-xs">Contacts ({{ editForm.contact_information.length }})</h4>
                            <SecondaryButton @click="addEditContacts" type="button" class="!py-1.5 !px-3">
                                <div class="flex items-center gap-1">
                                    <Icon icon="material-symbols:add" class="h-4 w-4" />
                                    Add Contact
                                </div>
                            </SecondaryButton>
                        </div>

                        <div class="space-y-3 max-h-[220px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(contact, index) in editForm.contact_information" :key="contact.id" 
                                 class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-3 relative group">
                                <button type="button" @click="removeEditContact(contact)"
                                        class="absolute top-2.5 right-2.5 text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <InputLabel value="Contact Name" />
                                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full text-xs" placeholder="Full Name" />
                                    </div>
                                    <div>
                                        <InputLabel value="Designation" />
                                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full text-xs" placeholder="e.g. CTO" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="md:col-span-1">
                                        <InputLabel value="Role Tag" />
                                        <select v-model="contact.tag"
                                            class="w-full mt-1 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2 transition-all">
                                            <option value="" disabled>Select Tag</option>
                                            <option value="Key Contact">Key Contact</option>
                                            <option value="Primary Contact">Primary Contact</option>
                                            <option value="Secondary Contact">Secondary Contact</option>
                                            <option value="Finance Contact">Finance Contact</option>
                                            <option value="Technical Contact">Technical Contact</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Phone" />
                                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full text-xs" placeholder="Phone" />
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Email" />
                                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full text-xs" placeholder="Email" />
                                    </div>
                                </div>
                            </div>

                            <div v-if="editForm.contact_information.length === 0" class="text-center py-6 text-gray-400 font-semibold text-xs border border-dashed border-gray-200 rounded-xl bg-gray-50/20">
                                No contact persons defined yet.
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <SecondaryButton @click="closeEditModal">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': editForm.processing }" :disabled="editForm.processing" @click.prevent="update">
                        Update Account
                    </PrimaryButton>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>

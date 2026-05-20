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
    industry_id: -1,
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
    form.contact_information = JSON.parse(item.contact_information);
}

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
    editForm.contact_information = JSON.parse(client.contact_information);
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


</script>


<template>
    <AppLayout title="CRM - Accounts">
        <PageHeader title="CRM Accounts" name="CRM - Accounts">
            <template #actions>
                <button @click="openAddModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Account
                </button>
            </template>
        </PageHeader>

        <section class="px-4 pb-10 sm:px-8 mt-4 space-y-4">
            <!-- Search -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input type="text" v-model="filters.global" placeholder="Search accounts…"
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
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Industry</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in filteredClients" :key="item.id"
                                class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                            {{ (item.name?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ item.name }}</p>
                                            <a v-if="item.website_url" :href="item.website_url" target="_blank"
                                               class="text-xs text-primary hover:underline">{{ item.website_url }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-medium">
                                        {{ item.industry?.name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ item.location }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-gray-700">{{ item.email }}</p>
                                    <p class="text-gray-400 text-xs">{{ item.phone }}</p>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editClient(item)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-primary-light transition"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <a :href="`/crm/account/businesses/${item.id}`">
                                            <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-light text-primary text-xs font-semibold rounded-lg hover:bg-primary hover:text-white transition">
                                                Businesses
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <p class="text-4xl mb-2">🏢</p>
                                    <p class="text-gray-500 font-medium">No accounts found</p>
                                    <p class="text-gray-400 text-xs mt-1">Try searching or add a new account.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Create Modal -->
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>Add CRM Account</template>
            <template #content>
                <div class="space-y-4">
                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-100">
                        <p class="text-xs font-semibold text-blue-700 mb-2">Select from existing database</p>
                        <CmkSelector
                            v-model:input="selectedValue"
                            placeholder="Search existing client…"
                            inputType="text"
                            :max="50"
                            :autoFocus="false"
                            error=""
                            :items="clients"
                            @selectedItem="handleSelect"
                            displayKey="name"
                        />
                        <InputError :message="form.errors.clent_id" class="mt-1"/>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-xs text-gray-400 font-medium">or add manually</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Name</label>
                        <TextInput v-model="form.name" type="text" class="block w-full" placeholder="Company name"/>
                        <InputError :message="form.errors.name" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <TextInput v-model="form.phone" type="text" class="block w-full" placeholder="Phone"/>
                            <InputError :message="form.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                            <TextInput v-model="form.email" type="email" class="block w-full" placeholder="Email"/>
                            <InputError :message="form.errors.email" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Website</label>
                        <TextInput v-model="form.website_url" type="text" class="block w-full" placeholder="https://"/>
                        <InputError :message="form.errors.website_url" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Location</label>
                        <TextInput v-model="form.location" type="text" class="block w-full" placeholder="Location"/>
                        <InputError :message="form.errors.location" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Industry</label>
                        <select v-model="form.industry_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                            <option value="" disabled>Select Industry</option>
                            <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{ industry.name }}</option>
                        </select>
                        <InputError :message="form.errors.industry_id" class="mt-1"/>
                    </div>
                    <!-- Contacts -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-gray-600">Contacts</label>
                            <button @click="addContacts()" class="text-xs text-primary font-semibold hover:underline">+ Add contact</button>
                        </div>
                        <InputError :message="form.errors.contact_information" class="mt-1"/>
                        <div v-for="(contact, ci) in form.contact_information" :key="ci"
                             class="grid grid-cols-4 gap-2 mb-2 p-2 rounded-lg bg-gray-50 border border-gray-100">
                            <TextInput v-model="contact.name" type="text" class="block w-full" placeholder="Name"/>
                            <TextInput v-model="contact.email" type="email" class="block w-full" placeholder="Email"/>
                            <TextInput v-model="contact.phone" type="text" class="block w-full" placeholder="Phone"/>
                            <div class="flex items-center gap-1">
                                <TextInput v-model="contact.designation" type="text" class="block w-full" placeholder="Title"/>
                                <button @click="removeContact(contact)" class="p-1.5 rounded text-red-400 hover:bg-red-50 hover:text-red-600 transition flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
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
            <template #title>Edit Account</template>
            <template #content>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Name</label>
                        <TextInput v-model="editForm.name" type="text" class="block w-full" placeholder="Name"/>
                        <InputError :message="editForm.errors.name" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <TextInput v-model="editForm.phone" type="text" class="block w-full" placeholder="Phone"/>
                            <InputError :message="editForm.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                            <TextInput v-model="editForm.email" type="email" class="block w-full" placeholder="Email"/>
                            <InputError :message="editForm.errors.email" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Website</label>
                        <TextInput v-model="editForm.website_url" type="text" class="block w-full" placeholder="https://"/>
                        <InputError :message="editForm.errors.website_url" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Location</label>
                        <TextInput v-model="editForm.location" type="text" class="block w-full" placeholder="Location"/>
                        <InputError :message="editForm.errors.location" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Industry</label>
                        <select v-model="editForm.industry_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                            <option value="" disabled>Select Industry</option>
                            <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{ industry.name }}</option>
                        </select>
                        <InputError :message="editForm.errors.industry_id" class="mt-1"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Comments</label>
                        <TextInput v-model="editForm.comments" type="text" class="block w-full" placeholder="Comments"/>
                        <InputError :message="editForm.errors.comments" class="mt-1"/>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-gray-600">Contacts</label>
                            <button @click="addEditContacts()" class="text-xs text-primary font-semibold hover:underline">+ Add contact</button>
                        </div>
                        <div v-for="(contact, ci) in editForm.contact_information" :key="ci"
                             class="grid grid-cols-4 gap-2 mb-2 p-2 rounded-lg bg-gray-50 border border-gray-100">
                            <TextInput v-model="contact.name" type="text" class="block w-full" placeholder="Name"/>
                            <TextInput v-model="contact.email" type="email" class="block w-full" placeholder="Email"/>
                            <TextInput v-model="contact.phone" type="text" class="block w-full" placeholder="Phone"/>
                            <div class="flex items-center gap-1">
                                <TextInput v-model="contact.designation" type="text" class="block w-full" placeholder="Title"/>
                                <button @click="removeEditContact(contact)" class="p-1.5 rounded text-red-400 hover:bg-red-50 hover:text-red-600 transition flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeEditModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-2" :class="{'opacity-25': form.processing}" :disabled="form.processing" @click.prevent="update()">Update</PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

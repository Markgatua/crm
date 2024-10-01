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
import InputText from 'primevue/inputtext';

const props = defineProps({
    clients: Array,
    industries: Array
});

const filters = ref({
    global: '',
});

const clients = ref(props.clients);

const filteredClients = computed(() => {
    const keyword = filters.value.global.toLowerCase();
    if (!keyword) {
        return clients.value;
    }
    return clients.value.filter(client => {
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
    editForm.contact_information = JSON.parse(client.contact_information);
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
    <AppLayout title="Accounts">
        <PageHeader title="Accounts" name="accounts" />
        <section class="px-4 mt-8 sm:px-8">
            <!-- {{ clients }} -->
            <section class="bg-gray-50">
                <div class="mx-auto max-w-screen-2xl px-4 lg:px-10">
                    <div class="bg-white relative sm:rounded-lg overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    <PrimaryButton @click="openAddModal()">Add</PrimaryButton>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
             <DataTable :value="filteredClients" :paginator="true" :rows="10" :loading="loading">

                    <form class="flex items-center w-1/2 mb-5">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="simple-search"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                            placeholder="Search" v-model="filters.global">
                        </div>
                    </form>


                <Column field="name" header="Name" :sortable="true">
                  <template #body="slotProps">
                    <div class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                      {{ slotProps.data.name }} <br /> <span>{{ slotProps.data.website_url }}</span>
                    </div>
                  </template>
                </Column>
                <Column field="industry.name" header="Industry" :sortable="true">
                  <template #body="slotProps">
                    <div class="px-4 py-3">{{ slotProps.data.industry?.name }}</div>
                  </template>
                </Column>
                <Column field="location" header="Location" :sortable="true">
                  <template #body="slotProps">
                    <div class="px-4 py-3">{{ slotProps.data.location }}</div>
                  </template>
                </Column>
                <Column field="phone" header="Phone" :sortable="true">
                  <template #body="slotProps">
                    <div class="px-4 py-3">{{ slotProps.data.phone }}</div>
                  </template>
                </Column>
                <Column field="email" header="Email" :sortable="true">
                  <template #body="slotProps">
                    <div class="px-4 py-3">{{ slotProps.data.email }}</div>
                  </template>
                </Column>
                <Column header="Actions">
                  <template #body="slotProps">
                    <PrimaryButton @click="editClient(slotProps.data)" class="p-button-secondary">Edit</PrimaryButton>
                  </template>
                </Column>
                <Column header="Actions">
                  <template #body="slotProps">
                    <a :href="`/sales_person/account/businesses/${slotProps.data.id}`" ><WarningButton class="p-button-secondary">Businesses</WarningButton></a>
                </template>
                </Column>
              </DataTable>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <!-- Create Modal -->
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>
                Create Account
            </template>
            <template #content>
                <div class="mt-4">
                    <TextInput v-model="form.name" type="text" class="mt-1 block w-full" placeholder="Name" />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div class="mt-4 flex">
                    <div class="flex-1 mr-2">
                        <TextInput v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="Phone" />
                        <InputError :message="form.errors.phone" class="mt-2" />
                    </div>
                    <div class="flex-1 ml-2">
                        <TextInput v-model="form.email" type="email" class="mt-1 block w-full" placeholder="Email" />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>
                </div>


                <div class="mt-4">
                    <TextInput v-model="form.website_url" type="text" class="mt-1 block w-full"
                        placeholder="Website url" />
                    <InputError :message="form.errors.website_url" class="mt-2" />
                </div>

                <div class="mt-4">
                    <TextInput v-model="form.location" type="text" class="mt-1 block w-full" placeholder="Location" />
                    <InputError :message="form.errors.location" class="mt-2" />
                </div>

                <div class="mt-4">
                    <select v-model="form.industry_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{
                industry.name }}</option>
                    </select>
                    <InputError :message="form.errors.industry_id" class="mt-2" />
                </div>

                <div class="mt-4">
                    <TextInput v-model="form.comments" type="text" class="mt-1 block w-full" placeholder="Comments" />
                    <InputError :message="form.errors.comments" class="mt-2" />
                </div>

                <div class="mt-4">
                    <p>Contacts</p>
                    <InputError :message="form.errors.contact_information" class="mt-2" />
                </div>

                <div v-for="contact in form.contact_information" class="mt-4 flex items-center">
                    <div class="flex-2 mr-2">
                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full" placeholder="Name" />
                    </div>
                    <div class="flex-2 ml-2">
                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full" placeholder="Email" />
                    </div>
                    <div class="flex-2 ml-2">
                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full" placeholder="Phone" />
                    </div>
                    <div class="flex-2 ml-2">
                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full"
                            placeholder="Designation" />
                    </div>
                    <!-- <div class="flex-2 ml-2">
                        <TextInput v-model="contact.tag" type="text" class="mt-1 block w-full"
                            placeholder="Tag" />
                    </div> -->
                    <div class="flex-2 ml-4">
                    <select v-model="contact.tag"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option value="Key Contact">Key Contact</option>
                        <option value="Primary Contact">Primary Contact</option>
                        <option value="Secondary Contact">Secondary Contact</option>
                        <option value="Finance Contact">Finance Contact</option>
                        <option value="Technical Contact">Technical Contact</option>

                    </select>
                    </div>
                    <div class="flex-1 ml-2">
                        <PrimaryButton class="ms-3 bg-red-500" @click="removeContact(contact)">
                            <MdDelete />
                        </PrimaryButton>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <PrimaryButton class="ms-3" @click="addContacts()">
                        Add contact
                    </PrimaryButton>
                </div>

            </template>

            <template #footer>
                <SecondaryButton @click="closeAddModal()">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click.prevent="save">
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>


        <!-- Edit Modal -->
        <DialogModal :show="editingModal" @close="closeEditModal">
            <template #title>
                Edit client
            </template>


            <template #content>
                <div class="mt-4">
                    <TextInput v-model="editForm.name" type="text" class="mt-1 block w-full" placeholder="Name" />
                    <InputError :message="editForm.errors.name" class="mt-2" />
                </div>
                <div class="mt-4 flex">
                    <div class="flex-1 mr-2">
                        <TextInput v-model="editForm.phone" type="text" class="mt-1 block w-full" placeholder="Phone" />
                        <InputError :message="editForm.errors.phone" class="mt-2" />
                    </div>
                    <div class="flex-1 ml-2">
                        <TextInput v-model="editForm.email" type="email" class="mt-1 block w-full" placeholder="Email" />
                        <InputError :message="editForm.errors.email" class="mt-2" />
                    </div>
                </div>


                <div class="mt-4">
                    <TextInput v-model="editForm.website_url" type="text" class="mt-1 block w-full"
                        placeholder="Website url" />
                    <InputError :message="editForm.errors.website_url" class="mt-2" />
                </div>

                <div class="mt-4">
                    <TextInput v-model="editForm.location" type="text" class="mt-1 block w-full" placeholder="Location" />
                    <InputError :message="editForm.errors.location" class="mt-2" />
                </div>

                <div class="mt-4">
                    <select v-model="editForm.industry_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{industry.name }}</option>
                    </select>
                    <InputError :message="editForm.errors.industry_id" class="mt-2" />
                </div>

                <div class="mt-4">
                    <TextInput v-model="editForm.comments" type="text" class="mt-1 block w-full" placeholder="Comments" />

                    <InputError :message="editForm.errors.comments" class="mt-2" />
                </div>

                <div class="mt-4">
                    <p>Contacts</p>
                    <InputError :message="editForm.errors.contact_information" class="mt-2" />
                </div>

                <div v-for="contact in editForm.contact_information" class="mt-4 flex items-center">
                    <div class="flex-1 mr-2">
                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full" placeholder="Name" />
                    </div>
                    <div class="flex-1 ml-2">
                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full" placeholder="Email" />
                    </div>
                    <div class="flex-1 ml-2">
                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full" placeholder="Phone" />
                    </div>
                    <div class="flex-1 ml-2">
                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full"
                            placeholder="Designation" />
                    </div>
                    <div class="flex-1 ml-2">
                    <select v-model="contact.tag"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option value="Key Contact">Key Contact</option>
                        <option value="Primary Contact">Primary Contact</option>
                        <option value="Secondary Contact">Secondary Contact</option>
                        <option value="Finance Contact">Finance Contact</option>
                        <option value="Technical Contact">Technical Contact</option>

                    </select>
                    </div>
                    <div class="flex-1 ml-2">
                        <PrimaryButton class="ms-3 bg-red-500" @click="removeEditContact(contact)">
                            <MdDelete />
                        </PrimaryButton>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <PrimaryButton class="ms-3" @click="addEditContacts()">
                        Add contact
                    </PrimaryButton>
                </div>

            </template>





            <template #footer>
                <SecondaryButton @click="closeEditModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click.prevent="update()">
                    Update
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

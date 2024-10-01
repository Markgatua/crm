<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Tab, TabGroup, TabList, TabPanel, TabPanels } from "@headlessui/vue";
import { ref, toDisplayString } from "vue";
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
import { MdDelete } from "vue3-icons/md";
import toast from "@/Stores/toast.js"

defineProps({
    clients: Object,
    industries: Object,
    personnels: Object
})

const form = useForm({
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: -1,
    user_id: -1,
    comments: '',
    contact_information: []
});


const editForm = useForm({
    id:-1,
    name: '',
    location: '',
    email: '',
    phone: '',
    website_url: '',
    industry_id: -1,
    user_id: -1,
    comments: '',
    contact_information: []
});


const contacts = ref([])
//contact meta
//name,email,phone,designation
const addingModal = ref(false);
const editingModal = ref(false);

const editItem = ref(null)

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
    })
}

const addEditContacts = () => {
    editForm.contact_information.push({
        id: Math.random(),
        name: "",
        email: "",
        phone: "",
        designation: ""
    })
}

const removeEditContact = (contact) => {
    editForm.contact_information = editForm.contact_information.filter(x => x.id != contact.id)
}

const removeContact = (contact) => {
    form.contact_information = form.contact_information.filter(x => x.id != contact.id)
}


const editClient = (client) => {
    editingModal.value = true

    editForm.name = client.name
    editForm.id = client.id

    editForm.location = client.location
    editForm.email = client.email
    editForm.phone = client.phone
    editForm.website_url = client.website_url
    editForm.comments = client.comments
    editForm.industry_id = client.industry_id
    editForm.user_id = client.user_id
    editForm.contact_information = JSON.parse(client.contact_information)

}
const save = () => {
    form.post(route('create.existing.user'), {
        preserveScroll: true,
        onSuccess: () => {
            closeAddModal()
            form.reset()
        },
        onError: (e) => {
            toast.add(e)
        }
    });
};

const closeAddModal = () => {
    addingModal.value = false;
    form.reset();
};

const enabled = ref(false)

const toggleSwitch = (user) => {
    router.put('user/switch', { id: user.id }, {
        onBefore: (visit) => { },
        onStart: (visit) => { },
        onProgress: (progress) => { },
        onSuccess: (page) => {

        },
        onError: (errors) => { },
        onCancel: () => { },
        onFinish: visit => { },
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


const update = () => {
    editForm.put(route('update.existing.user', { user: editItem.value }), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal()
            editForm.reset()
        }
    })
}


const closeEditModal = () => {
    editingModal.value = false;
    form.reset();
};

const destroy = (item) => {
    if (!confirm('Are You sure your want to delete ' + item.name)) {
        return
    }
    router.delete(route('users.destroy', { User: item }))
}
</script>


<template>
    <AppLayout title="Clients">
        <PageHeader title="Clients" name="Clients" />
        <section class="px-4 mt-8 sm:px-8">
            <!-- {{ clients }} -->
            <section class="bg-gray-50 p-3 sm:p-5">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <div class="bg-white relative sm:rounded-lg overflow-hidden">
                        <div
                            class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2">
                                <form class="flex items-center">
                                    <label for="simple-search" class="sr-only">Search</label>
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" id="simple-search"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                            placeholder="Search">
                                    </div>
                                </form>
                            </div>
                            <div
                                class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                        class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                        type="button">
                                        <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                        Actions
                                    </button>
                                    <div id="actionsDropdown"
                                        class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow ">
                                        <ul class="py-1 text-sm text-gray-700" aria-labelledby="actionsDropdownButton">
                                            <li>
                                                <a href="#" class="block py-2 px-4 hover:bg-gray-100">Mass Edit</a>
                                            </li>
                                        </ul>
                                        <div class="py-1">
                                            <a href="#"
                                                class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Delete
                                                all</a>
                                        </div>
                                    </div>
                                    <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                                        class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                        type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                            class="h-4 w-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Filter
                                        <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    </button>
                                    <PrimaryButton @click="openAddModal()">Add</PrimaryButton>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Name</th>
                                        <th scope="col" class="px-4 py-3">Industry</th>
                                        <th scope="col" class="px-4 py-3">Location</th>
                                        <th scope="col" class="px-4 py-3">Phone</th>
                                        <th scope="col" class="px-4 py-3">Email</th>
                                        <!-- <th scope="col" class="px-4 py-3">Update</th> -->
                                        <!-- <th scope="col" class="px-4 py-3">Delete</th> -->
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in clients" :key="item.id" class="border-b">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                            {{ item.name }} <br> <span>{{ item.website_url }}</span>

                                        </th>
                                        <td class="px-4 py-3">{{ item.industry.name ?? "" }}</td>
                                        <td class="px-4 py-3">{{ item.location }}</td>

                                        <td class="px-4 py-3">{{ item.phone }}</td>
                                        <td class="px-4 py-3">{{ item.email ?? '' }}</td>

                                        <td class="px-4 py-3">
                                            <!-- <div v-for="(ci, index) in item.contact_information" :key="ci.id">
                                        {{ ci.phone }}
                                        </div> -->
                                        </td>


                                        <td class="px-4 py-3">
                                            <PrimaryButton class="ms-3 bg-blue-500" @click="editClient(item)">
                                                Edit
                                            </PrimaryButton>
                                        </td>
                                        <!-- <td class="px-4 py-3">
                                        <PrimaryButton @click.prevent="editUser(item)">Edit</PrimaryButton>
                                    </td>
                                    <td class="px-4 py-3">
                                        <DangerButton @click.prevent="destroy(item)">Delete</DangerButton>
                                    </td> -->

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <!-- Create Modal -->
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>
                Create Client
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
                    <label for="">Select Industry/Sector</label>
                    <select v-model="form.industry_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{
                industry.name }}</option>
                    </select>
                    <InputError :message="form.errors.industry_id" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="">Select Account Manager</label>
                    <select v-model="form.user_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Account Manager</option>
                        <option v-for="manager in personnels" :key="manager.id" :value="manager.id">{{
                             manager.first_name }} {{ manager.last_name }}</option>
                    </select>
                    <InputError :message="form.errors.user_id" class="mt-2" />
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
                    <label for="">Select Industry/Sector</label>
                    <select v-model="editForm.industry_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Industry</option>
                        <option v-for="industry in industries" :key="industry.id" :value="industry.id">{{industry.name }}</option>
                    </select>
                    <InputError :message="editForm.errors.industry_id" class="mt-2" />
                </div>

                <div class="mt-4">
                    <label for="">Select Account Manager</label>
                    <select v-model="editForm.user_id"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Account Manager</option>
                        <option v-for="manager in personnels" :key="manager.id" :value="manager.id">{{manager.first_name }} {{ manager.last_name }}</option>
                    </select>
                    <InputError :message="editForm.errors.user_id" class="mt-2" />
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

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {IoMdAdd} from "vue3-icons/io";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from "@/Components/TextInput.vue";
import CheckBox from "@/Components/Checkbox.vue";
import CustomTextArea from "@/CustomComponents/CustomTextArea.vue";

defineProps({
    users: Object,
    departments: Object,
    roles: Object
})

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    designation: '',
    password: '',
    role_id: '',
    department_id: '',
    salesPerson: false,
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
    form.id = item.id;
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
    <AppLayout title="Users">
       <PageHeader title="Users" name="users" />
       <section class="px-4 mt-8">
           
                    <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden  max-w-screen-xl px-4 ">
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
                                                      clip-rule="evenodd"/>
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
                                <PrimaryButton type="button" @click="openAddUserModal"
                                               class="flex items-center gap-2 justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                                    <IoMdAdd/>
                                    Add
                                </PrimaryButton>
                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200"
                                            type="button">
                                        <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
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
                                            <a href="#" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100">Delete
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
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        Filter
                                        <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Email</th>
                                    <th scope="col" class="px-4 py-3">Phone</th>
                                    <th scope="col" class="px-4 py-3">Department</th>
                                    <th scope="col" class="px-4 py-3">Designation</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Edit</th>
                                    <th scope="col" class="px-4 py-3">Delete</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in users" :key="item.id" class="border-b">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{ item.first_name }} {{ item.last_name }} <br> <span class="text-danger">{{ item.role }}</span>

                                    </th>
                                    <td class="px-4 py-3">{{ item.email }}</td>
                                    <td class="px-4 py-3">{{ item.phone }}</td>
                                    <td class="px-4 py-3">{{ item.department }}</td>
                                    <td class="px-4 py-3">{{ item.designation }}</td>
                                    <td>
                                            <Switch
                                            v-model="item.is_active"
                                            @click="toggleSwitch(item)"
                                            :class="{ 'bg-teal-900': item.is_active, 'bg-teal-700': !item.is_active }"
                                            class="relative inline-flex h-[38px] w-[74px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                            >
                                            <span
                                                aria-hidden="true"
                                                :class="item.is_active ? 'translate-x-9' : 'translate-x-0'"
                                                class="pointer-events-none inline-block h-[34px] w-[34px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                            />
                                            </Switch>
                                    </td>


                                    <td class="px-4 py-3">
                                        <PrimaryButton @click.prevent="editUser(item)">Edit</PrimaryButton>
                                    </td>
                                    <td class="px-4 py-3">
                                        <DangerButton @click.prevent="destroy(item)">Delete</DangerButton>
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <button id="apple-imac-27-dropdown-button"
                                                data-dropdown-toggle="apple-imac-27-dropdown"
                                                class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none"
                                                type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor"
                                                 viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
              
        </section>
         <!-- Create Modal -->
         <DialogModal :show="addingUserModal" @close="closeAddUserModal">
            <template #title>
                Create User
            </template>

            <template #content>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                <TextInput
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="First Name"
                />
                <InputError :message="form.errors.first_name" class="mt-2"/>
                </div>

                <div class="flex-1 ml-2">
                <TextInput
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Last Name"
                />
                <InputError :message="form.errors.last_name" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4">
                    <TextInput
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Email"
                    />
                    <InputError :message="form.errors.email" class="mt-2"/>
                </div>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                    <TextInput
                        v-model="form.phone"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Phone"
                    />
                    <InputError :message="form.errors.phone" class="mt-2"/>
                </div>
                <div class="flex-1 ml-2">
                    <TextInput
                        v-model="form.designation"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Designation"
                    />
                    <InputError :message="form.errors.designation" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                    <select v-model="form.department_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Department</option>
                        <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                    </select>
                    <InputError :message="form.errors.department_id" class="mt-2"/>
                </div>
                <div class="flex-1 ml-2">
                    <select v-model="form.role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Role</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                    <InputError :message="form.errors.role_id" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4 flex">
                    <div class="flex-1 mr-3">
                    <TextInput
                        v-model="form.password"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" class="mt-2"/>
                    </div>
                    <div class="flex-1 ml-2 mt-3">
                        <input type="checkbox" id="salesPersonCheckbox" v-model="form.salesPerson">
                        <label for="salesPersonCheckbox" class="pr-4"> Sales Person</label>
                </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeAddUserModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click.prevent="saveUser"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>


        <!-- Edit Modal -->
        <DialogModal :show="editingUserModal" @close="closeEditModal">
            <template #title>
                Edit {{ editItem.first_name }} {{ editItem.last_name }} Profile
            </template>

            <template #content>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                <TextInput
                    v-model="form.first_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="First Name"
                />
                <InputError :message="form.errors.first_name" class="mt-2"/>
                </div>

                <div class="flex-1 ml-2">
                <TextInput
                    v-model="form.last_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Last Name"
                />
                <InputError :message="form.errors.last_name" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4">
                    <TextInput
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Email"
                    />
                    <InputError :message="form.errors.email" class="mt-2"/>
                </div>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                    <TextInput
                        v-model="form.phone"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Phone"
                    />
                    <InputError :message="form.errors.phone" class="mt-2"/>
                </div>
                <div class="flex-1 ml-2">
                    <TextInput
                        v-model="form.designation"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Designation"
                    />
                    <InputError :message="form.errors.designation" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4 flex">
                <div class="flex-1 mr-2">
                    <select v-model="form.department_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Department</option>
                        <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                    </select>
                    <InputError :message="form.errors.department_id" class="mt-2"/>
                </div>
                <div class="flex-1 ml-2">
                    <select v-model="form.role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Role</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                    </select>
                    <InputError :message="form.errors.role_id" class="mt-2"/>
                </div>
                </div>
                <div class="mt-4">
                    <TextInput
                        v-model="form.password"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" class="mt-2"/>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeEditModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click.prevent="updateUser()"
                >
                    Update
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

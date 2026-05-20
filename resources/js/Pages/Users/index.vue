<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {ref, computed} from "vue";
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

const props = defineProps({
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

const search = ref('');
const filteredUsers = computed(() => {
    if (!search.value) return props.users ?? [];
    const kw = search.value.toLowerCase();
    return (props.users ?? []).filter(u =>
        `${u.first_name} ${u.last_name} ${u.email} ${u.department} ${u.role}`.toLowerCase().includes(kw)
    );
});

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-green-500','bg-purple-500','bg-pink-500','bg-amber-500','bg-teal-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];

const roleColorMap = {
    'Super Admin': 'bg-red-100 text-red-700',
    'Admin': 'bg-orange-100 text-orange-700',
    'Manager': 'bg-purple-100 text-purple-700',
    'Sales': 'bg-blue-100 text-blue-700',
    'Sales Rep': 'bg-blue-100 text-blue-700',
};
const roleColor = (role) => roleColorMap[role] ?? 'bg-gray-100 text-gray-600';
</script>

<template>
    <AppLayout title="Users">
        <PageHeader title="Users" name="Users">
            <template #actions>
                <button @click="openAddUserModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add User
                </button>
            </template>
        </PageHeader>

        <section class="px-4 pb-10 sm:px-8 mt-4 space-y-4">
            <!-- Search bar -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                    <input v-model="search" type="text" placeholder="Search users…"
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                </div>
                <span class="text-xs text-gray-400 bg-white border border-gray-100 px-3 py-2 rounded-xl shadow-sm font-medium">
                    {{ filteredUsers.length }} user{{ filteredUsers.length !== 1 ? 's' : '' }}
                </span>
            </div>

            <!-- Table card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/60">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Designation</th>
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in filteredUsers" :key="item.id"
                                class="hover:bg-blue-50/30 transition-colors group">
                                <!-- User avatar + name -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0 ${avatarBg(item.id)}`">
                                            {{ ((item.first_name?.[0]??'') + (item.last_name?.[0]??'')).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ item.first_name }} {{ item.last_name }}</p>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                                :class="roleColor(item.role)">
                                                {{ item.role }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <!-- Contact -->
                                <td class="px-5 py-4">
                                    <p class="text-gray-700">{{ item.email }}</p>
                                    <p class="text-gray-400 text-xs mt-0.5">{{ item.phone }}</p>
                                </td>
                                <!-- Department -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-medium">
                                        {{ item.department }}
                                    </span>
                                </td>
                                <!-- Designation -->
                                <td class="px-5 py-4 text-gray-600">{{ item.designation }}</td>
                                <!-- Status toggle -->
                                <td class="px-5 py-4">
                                    <button @click="toggleSwitch(item)"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                        :class="item.is_active ? 'bg-green-500' : 'bg-gray-200'">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition duration-200 ease-in-out"
                                            :class="item.is_active ? 'translate-x-5' : 'translate-x-0'"/>
                                    </button>
                                    <span class="ml-2 text-xs" :class="item.is_active ? 'text-green-600 font-medium' : 'text-gray-400'">
                                        {{ item.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <!-- Actions -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click.prevent="editUser(item)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-primary-light transition"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button @click.prevent="destroy(item)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <p class="text-4xl mb-2">👤</p>
                                    <p class="text-gray-500 font-medium">No users found</p>
                                    <p class="text-gray-400 text-xs mt-1">Try adjusting your search or add a new user.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Create Modal -->
        <DialogModal :show="addingUserModal" @close="closeAddUserModal">
            <template #title>Add New User</template>
            <template #content>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">First Name</label>
                            <TextInput v-model="form.first_name" type="text" class="mt-1 block w-full" placeholder="First Name"/>
                            <InputError :message="form.errors.first_name" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name</label>
                            <TextInput v-model="form.last_name" type="text" class="mt-1 block w-full" placeholder="Last Name"/>
                            <InputError :message="form.errors.last_name" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                        <TextInput v-model="form.email" type="text" class="mt-1 block w-full" placeholder="Email"/>
                        <InputError :message="form.errors.email" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <TextInput v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="Phone"/>
                            <InputError :message="form.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Designation</label>
                            <TextInput v-model="form.designation" type="text" class="mt-1 block w-full" placeholder="Designation"/>
                            <InputError :message="form.errors.designation" class="mt-1"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Department</label>
                            <select v-model="form.department_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm text-gray-800 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                                <option value="" disabled>Select Department</option>
                                <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                            </select>
                            <InputError :message="form.errors.department_id" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
                            <select v-model="form.role_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm text-gray-800 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                                <option value="" disabled>Select Role</option>
                                <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                            </select>
                            <InputError :message="form.errors.role_id" class="mt-1"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                            <TextInput v-model="form.password" type="text" class="mt-1 block w-full" placeholder="Password"/>
                            <InputError :message="form.errors.password" class="mt-1"/>
                        </div>
                        <div class="flex items-center gap-2 mt-6">
                            <input type="checkbox" id="salesPersonCheckbox" v-model="form.salesPerson" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                            <label for="salesPersonCheckbox" class="text-sm text-gray-600 font-medium">Sales Person</label>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeAddUserModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-2" :class="{'opacity-25': form.processing}" :disabled="form.processing" @click.prevent="saveUser">Save User</PrimaryButton>
            </template>
        </DialogModal>

        <!-- Edit Modal -->
        <DialogModal :show="editingUserModal" @close="closeEditModal">
            <template #title>Edit — {{ editItem?.first_name }} {{ editItem?.last_name }}</template>
            <template #content>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">First Name</label>
                            <TextInput v-model="form.first_name" type="text" class="mt-1 block w-full" placeholder="First Name"/>
                            <InputError :message="form.errors.first_name" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name</label>
                            <TextInput v-model="form.last_name" type="text" class="mt-1 block w-full" placeholder="Last Name"/>
                            <InputError :message="form.errors.last_name" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                        <TextInput v-model="form.email" type="text" class="mt-1 block w-full" placeholder="Email"/>
                        <InputError :message="form.errors.email" class="mt-1"/>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                            <TextInput v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="Phone"/>
                            <InputError :message="form.errors.phone" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Designation</label>
                            <TextInput v-model="form.designation" type="text" class="mt-1 block w-full" placeholder="Designation"/>
                            <InputError :message="form.errors.designation" class="mt-1"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Department</label>
                            <select v-model="form.department_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm text-gray-800 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                                <option value="" disabled>Select Department</option>
                                <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                            </select>
                            <InputError :message="form.errors.department_id" class="mt-1"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Role</label>
                            <select v-model="form.role_id" class="w-full rounded-lg border border-gray-200 py-2.5 px-3.5 text-sm text-gray-800 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none transition">
                                <option value="" disabled>Select Role</option>
                                <option v-for="r in roles" :key="r.id" :value="r.id">{{ r.name }}</option>
                            </select>
                            <InputError :message="form.errors.role_id" class="mt-1"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                        <TextInput v-model="form.password" type="text" class="mt-1 block w-full" placeholder="Password"/>
                        <InputError :message="form.errors.password" class="mt-1"/>
                    </div>
                </div>
            </template>
            <template #footer>
                <SecondaryButton @click="closeEditModal">Cancel</SecondaryButton>
                <PrimaryButton class="ms-2" :class="{'opacity-25': form.processing}" :disabled="form.processing" @click.prevent="updateUser()">Update</PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {ref} from "vue";
import {router, useForm, Link} from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {IoMdAdd} from "vue3-icons/io";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from "@/Components/TextInput.vue";
import CustomTextArea from "@/CustomComponents/CustomTextArea.vue";

defineProps({
    accounts: Object,
    titlename: Object,
})

const form = useForm({
    name: '',
    description: '',
});

const addingRoleModal = ref(false);
const editingRoleModal = ref(false);

const editItem = ref(null)

const openAddRoleModal = () => {
    addingRoleModal.value = true;
};

const saveRole = () => {
    form.post(route('roles.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeAddRoleModal()
            form.reset()
        }
    });
};

const closeAddRoleModal = () => {
    addingRoleModal.value = false;
    form.reset();
};

const editRole = (item) => {
    editingRoleModal.value = true
    editItem.value = item;
    form.name=item.name;
    form.description=item.description
}

const updateRole = () => {
    form.put(route('roles.update',{ role: editItem.value }), {
        preserveScroll: true,
        onSuccess: () => {
            closeEditModal()
            form.reset()
        }
    })
}

const closeEditModal = () => {
    editingRoleModal.value = false;
    form.reset();
};

const destroy=(item)=>{
    if (!confirm('Are You sure your want to delete ' + item.name)){
        return
    }
    router.delete(route('roles.destroy',{ role: item }))
}
</script>


<template>
    <AppLayout title="Accounts">
       <PageHeader :title="titlename" optionalText="Accounts" name="Accounts" />
       <section class="px-4 mt-8 sm:px-8">
            <section class="bg-gray-50 p-3 sm:p-5">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="item in accounts" :key="item.id" class="border-b p-2">
                            <div class="max-w-sm w-full lg:max-w-full lg:flex mb-2">
                                <div class="w-1/2 bg-white rounded-lg sahdow-lg flex flex-col justify-center items-center">
                                    <div class="text-gray-900 font-bold text-xl mb-2">Account Manager</div>

                                    <div>
                                        <img class="object-center object-cover h-auto w-full" src="https://visualpharm.com/assets/527/Person-595b40b85ba036ed117da7ec.svg" alt="photo">
                                    </div>
                                    <div class="text-center py-8 sm:py-6">
                                        <p class="text-xl text-gray-700 font-bold mb-1">{{  item.accountmanagerfirstname }} {{ item.accountmanagerlastname }}</p>
                                        <p class="text-base text-gray-400 font-small">{{ item.accountmanageremail }}</p>
                                        <p class="text-base text-gray-400 font-normal">{{ item.accountmanagerphone }}</p>
                                    </div>
                                </div>

                                <div class="border-r border-b border-l border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-b lg:rounded-b-none lg:rounded-r p-4 flex flex-col justify-between leading-normal">
                                    <div class="mb-8">
                                        <p class="text-gray-600 flex items-center">
                                            <!-- <svg class="fill-current text-gray-500 w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" />
                                            </svg>  -->
                                            <span class="text-gray-900 font-bold mr-1">Stage: </span>
                                            <span class="text-green-600 font-bold font-italic"> {{ item.project_stage_information.project_stage_name }}</span>
                                        </p>
                                        <div class="text-sky-500 font-bold text-xl mb-2"><Link :href="route('accounts.account', { id: item.id })">{{ item.business_name }}</Link></div>
                                        <h4>CLIENT</h4>
                                        <p class="text-gray-700 text-base overflow-hidden" style="-webkit-line-clamp: 4; max-height: calc(1.5em * 4);">{{ item.clientname }}</p>
                                        <p class="text-gray-700 text-base overflow-hidden" style="-webkit-line-clamp: 4; max-height: calc(1.5em * 4);">{{ item.clientemail }}</p>
                                        <p class="text-gray-700 text-base overflow-hidden" style="-webkit-line-clamp: 4; max-height: calc(1.5em * 4);">{{ item.clientphone }}</p>
                                        <p class="text-gray-700 text-base overflow-hidden" style="-webkit-line-clamp: 4; max-height: calc(1.5em * 4);">{{ item.clientlocation }}</p>
                                        <p class="text-gray-700 text-base overflow-hidden" style="-webkit-line-clamp: 4; max-height: calc(1.5em * 4);">{{ item.clientwebsiteurl }}</p>
                                    </div>
                                    <div class="text-gray-900 font-bold text-xl mb-2">Contact Person</div>

                                    <div v-for="it in JSON.parse(item.contact_information)" class="flex items-center">
                                        <img class="w-10 h-10 rounded-full mr-4" src="/usericon.png" alt="Avatar of Jonathan Reinink">
                                        <div class="">
                                            <!-- <p class="text-black-900 text-bold leading-none">{{ JSON.parse(item.contact_information) }}</p> -->
                                            <p class="text-gray-900 leading-none">{{ it.name }}</p>
                                            <p class="text-gray-900 leading-none">{{ it.email }}</p>
                                            <p class="text-gray-900 leading-none">{{ it.phone }}</p>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </section>
        </section>
         <!-- Create Modal -->
         <DialogModal :show="addingRoleModal" @close="closeAddRoleModal">
            <template #title>
                Create Role
            </template>

            <template #content>
                <div class="mt-4">
                    <TextInput
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2"/>
                </div>
                <div class="mt-4">
                    <CustomTextArea
                        v-model="form.description"
                        type="text"
                        class="mt-1 block w-3/4 max-h-36 min-h-36"
                        placeholder="Description"
                    />
                    <InputError :message="form.errors.description" class="mt-2"/>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeAddRoleModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click.prevent="saveRole"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>


        <!-- Edit Modal -->
        <DialogModal :show="editingRoleModal" @close="closeEditModal">
            <template #title>
                Edit {{ editItem.name }}
            </template>

            <template #content>
                <div class="mt-4">
                    <TextInput
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2"/>
                </div>
                <div class="mt-4">
                    <CustomTextArea
                        v-model="form.description"
                        type="text"
                        class="mt-1 block w-3/4 max-h-36 min-h-36"
                        placeholder="Description"
                    />
                    <InputError :message="form.errors.description" class="mt-2"/>
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
                    @click.prevent="updateRole()"
                >
                    Update
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

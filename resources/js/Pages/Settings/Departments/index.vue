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
    import CustomTextArea from "@/CustomComponents/CustomTextArea.vue";

    defineProps({
        departments: Object,
    })

    const form = useForm({
        name: ''
    });

    const addingDepartmentModal = ref(false);
    const editingDepartmentModal = ref(false);

    const editItem = ref(null)

    const openAddDepartmentModal = () => {
        addingDepartmentModal.value = true;
    };

    const saveDepartment = () => {
        form.post(route('departments.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeAddDepartmentModal()
                form.reset()
            }
        });
    };

    const closeAddDepartmentModal = () => {
        addingDepartmentModal.value = false;
        form.reset();
    };

    const editDepartment = (item) => {
        editingDepartmentModal.value = true
        editItem.value = item;
        form.name=item.name;
        form.description=item.description
    }

    const updateDepartment = () => {
        form.put(route('departments.update',{ department: editItem.value }), {
            preserveScroll: true,
            onSuccess: () => {
                closeEditModal()
                form.reset()
            }
        })
    }

    const closeEditModal = () => {
        editingDepartmentModal.value = false;
        form.reset();
    };

    const destroy=(item)=>{
        if (!confirm('Are You sure your want to delete ' + item.name)){
            return
        }
        router.delete(route('departments.destroy',{ department: item }))
    }
    </script>


    <template>
        <AppLayout title="Departments">
           <PageHeader title="Departments" name="departments" />
           <section class="px-4 mt-8 sm:px-8">
                <section class="bg-gray-50 p-3 sm:p-5">
                    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                        <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
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
                                    <PrimaryButton type="button" @click="openAddDepartmentModal"
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
                                        <th scope="col" class="px-4 py-3">Department</th>
                                        <th scope="col" class="px-4 py-3">Edit</th>
                                        <th scope="col" class="px-4 py-3">Delete</th>
                                        <th scope="col" class="px-4 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in departments" :key="item.id" class="border-b">
                                        <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                            {{ item.name }}
                                        </th>
                                        <td class="px-4 py-3">
                                            <PrimaryButton @click.prevent="editDepartment(item)">Edit</PrimaryButton>
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
                    </div>
                </section>
            </section>
             <!-- Create Modal -->
             <DialogModal :show="addingDepartmentModal" @close="closeAddDepartmentModal">
                <template #title>
                    Create Department
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

                </template>

                <template #footer>
                    <SecondaryButton @click="closeAddDepartmentModal">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click.prevent="saveDepartment"
                    >
                        Save
                    </PrimaryButton>
                </template>
            </DialogModal>


            <!-- Edit Modal -->
            <DialogModal :show="editingDepartmentModal" @close="closeEditModal">
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

                </template>

                <template #footer>
                    <SecondaryButton @click="closeEditModal">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click.prevent="updateDepartment()"
                    >
                        Update
                    </PrimaryButton>
                </template>
            </DialogModal>
        </AppLayout>
    </template>

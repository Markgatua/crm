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
    roles: Object,
    permissions: Object,
    modules: Object
})

const form = useForm({
    role_id: '',
    permission_id: '',
    selectedModules: ''
});



const changeUser = () => {
    const role_id = this.form.role_id;
    // You can use role_id as needed in your method
    console.log("Selected role ID:", role_id);
    // const user_id = this.role_id; 
    const url = "getdep"; 

    this.selectedPermissions = [];
    this.selectedModules = [];

    // Make AJAX request
    axios.post(url, { user_id: user_id })
      .then(response => {
        const data = response.data;
        for (const permissionId of data) {
          // Find permission and module checkboxes by ID and check them
          const permissionCheckbox = document.getElementById('p' + permissionId);
          if (permissionCheckbox) {
            this.selectedPermissions.push(permissionId);
          }

          // Assuming module checkboxes are named pamodule[]
          const moduleCheckbox = document.getElementsByName('pamodule[]').find(checkbox => checkbox.value === permissionId.toString());
          if (moduleCheckbox) {
            moduleCheckbox.checked = true;
            this.selectedModules.push(permissionId);
          }
        }
      })
      .catch(error => {
        console.error('Error fetching data:', error);
      });
  }




// const saveRole = () => {
//     form.post(route('roles.store'), {
//         preserveScroll: true,
//         onSuccess: () => {
//             closeAddRoleModal()
//             form.reset()
//         }
//     });
// };


</script>


<template>
    <AppLayout title="Roles Permissions">
       <PageHeader title="Roles Permissions" name="Roles Permissions" />
       <section class="px-4 mt-8 sm:px-8">
            <section class="bg-gray-50 p-3 sm:p-5">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                       
                        <div class="overflow-x-auto">
                            <div class="col-span-12">
                                <div class="card">
                                <form>
                                    <div style="height:100px;" class="users col-span-3 border">
                                    <label for="role_id" class="text-blue-500">Select Role</label>
                                    <br>
                                    <select v-model="form.role_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-1/4" @change="changeUser">
                                        <option value="" :disabled=true>---- Select Role ----</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                                    </select>
                                    </div>
                                    <div class="col-span-12">
                                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                                        <div v-for="module in modules" :key="module.id" class="col-span-1">
                                        <div class="card">
                                            <div class="card-body p-2">
                                            <div class="flex items-center">
                                                <input type="checkbox" :id="module.module" :value="module.module" v-model="form.selectedModules">
                                                <h5 class="font-bold ml-2">{{ module.module }}</h5>
                                            </div>
                                            <div v-for="permission in permissions" :key="permission.id" class="he mt-3">
                                                <input v-if="permission.module == module.module" type="checkbox" :id="'p' + permission.id" :value="permission.id" v-model="selectedPermissions">
                                                <label v-if="permission.module == module.module" :for="'p' + permission.id" class="ml-2">{{ permission.read_name }}</label>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, watch } from "vue";
import { useForm,Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DialogModal from "@/Components/DialogModal.vue";
import TextInput from '@/Components/TextInput.vue';
import toast from "@/Stores/toast.js";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { MdDelete } from "vue3-icons/md";


const props = defineProps({
    client: Object,
    clients: Array,
    clienttypes: Array,
    projectstages: Array,
    solutiontypes: Array,
    solutions: Array,
    solutionsubtypes: Array,
    presales: Array,
    accounts: Array
});

const filters = ref({
    global: '',
});

const clients = ref(props.accounts);

const filteredClients = computed(() => {
    const keyword = filters.value.global ? filters.value.global.toLowerCase() : '';
    if (!keyword) {
        return clients.value;
    }
    return clients.value.filter(client => {
        return (
            (client.solution_name && client.solution_name.toLowerCase().includes(keyword)) ||
            (client.business_name && client.business_name.toLowerCase().includes(keyword)) ||
             (client.latest_stage.project_stage.name && client.latest_stage.project_stage.name.toLowerCase().includes(keyword))
        );
    });
});

const editItem = ref(null)

const form = useForm({
    solution_name: '',
    client_type_id: 3,
    project_stage_id: 10,
    solution_type_id: null,
    solution_id: null,
    solution_subtype_id: null,
    useaccountcontacts: false,
    contact_information: [],
    client_id: props.client.id,
    next_actions: '',
    expected_sale_value: '',
    expected_month_of_closure: '',
    projected_month_of_closure: '',
    project_delivery_date: '',
    margins_projection: '',
    deal_amount: '',
    probability_of_closure: -1,
    reason_for_losing: '',
    reason_for_overdue: '',
    overdue_next_steps: '',
    presale_assigned: -1,
    documents: [],
    account_id: '',
    site_survey_comments: '',
    site_survey_due_date: '',
    contract_name: '',
    contract_number: '',
    contract_validity: '',
    contract_start_date: '',
    contract_end_date: '',
    contract_license: '',
    contract_license_type: '',
    contract_license_renewal_validity: '',
    contract_license_renewal_start_date: '',
    contract_license_renewal_end_date: '',
});


const updateForm = useForm({
    solution_name: '',
    project_stage_id: 10,
    solution_type_id: null,
    solution_id: null,
    solution_subtype_id: null,
    useaccountcontacts: false,
    account_id: '',
    next_actions: '',
    expected_sale_value: '',
    expected_month_of_closure: '',
    projected_month_of_closure: '',
    project_delivery_date: '',
    margins_projection: '',
    deal_amount: '',
    probability_of_closure: -1,
    reason_for_losing: '',
    reason_for_overdue: '',
    overdue_next_steps: '',
    presale_assigned: -1,
    documents: [],
    site_survey_comments: '',
    site_survey_due_date: '',
    contract_name: '',
    contract_number: '',
    contract_validity: '',
    contract_start_date: '',
    contract_end_date: '',
    contract_license: '',
    contract_license_type: '',
    contract_license_renewal_validity: '',
    contract_license_renewal_start_date: '',
    contract_license_renewal_end_date: '',
});

const addingModal = ref(false);

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

const addDocuments = () => {
    form.documents.push({
        id: Math.random(),
        name: "",
        file: null
    });
};

const handleFileChange = (file, index) => {
      form.documents[index].file = file;
    };


const removeDocument = (document) => {
    form.documents = form.documents.filter(x => x.id != document.id);
};

const removeContact = (contact) => {
    form.contact_information = form.contact_information.filter(x => x.id != contact.id);
};

const save = () => {
    form.post(route('crm.accounts.businesses.create'), {
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

const showSolutionCategory = computed(() => form.project_stage_id == 2 || form.project_stage_id == 3 || form.project_stage_id == 4 || form.project_stage_id == 5 || form.project_stage_id == 7 || form.project_stage_id == 8 || form.project_stage_id  == 10);
const showSolutionField = computed(() => form.solution_type_id !== null && form.project_stage_id == 2 || form.solution_type_id !== null && form.project_stage_id == 3 || form.solution_type_id !== null && form.project_stage_id == 4 || form.solution_type_id !== null && form.project_stage_id == 5 || form.solution_type_id !== null && form.project_stage_id == 7 || form.solution_type_id !== null && form.project_stage_id == 8 || form.solution_type_id !== null && form.project_stage_id == 10 );
const showSolutionSubtypeField = computed(() => form.solution_id !== null && form.project_stage_id == 2 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 3 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 4 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 5 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 7 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 8 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id) || form.solution_id !== null && form.project_stage_id == 10 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id));

const filteredSolutions = computed(() => props.solutions.filter(solution => solution.solution_type_id == form.solution_type_id));
const filteredSolutionSubtypes = computed(() => props.solutionsubtypes.filter(subtype => subtype.solution_id == form.solution_id));

const showSolutionCategoryX = computed(() => form.project_stage_id == 2 );
const showSolutionFieldX = computed(() => form.solution_type_id !== null && form.project_stage_id == 2 );
const showSolutionSubtypeFieldX = computed(() => form.solution_id !== null && form.project_stage_id == 2 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id));

const showLicenseX= computed(() => form.project_stage_id == 10);
const showLicenseTypeX = computed(() => form.contract_license === 'Yes' && form.project_stage_id == 10);



watch(() => form.project_stage_id, (newStage) => {
    if (newStage === 1 || newStage === 2 || newStage === 3) {
        form.next_actions = '';
        form.expected_month_of_closure = '';
        form.expected_sale_value = '';
    }
});
const updateStageModal = ref(false);
const updateStage = (item) => {
    updateStageModal.value = true;
    editItem.value = item;
    updateForm.solution_name=item.solution_name;
    updateForm.project_stage_id=item.project_stage_id;
    updateForm.solution_type_id=item.solution_type_id;
    updateForm.solution_id=item.solution_id;
    updateForm.solution_subtype_id=item.solution_subtype_id;
    updateForm.account_id=item.account_id; //////////////////////////////////////////////////////
    updateForm.next_actions=item.next_actions;
    updateForm.expected_sale_value=item.expected_sale_value;
    updateForm.expected_month_of_closure=item.expected_month_of_closure;
    updateForm.projected_month_of_closure=item.projected_month_of_closure;
    updateForm.project_delivery_date=item.project_delivery_date;
    updateForm.margins_projection=item.margins_projection;
    updateForm.deal_amount=item.deal_amount;
    updateForm.probability_of_closure=item.probability_of_closure;
    updateForm.reason_for_losing=item.reason_for_losing;
    updateForm.reason_for_overdue=item.reason_for_overdue;
    updateForm.overdue_next_steps=item.overdue_next_steps;
    updateForm.presale_assigned=item.presale_assigned;
    updateForm.documents=[];
    updateForm.site_survey_comments=item.site_survey_comments;
    updateForm.site_survey_due_date=item.site_survey_due_date;
    updateForm.contract_name=item.contract_name;
    updateForm.contract_number=item.contract_number;
    updateForm.contract_validity=item.contract_validity;
    updateForm.contract_start_date=item.contract_start_date;
    updateForm.contract_end_date=item.contract_end_date;
    updateForm.contract_license=item.contract_license;
    updateForm.contract_license_type=item.contract_license_type;
    updateForm.contract_license_renewal_validity=item.contract_license_renewal_validity;
    updateForm.contract_license_renewal_start_date=item.contract_license_renewal_start_date;
    updateForm.contract_license_renewal_end_date=item.contract_license_renewal_end_date;
    }

    // const updateDepartment = () => {
    //     form.post(route('sales_person.accounts.businesses.update_stage',{ account: editItem.value }), {
    //         preserveScroll: true,
    //         onSuccess: () => {
    //             closeEditModal()
    //             form.reset()
    //         }
    //     })
    // }



    const updateDepartment = () => {
        console.log('&&&&&&&&&');
        console.log(editItem.value.client_id);
        return;
        form.post(route('sales_person.accounts.businesses.update_stage', {
            id: editItem.value.id,
            cid: editItem.value.client_id
        }), {
            preserveScroll: true,
            onSuccess: () => {
                closeUpdateStageModal();
                form.reset();
                updateForm.reset();
                location.reload(); // Reloads the page to ensure data is up to date
            },
            onError: (e) => {
                toast.add(e); // Display error message using a toast notification
            }
        });
    };


const closeUpdateStageModal  = () => {
    updateStageModal.value = false;
    form.reset();
};
</script>

<style>
/* Custom styles for the pagination */
.p-paginator {
    background-color: #f8f9fa; /* Background color */
    border: 1px solid #dee2e6; /* Border color */
    border-radius: 0.25rem; /* Rounded corners */
    padding: 0.5rem; /* Padding */
}

.p-paginator .p-paginator-pages .p-paginator-page {
    margin: 0 0.25rem; /* Margin between page buttons */
    background-color: #ffffff; /* Page button background color */
    color: #007bff; /* Page button text color */
    border: 1px solid #dee2e6; /* Page button border color */
    border-radius: 0.25rem; /* Rounded corners for page buttons */
}

.p-paginator .p-paginator-pages .p-paginator-page.p-highlight {
    background-color: #007bff; /* Active page button background color */
    color: #ffffff; /* Active page button text color */
    border: 1px solid #007bff; /* Active page button border color */
}
</style>

<template>
    <AppLayout :title="`${client.name} - Businesses`">
        <PageHeader :title="`${client.name} - Businesses`" :name="`${client.name} - Businesses`" />

        <section class="px-4 mt-8 sm:px-8">
            <section class="bg-gray-50 p-3 sm:p-5">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                    <div class="bg-white relative sm:rounded-lg overflow-hidden">
                        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                            <div class="w-full md:w-1/2"></div>
                            <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                                <div class="flex items-center space-x-3 w-full md:w-auto">
                                    <PrimaryButton @click="openAddModal">Add</PrimaryButton>
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
                                            {{ slotProps.data.business_name }} <br /> {{ slotProps.data.solution_name }}
                                        </div>
                                    </template>
                                </Column>
                                <Column field="industry.name" header="Solution" :sortable="true">
                                    <template #body="slotProps">
                                        <div class="px-4 py-3">{{ slotProps.data.solution_type_name }} <br>{{ slotProps.data.solution_name }} <br>{{ slotProps.data.solution_sub_type_name }}</div>
                                    </template>
                                </Column>
                                <Column field="Contact Person" class="w-1/4" header="Contact Person" :sortable="true">
                                <template #body="slotProps">
                                    <div class="px-4 py-3">
                                    <div v-for="(item, index) in JSON.parse(slotProps.data.contact_information)" :key="index">
                                        {{ item.name }} -
                                        <span v-if="item.phone"> {{ item.phone }} <br> </span>
                                        <span v-if="item.designation"> {{ item.designation }} - </span>
                                        <span v-if="item.email"> {{ item.email }} <br></span>

                                        <span v-if="index < JSON.parse(slotProps.data.contact_information).length - 1"> <br> </span>
                                    </div>
                                    </div>
                                </template>
                                </Column>

                                <Column field="Current Stage" header="Current Stage" :sortable="true">
                                    <template #body="slotProps">
                                        <div class="px-4 py-3">{{ slotProps.data.latest_stage.project_stage.name }}</div>
                                        <div class="px-4 py-1">{{ slotProps.data.latest_stage.stage_information }}</div>
                                        <details>
                                            <summary>
                                                details
                                            </summary>
                                                <div v-for="(item, index) in JSON.parse(slotProps.data.latest_stage.meta)" :key="index" class="px-4 py-1">
                                            <span v-if="item.key !== 'Documents'">
                                               <span style="font-weight: bold;">{{ item.key }} :</span> {{ item.value }}
                                            </span>
                                            <span v-else>
                                                <span style="font-weight: bold;"> {{ item.key }} :</span>
                                                <span v-for="(doc, docIndex) in item.value" :key="docIndex">
                                                <a :href="doc.value" target="_blank" style="font-weight: bold;color: blue;">{{ doc.key }}</a>
                                                <span v-if="docIndex < item.value.length - 1">, </span>
                                                </span>
                                            </span>
                                            </div>
                                        </details>

                                    </template>
                                </Column>

                                <Column header="More">
                                    <template #body="slotProps">
                                        <Link :href="route('sales_person.accounts.account', { id: slotProps.data.id })">
                                            <WarningButton >View</WarningButton>
                                        </Link>

                                    </template>
                                </Column>


                                <!-- <Column header="Update Stage">
                                    <template #body="slotProps">
                                        <div class="px-2 py-3">
                                        <WarningButton @click.prevent="updateStage(slotProps.data)">UpdateStage</WarningButton>
                                        </div>

                                    </template>
                                </Column> -->

                                <Column header="Actions">
                                    <template class="" #body="slotProps">
                                        <div class="px-2 py-3">
                                        <PrimaryButton @click="editClient(slotProps.data)" class="p-button-secondary">Edit</PrimaryButton>
                                        </div>
                                    </template>
                                </Column>
                                <!-- <Column header="Actions">
                                    <template #body="slotProps">
                                        <WarningButton @click="editClient(slotProps.data)" class="p-button-secondary">Businesses</WarningButton>
                                    </template>
                                </Column> -->
                            </DataTable>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>
                Add Business to {{ client.name }} Account
            </template>
            <template #content>
                <!-- <div class="mt-4">
                    <label for="">Select Business Type</label>
                    <select v-model="form.client_type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Business Type</option>
                        <option v-for="clienttype in clienttypes" :key="clienttype.id" :value="clienttype.id">{{ clienttype.name }}</option>
                    </select>
                    <InputError :message="form.errors.client_type_id" class="mt-2" />
                </div> -->


                <!-- <div class="mt-4">
                    <label for="">Select Stage</label>
                    <select v-model="form.project_stage_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select stage</option>
                        <option v-for="projectstage in projectstages" :key="projectstage.id" :value="projectstage.id">{{ projectstage.name }}</option>
                    </select>
                    <InputError :message="form.errors.client_type_id" class="mt-2" />
                </div> -->

                <div class="mt-4" v-if="form.project_stage_id === 2 || form.project_stage_id === 3 || form.project_stage_id === 4 || form.project_stage_id === 5 || form.project_stage_id === 6 || form.project_stage_id === 7 || form.project_stage_id === 8 || form.project_stage_id === 9 || form.project_stage_id  === 10">
                    <TextInput v-model="form.solution_name" type="text" class="mt-1 block w-full" placeholder="Business Name (How you Love it)" />
                    <InputError :message="form.errors.solution_name" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showSolutionCategory">
                    <label for="">Select Solution Category</label>
                    <select v-model="form.solution_type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution Category</option>
                        <option v-for="solutiontype in props.solutiontypes" :key="solutiontype.id" :value="solutiontype.id">{{ solutiontype.solution_type_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_type_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showSolutionField">
                    <label for="">Select Solution</label>
                    <select v-model="form.solution_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution</option>
                        <option v-for="solution in filteredSolutions" :key="solution.id" :value="solution.id">{{ solution.solution_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showSolutionSubtypeField">
                    <label for="">Select Solution Subtype</label>
                    <select v-model="form.solution_subtype_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution Subtype</option>
                        <option v-for="subtype in filteredSolutionSubtypes" :key="subtype.id" :value="subtype.id">{{ subtype.solution_sub_type_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_subtype_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 1 ">
                    <TextInput v-model="form.next_actions" type="text" class="mt-1 block w-full" placeholder="Next Actions" />
                    <InputError :message="form.errors.next_actions" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 10">
                    <TextInput v-model="form.contract_name" type="text" class="mt-1 block w-full" placeholder="Contract Name" />
                    <InputError :message="form.errors.contract_name" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 10">
                    <TextInput v-model="form.contract_number" type="text" class="mt-1 block w-full" placeholder="Contract Number" />
                    <InputError :message="form.errors.contract_number" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 10">
                    <TextInput v-model="form.contract_validity" type="text" class="mt-1 block w-full" placeholder="Contract Validity (In Months)" />
                    <InputError :message="form.errors.contract_validity" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 10">
                    <TextInput v-model="form.contract_start_date" type="text" class="mt-1 block w-full"  placeholder="Contract Start Date (dd/mm/yyyy)" />
                    <InputError :message="form.errors.contract_start_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 10">
                    <TextInput v-model="form.contract_end_date" type="text" class="mt-1 block w-full" placeholder="Contract End Date (dd/mm/yyyy)" />
                    <InputError :message="form.errors.contract_end_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showLicenseX">
                    <label for="">Select Contract License</label>
                    <select v-model="form.contract_license" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Contract License</option>
                        <option value="None">None</option>
                        <option value="Yes">Yes</option>
                    </select>
                    <InputError :message="form.errors.contract_license" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showLicenseTypeX">
                    <label for="">Select License Type</label>
                    <select v-model="form.contract_license_type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select License Type</option>
                        <option value="Perpetual">Perpetual</option>
                        <option value="Renewable">Renewable</option>
                    </select>
                    <InputError :message="form.errors.contract_license_type" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.contract_license_type === 'Renewable' && form.project_stage_id === 10 && form.contract_license === 'Yes'">
                    <TextInput v-model="form.contract_license_renewal_validity" type="text" class="mt-1 block w-full" placeholder="Renewable Contract License Validity (In Months)" />
                    <InputError :message="form.errors.contract_license_renewal_validity" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.contract_license_type === 'Renewable' && form.project_stage_id === 10 && form.contract_license === 'Yes'">
                    <TextInput v-model="form.contract_license_renewal_start_date" type="text" class="mt-1 block w-full" placeholder="Renewable Contract License Start Date (dd/mm/yyyy)" />
                    <InputError :message="form.errors.contract_license_renewal_start_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.contract_license_type === 'Renewable' && form.project_stage_id === 10 && form.contract_license === 'Yes'">
                    <TextInput v-model="form.contract_license_renewal_end_date" type="text" class="mt-1 block w-full" placeholder="Renewable Contract License End Date (dd/mm/yyyy)" />
                    <InputError :message="form.errors.contract_license_renewal_end_date" class="mt-2" />
                </div>


                <div class="mt-4" v-if="form.project_stage_id === 3 || form.project_stage_id === 4">
                    <TextInput v-model="form.expected_sale_value" type="text" class="mt-1 block w-full" placeholder="Expected Sale Value" />
                    <InputError :message="form.errors.expected_sale_value" class="mt-2" />

                    <TextInput v-model="form.projected_month_of_closure" type="text" class="mt-4 block w-full" placeholder="Projected Month of Closure" />
                    <InputError :message="form.errors.projected_month_of_closure" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 5">
                    <TextInput v-model="form.deal_amount" type="text" class="mt-1 block w-full" placeholder="Deal Amount" />
                    <InputError :message="form.errors.deal_amount" class="mt-2" />

                    <TextInput v-model="form.margins_projection" type="text" class="mt-4 block w-full" placeholder="Margins Projection" />
                    <InputError :message="form.errors.margins_projection" class="mt-2" />

                    <TextInput v-model="form.project_delivery_date" type="text" class="mt-4 block w-full" placeholder="Project Delivery Date" />
                    <InputError :message="form.errors.project_delivery_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 4">
                    <label for="">Select Probability of Closure</label>
                    <select v-model="form.probability_of_closure" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Probability of Closure</option>
                        <option value="Most Likely">Most Likely</option>
                        <option value="Likely">Likely</option>
                        <option value="Unlikely">Unlikely</option>
                    </select>
                    <InputError :message="form.errors.probability_of_closure" class="mt-2" />

                    <TextInput v-model="form.expected_month_of_closure" type="text" class="mt-4 block w-full" placeholder="Expected Month of Closure" />
                    <InputError :message="form.errors.expected_month_of_closure" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 6">
                    <TextInput v-model="form.reason_for_losing" type="text" class="mt-1 block w-full" placeholder="Reason  for Losing" />
                    <InputError :message="form.errors.reason_for_losing" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 7">
                    <label for="">Select Assigned Presale</label>
                    <select v-model="form.presale_assigned" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Assigned Presale</option>
                        <option v-for="presale in props.presales" :key="presale.id" :value="presale.id">{{ presale.first_name }} {{ presale.last_name }}</option>
                    </select>
                    <InputError :message="form.errors.presale_assigned" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 8">
                    <TextInput v-model="form.site_survey_comments" type="text" class="mt-4 block w-full" placeholder="Site Survey Comments" />
                    <InputError :message="form.errors.site_survey_comments" class="mt-2" />

                    <TextInput v-model="form.site_survey_due_date" type="text" class="mt-4 block w-full" placeholder="Site Survey Due Date" />
                    <InputError :message="form.errors.site_survey_due_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 9">
                    <TextInput v-model="form.reason_for_overdue" type="text" class="mt-1 block w-full" placeholder="Reason  for Overdue" />
                    <InputError :message="form.errors.reason_for_overdue" class="mt-2" />

                    <TextInput v-model="form.overdue_next_steps" type="text" class="mt-4 block w-full" placeholder="Next Steps" />
                    <InputError :message="form.errors.overdue_next_steps" class="mt-2" />
                </div>

                <div class="mt-4">
                    <p>Contacts</p>
                    <InputError :message="form.errors.contact_information" class="mt-2" />
                </div>

                <div class="flex-1 ml-2 mt-3">
                    <input type="checkbox" id="salesPersonCheckbox" v-model="form.useaccountcontacts">
                    <label for="salesPersonCheckbox" class="pr-4 text-bg-dark"> Use Account's Contacts</label>
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
                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full" placeholder="Designation" />
                    </div>
                    <div class="flex-1 ml-2">
                        <PrimaryButton class="ms-3 bg-red-500" @click="removeContact(contact)">
                            <MdDelete />
                        </PrimaryButton>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <PrimaryButton class="ms-3" @click="addContacts">
                        Add contact
                    </PrimaryButton>
                </div>

                <div>
                <div class="mt-4">
                <p>Documents</p>
                <InputError :message="form.errors.documents" class="mt-2" />
                </div>

                <div v-for="(document, index) in form.documents" :key="document.id" class="mt-4 flex items-center">
                <div class="flex-1 mr-2">
                    <TextInput v-model="document.name" type="text" class="mt-1 block w-full" placeholder="Document Name" />
                </div>
                <div class="flex-1 ml-2">
                    <TextInput @change="handleFileChange($event.target.files[0], index)" type="file" class="mt-1 block w-full" placeholder="Upload File" />
                </div>

                <div class="flex-1 ml-2">
                    <PrimaryButton class="ms-3 bg-red-500" @click="removeDocument(document)">
                    <MdDelete />
                    </PrimaryButton>
                </div>
                </div>

                <div class="mt-4 flex justify-end">
                <PrimaryButton class="ms-3" @click="addDocuments">
                    Add Document
                </PrimaryButton>
                </div>
            </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeAddModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="save">
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>


        <DialogModal :show="updateStageModal" @close="closeUpdateStageModal">
            <template #title>
                Update Account Stage
            </template>
            <template #content>

                <div class="mt-4">
                    <label for="">Select Stage</label>
                    <select v-model="form.project_stage_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select stage</option>
                        <option v-for="projectstage in projectstages" :key="projectstage.id" :value="projectstage.id">{{ projectstage.name }}</option>
                    </select>
                    <InputError :message="form.errors.client_type_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 2">
                    <TextInput v-model="form.solution_name" type="text" class="mt-1 block w-full" placeholder="Business Name (How you Love it)" />
                    <InputError :message="form.errors.solution_name" class="mt-2" />
                </div>

                <!-- <TextInput v-model="form.account_id" value={{ slotProps.data.id }} /> -->


                <div class="mt-4" v-if="showSolutionCategoryX">
                    <label for="">Select Solution Category</label>
                    <select v-model="form.solution_type_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution Category</option>
                        <option v-for="solutiontype in props.solutiontypes" :key="solutiontype.id" :value="solutiontype.id">{{ solutiontype.solution_type_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_type_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showSolutionFieldX">
                    <label for="">Select Solution</label>
                    <select v-model="form.solution_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution</option>
                        <option v-for="solution in filteredSolutions" :key="solution.id" :value="solution.id">{{ solution.solution_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="showSolutionSubtypeFieldX">
                    <label for="">Select Solution Subtype</label>
                    <select v-model="form.solution_subtype_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Solution Subtype</option>
                        <option v-for="subtype in filteredSolutionSubtypes" :key="subtype.id" :value="subtype.id">{{ subtype.solution_sub_type_name }}</option>
                    </select>
                    <InputError :message="form.errors.solution_subtype_id" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 1">
                    <TextInput v-model="form.next_actions" type="text" class="mt-1 block w-full" placeholder="Next Actions" />
                    <InputError :message="form.errors.next_actions" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 3 ">
                    <TextInput v-model="form.expected_sale_value" type="text" class="mt-1 block w-full" placeholder="Expected Sale Value" />
                    <InputError :message="form.errors.expected_sale_value" class="mt-2" />

                    <TextInput v-model="form.projected_month_of_closure" type="text" class="mt-4 block w-full" placeholder="Projected Month of Closure" />
                    <InputError :message="form.errors.projected_month_of_closure" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 5">
                    <TextInput v-model="form.deal_amount" type="text" class="mt-1 block w-full" placeholder="Deal Amount" />
                    <InputError :message="form.errors.deal_amount" class="mt-2" />

                    <TextInput v-model="form.margins_projection" type="text" class="mt-4 block w-full" placeholder="Margins Projection" />
                    <InputError :message="form.errors.margins_projection" class="mt-2" />

                    <TextInput v-model="form.project_delivery_date" type="text" class="mt-4 block w-full" placeholder="Project Delivery Date" />
                    <InputError :message="form.errors.project_delivery_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 4">
                    <label for="">Select Probability of Closure</label>
                    <select v-model="form.probability_of_closure" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Probability of Closure</option>
                        <option value="Most Likely">Most Likely</option>
                        <option value="Likely">Likely</option>
                        <option value="Unlikely">Unlikely</option>
                    </select>
                    <InputError :message="form.errors.probability_of_closure" class="mt-2" />

                    <TextInput v-model="form.expected_month_of_closure" type="text" class="mt-4 block w-full" placeholder="Expected Month of Closure" />
                    <InputError :message="form.errors.expected_month_of_closure" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 6">
                    <TextInput v-model="form.reason_for_losing" type="text" class="mt-1 block w-full" placeholder="Reason  for Losing" />
                    <InputError :message="form.errors.reason_for_losing" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 7">
                    <label for="">Select Assigned Presale</label>
                    <select v-model="form.presale_assigned" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        <option value="" :disabled=true>Select Assigned Presale</option>
                        <option v-for="presale in props.presales" :key="presale.id" :value="presale.id">{{ presale.first_name }} {{ presale.last_name }}</option>
                    </select>
                    <InputError :message="form.errors.presale_assigned" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 8">
                    <TextInput v-model="form.site_survey_comments" type="text" class="mt-4 block w-full" placeholder="Site Survey Comments" />
                    <InputError :message="form.errors.site_survey_comments" class="mt-2" />

                    <TextInput v-model="form.site_survey_due_date" type="text" class="mt-4 block w-full" placeholder="Site Survey Due Date" />
                    <InputError :message="form.errors.site_survey_due_date" class="mt-2" />
                </div>

                <div class="mt-4" v-if="form.project_stage_id === 9">
                    <TextInput v-model="form.reason_for_overdue" type="text" class="mt-1 block w-full" placeholder="Reason  for Overdue" />
                    <InputError :message="form.errors.reason_for_overdue" class="mt-2" />

                    <TextInput v-model="form.overdue_next_steps" type="text" class="mt-4 block w-full" placeholder="Next Steps" />
                    <InputError :message="form.errors.overdue_next_steps" class="mt-2" />
                </div>



                <div>
                <div class="mt-4">
                <p>Documents</p>
                <InputError :message="form.errors.documents" class="mt-2" />
                </div>

                <div v-for="(document, index) in form.documents" :key="document.id" class="mt-4 flex items-center">
                <div class="flex-1 mr-2">
                    <TextInput v-model="document.name" type="text" class="mt-1 block w-full" placeholder="Document Name" />
                </div>
                <div class="flex-1 ml-2">
                    <TextInput @change="handleFileChange($event.target.files[0], index)" type="file" class="mt-1 block w-full" placeholder="Upload File" />
                </div>

                <div class="flex-1 ml-2">
                    <PrimaryButton class="ms-3 bg-red-500" @click="removeDocument(document)">
                    <MdDelete />
                    </PrimaryButton>
                </div>
                </div>

                <div class="mt-4 flex justify-end">
                <PrimaryButton class="ms-3" @click="addDocuments">
                    Add Document
                </PrimaryButton>
                </div>
            </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeUpdateStageModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click.prevent="updateDepartment()"
                    >
                        Update Stage
                    </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

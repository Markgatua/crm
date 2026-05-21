<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, watch } from "vue";
import { useForm, Link } from "@inertiajs/vue3";
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

const clients = ref(props.accounts ?? []);

const filteredClients = computed(() => {
    const keyword = filters.value.global ? filters.value.global.toLowerCase() : '';
    if (!keyword) {
        return clients.value;
    }
    return clients.value.filter(client => {
      return (
          (client.solution_name?.toLowerCase().includes(keyword)) ||
          (client.business_name?.toLowerCase().includes(keyword)) ||
          (client.latest_stage?.project_stage?.name?.toLowerCase().includes(keyword))
      );
    });
});

const editItem = ref(null);

const form = useForm({
    solution_name: '',
    client_type_id: -1,
    project_stage_id: -1,
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
    pushtopresales: false,
    presale_actions: '',
});

const editForm = useForm({
    id: -1,
    solution_name: '',
    business_name: '',
    contact_information: [],
    project_stage_id: -1,
    solution_type_id: null,
    solution_id: null,
    solution_sub_type_id: null,
    useaccountcontacts: false,
    account_id: '',
    latest_stage_id: '',
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
    pushtopresales: false,
    presale_actions: '',
});

const addingModal = ref(false);
const updateStageModal = ref(false);
const updateAccountModal = ref(false);

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
        tag: ""
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

const removeContact = (contact) => {
    form.contact_information = form.contact_information.filter(x => x.id != contact.id);
};

const removeEditContact = (contact) => {
    editForm.contact_information = editForm.contact_information.filter(x => x.id != contact.id);
};

const addDocuments = () => {
    form.documents.push({
        id: Math.random(),
        name: "",
        file: null
    });
};

const addDocuments2 = () => {
    editForm.documents.push({
        id: Math.random(),
        name: "",
        file: null
    });
};

const handleFileChange = (file, index) => {
    form.documents[index].file = file;
};

const handleFileChange2 = (file, index) => {
    editForm.documents[index].file = file;
};

const removeDocument = (document) => {
    form.documents = form.documents.filter(x => x.id != document.id);
};

const removeDocument2 = (document) => {
    editForm.documents = editForm.documents.filter(x => x.id != document.id);
};

const save = () => {
    form.post(route('sales_person.accounts.businesses.create'), {
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

const showSolutionCategory = computed(() => [2,3,4,5,7,8,10].includes(Number(form.project_stage_id)));
const showSolutionField = computed(() => form.solution_type_id !== null && [2,3,4,5,7,8,10].includes(Number(form.project_stage_id)));
const showSolutionSubtypeField = computed(() => form.solution_id !== null && [2,3,4,5,7,8,10].includes(Number(form.project_stage_id)) && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id));

const filteredSolutions = computed(() => props.solutions.filter(solution => solution.solution_type_id == form.solution_type_id));
const filteredSolutionSubtypes = computed(() => props.solutionsubtypes.filter(subtype => subtype.solution_id == form.solution_id));

const showSolutionCategoryX = computed(() => Number(form.project_stage_id) === 2);
const showSolutionFieldX = computed(() => form.solution_type_id !== null && Number(form.project_stage_id) === 2);
const showSolutionSubtypeFieldX = computed(() => form.solution_id !== null && Number(form.project_stage_id) === 2 && props.solutionsubtypes.some(subtype => subtype.solution_id == form.solution_id));

const showLicenseX = computed(() => Number(form.project_stage_id) === 10);
const showLicenseTypeX = computed(() => form.contract_license === 'Yes' && Number(form.project_stage_id) === 10);

const showSolutionCategoryEdit = computed(() => [2,3,4,5,7,8,10].includes(Number(editForm.project_stage_id)));
const showSolutionFieldEdit = computed(() => editForm.solution_type_id !== null && [2,3,4,5,7,8,10].includes(Number(editForm.project_stage_id)));
const showSolutionSubtypeFieldEdit = computed(() => editForm.solution_id !== null && [2,3,4,5,7,8,10].includes(Number(editForm.project_stage_id)) && props.solutionsubtypes.some(subtype => subtype.solution_id == editForm.solution_id));

const filteredSolutionsEdit = computed(() => props.solutions.filter(solution => solution.solution_type_id == editForm.solution_type_id));
const filteredSolutionSubtypesEdit = computed(() => props.solutionsubtypes.filter(subtype => subtype.solution_id == editForm.solution_id));

const showLicenseEdit = computed(() => Number(editForm.project_stage_id) === 10);
const showLicenseTypeEdit = computed(() => editForm.contract_license === 'Yes' && Number(editForm.project_stage_id) === 10);

watch(() => form.project_stage_id, (newStage) => {
    if ([1, 2, 3].includes(Number(newStage))) {
        form.next_actions = '';
        form.expected_month_of_closure = '';
        form.expected_sale_value = '';
    }
});

const updateStage = (item) => {
    updateStageModal.value = true;
    editItem.value = item;
    form.solution_name = item.solution_name;
    form.project_stage_id = item.project_stage_id;
    form.solution_type_id = item.solution_type_id;
    form.solution_id = item.solution_id;
    form.solution_subtype_id = item.solution_subtype_id;
    form.account_id = item.id;
    form.next_actions = item.next_actions ?? '';
    form.expected_sale_value = item.expected_sale_value ?? '';
    form.expected_month_of_closure = item.expected_month_of_closure ?? '';
    form.projected_month_of_closure = item.projected_month_of_closure ?? '';
    form.project_delivery_date = item.project_delivery_date ?? '';
    form.margins_projection = item.margins_projection ?? '';
    form.deal_amount = item.deal_amount ?? '';
    form.probability_of_closure = item.probability_of_closure ?? -1;
    form.reason_for_losing = item.reason_for_losing ?? '';
    form.reason_for_overdue = item.reason_for_overdue ?? '';
    form.overdue_next_steps = item.overdue_next_steps ?? '';
    form.presale_assigned = item.presale_assigned ?? -1;
    form.documents = [];
    form.site_survey_comments = item.site_survey_comments ?? '';
    form.site_survey_due_date = item.site_survey_due_date ?? '';
    form.contract_name = item.contract_name ?? '';
    form.contract_number = item.contract_number ?? '';
    form.contract_validity = item.contract_validity ?? '';
    form.contract_start_date = item.contract_start_date ?? '';
    form.contract_end_date = item.contract_end_date ?? '';
    form.contract_license = item.contract_license ?? '';
    form.contract_license_type = item.contract_license_type ?? '';
    form.contract_license_renewal_validity = item.contract_license_renewal_validity ?? '';
    form.contract_license_renewal_start_date = item.contract_license_renewal_start_date ?? '';
    form.contract_license_renewal_end_date = item.contract_license_renewal_end_date ?? '';
};

const updateDepartment = () => {
    form.post(route('sales_person.accounts.businesses.update_stage', {
        id: editItem.value.id,
        cid: editItem.value.client_id
    }), {
        preserveScroll: true,
        onSuccess: () => {
            closeUpdateStageModal();
            form.reset();
            location.reload();
        },
        onError: (e) => {
            toast.add(e);
        }
    });
};

const editClient = (client) => {
    updateAccountModal.value = true;
    editItem.value = client;
    editForm.id = client.id;
    editForm.business_name = client.business_name;
    editForm.client_type_id = client.client_type_id;
    editForm.solution_name = client.solution_name;
    editForm.project_stage_id = client.latest_stage?.project_stage_id ?? -1;
    editForm.solution_type_id = client.solution_type_id;
    editForm.solution_id = client.solution_id;
    editForm.solution_sub_type_id = client.solution_sub_type_id;
    
    try {
        editForm.contact_information = typeof client.contact_information === 'string' 
            ? JSON.parse(client.contact_information) 
            : (client.contact_information ?? []);
    } catch(e) {
        editForm.contact_information = [];
    }

    editForm.account_id = client.id;
    editForm.latest_stage_id = client.latest_stage?.id ?? '';

    let metaData = [];
    try {
        metaData = typeof client.latest_stage?.meta === 'string' 
            ? JSON.parse(client.latest_stage.meta) 
            : (client.latest_stage?.meta ?? []);
    } catch(e) {
        metaData = [];
    }

    function getMetaValue(key) {
        let found = (metaData || []).find(item => item && item.key === key);
        return found ? found.value : '';
    }

    editForm.expected_sale_value = getMetaValue("Expected Sale Value");
    editForm.expected_month_of_closure = getMetaValue("Estimated Month of Closure");
    editForm.projected_month_of_closure = getMetaValue("Projected Month Of Closure");
    editForm.project_delivery_date = getMetaValue("Project Delivery Date");
    editForm.margins_projection = getMetaValue("Margins Projection");
    editForm.deal_amount = getMetaValue("Deal Amount");
    editForm.probability_of_closure = getMetaValue("Closure Probability") || -1;
};

const updateAccount = () => {
    editForm.post(route('sales_person.accounts.businesses.update', { id: editForm.id }), {
        preserveScroll: true,
        onSuccess: () => {
            closeUpdateAccountModal();
            location.reload();
        }
    });
};

const closeUpdateStageModal = () => {
    updateStageModal.value = false;
    form.reset();
};

const closeUpdateAccountModal = () => {
    updateAccountModal.value = false;
    editForm.reset();
};

function safeParseJSON(str) {
    if (!str) return [];
    try {
        return typeof str === 'string' ? JSON.parse(str) : str;
    } catch(e) {
        return [];
    }
}

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
    <AppLayout :title="`${client.name} - Businesses`">
        <PageHeader :title="`${client.name} - Businesses`" name="Businesses" />

        <div class="space-y-4">
            <!-- Search & Actions Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" v-model="filters.global" placeholder="Search businesses by name, solution or stage..."
                        class="w-full pl-9.5 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary/15 focus:border-primary transition-all">
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-500 shadow-xs">
                        {{ filteredClients.length }} Business{{ filteredClients.length !== 1 ? 'es' : '' }}
                    </span>
                    <PrimaryButton @click="openAddModal">
                        <div class="flex items-center gap-1.5">
                            <Icon icon="material-symbols:add" class="h-4 w-4" />
                            Add Business
                        </div>
                    </PrimaryButton>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="overflow-x-auto thin-scrollbar">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                            <tr class="border-b border-gray-200/80 bg-gray-50/50 text-gray-500 uppercase font-bold tracking-wider">
                                <th class="px-6 py-3.5">Business & Solution Name</th>
                                <th class="px-6 py-3.5">Solution Category</th>
                                <th class="px-6 py-3.5">Contacts</th>
                                <th class="px-6 py-3.5">Current Stage Info</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="biz in filteredClients" :key="biz.id"
                                class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="`w-8 h-8 rounded-lg flex items-center justify-center text-white text-[10px] font-extrabold flex-shrink-0 shadow-sm ${avatarBg(biz.id)}`">
                                            {{ (biz.business_name?.[0] ?? '?').toUpperCase() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ biz.business_name }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold truncate max-w-[200px] mt-0.5">{{ biz.solution_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="biz.solution_type_name" class="badge badge-primary font-bold">
                                        {{ biz.solution_type_name }}
                                    </span>
                                    <span v-else class="text-gray-400">—</span>
                                    <p class="text-[9px] text-gray-400 font-semibold truncate max-w-[150px] mt-1">{{ biz.solution_sub_type_name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1 max-w-[200px]">
                                        <div v-for="(contact, index) in safeParseJSON(biz.contact_information)" :key="index"
                                             class="p-1 rounded bg-gray-50 border border-gray-100/60 text-[10px]">
                                            <span class="font-bold text-gray-800">{{ contact.name }}</span>
                                            <span class="text-gray-400 mx-1">|</span>
                                            <span class="text-gray-500 font-semibold">{{ contact.designation }}</span>
                                            <p class="text-[9px] text-gray-400 font-medium mt-0.5">{{ contact.phone || contact.email }}</p>
                                        </div>
                                        <div v-if="safeParseJSON(biz.contact_information).length === 0" class="text-gray-400 italic">No contacts</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="biz.latest_stage" class="min-w-[160px] space-y-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-extrabold">
                                            {{ biz.latest_stage.project_stage?.name }}
                                        </span>
                                        <p class="text-[10px] text-gray-400 font-semibold truncate max-w-[150px]" :title="biz.latest_stage.stage_information">
                                            {{ biz.latest_stage.stage_information || 'No notes' }}
                                        </p>
                                        
                                        <!-- Custom nested dropdown details -->
                                        <details class="text-[10px] text-gray-500">
                                            <summary class="cursor-pointer font-bold text-primary hover:underline outline-none">Metrics</summary>
                                            <div class="mt-1.5 p-2 rounded bg-gray-50 border border-gray-200/60 space-y-1">
                                                <div v-for="(item, idx) in safeParseJSON(biz.latest_stage.meta)" :key="idx">
                                                    <span v-if="item.key !== 'Documents'" class="block">
                                                        <strong class="text-gray-700 font-bold uppercase tracking-wider text-[8px] block">{{ item.key.replace(/_/g, ' ') }}:</strong>
                                                        <span class="font-semibold">{{ item.value }}</span>
                                                    </span>
                                                    <span v-else class="block">
                                                        <strong class="text-gray-700 font-bold uppercase tracking-wider text-[8px] block">Documents:</strong>
                                                        <span v-for="(doc, dIdx) in item.value" :key="dIdx" class="inline-flex gap-1.5 mt-0.5">
                                                            <a :href="doc.value" target="_blank" class="text-primary font-bold hover:underline">{{ doc.key }}</a>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </details>
                                    </div>
                                    <span v-else class="text-gray-400">—</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="route('sales_person.accounts.account', { id: biz.id })">
                                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-light border border-primary/10 rounded-lg text-[10px] font-bold text-primary hover:bg-primary hover:text-white transition-all duration-200">
                                                View
                                            </button>
                                        </Link>
                                        <button @click="updateStage(biz)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 border border-emerald-200/50 rounded-lg text-[10px] font-bold text-emerald-700 hover:bg-emerald hover:text-white transition-all duration-200">
                                            Update Stage
                                        </button>
                                        <button @click="editClient(biz)"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200/50 rounded-lg text-[10px] font-bold text-amber-700 hover:bg-amber hover:text-white transition-all duration-200">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredClients.length === 0">
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <p class="text-3xl mb-3">🤝</p>
                                        <p class="text-gray-900 font-bold text-sm">No Businesses Found</p>
                                        <p class="text-gray-400 text-xs mt-1 font-medium">Try matching other keywords.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Business Modal -->
        <DialogModal :show="addingModal" @close="closeAddModal">
            <template #title>Add Business to {{ client.name }} Account</template>
            <template #content>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Business Type" />
                            <select v-model="form.client_type_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="-1" disabled>Select Business Type</option>
                                <option v-for="clienttype in clienttypes" :key="clienttype.id" :value="clienttype.id">
                                    {{ clienttype.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.client_type_id" class="mt-1" />
                        </div>

                        <div>
                            <InputLabel value="Select Stage" />
                            <select v-model="form.project_stage_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="-1" disabled>Select stage</option>
                                <option v-for="projectstage in projectstages" :key="projectstage.id" :value="projectstage.id">
                                    {{ projectstage.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.project_stage_id" class="mt-1" />
                        </div>
                    </div>

                    <div v-if="[2,3,4,5,6,7,8,9,10].includes(Number(form.project_stage_id))">
                        <InputLabel value="Business Solution Title" />
                        <TextInput v-model="form.solution_name" type="text" class="mt-1.5 block w-full text-xs" placeholder="e.g. Enterprise Cloud Deployment" />
                        <InputError :message="form.errors.solution_name" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-if="showSolutionCategory">
                            <InputLabel value="Solution Category" />
                            <select v-model="form.solution_type_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="null">Select Solution Category</option>
                                <option v-for="solutiontype in props.solutiontypes" :key="solutiontype.id" :value="solutiontype.id">
                                    {{ solutiontype.solution_type_name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.solution_type_id" class="mt-1" />
                        </div>

                        <div v-if="showSolutionField">
                            <InputLabel value="Select Solution" />
                            <select v-model="form.solution_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="null">Select Solution</option>
                                <option v-for="solution in filteredSolutions" :key="solution.id" :value="solution.id">
                                    {{ solution.solution_name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.solution_id" class="mt-1" />
                        </div>

                        <div v-if="showSolutionSubtypeField">
                            <InputLabel value="Solution Subtype" />
                            <select v-model="form.solution_subtype_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option :value="null">Select Subtype</option>
                                <option v-for="subtype in filteredSolutionSubtypes" :key="subtype.id" :value="subtype.id">
                                    {{ subtype.solution_sub_type_name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.solution_subtype_id" class="mt-1" />
                        </div>
                    </div>

                    <!-- Stage Dependent Fields -->
                    <div v-if="Number(form.project_stage_id) === 2" class="flex items-center gap-2 mt-2">
                        <input type="checkbox" id="pushToPresalesCheckbox" v-model="form.pushtopresales" class="rounded border-gray-300 text-primary focus:ring-primary/10">
                        <label for="pushToPresalesCheckbox" class="text-xs font-bold text-gray-700">Push To Presales</label>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 2 && form.pushtopresales" class="mt-3">
                        <InputLabel value="Pre-Sales Instructions" />
                        <TextInput v-model="form.presale_actions" type="text" class="mt-1.5 block w-full text-xs" placeholder="What should Pre-Sales scoping do?" />
                        <InputError :message="form.errors.presale_actions" class="mt-1" />
                    </div>

                    <div v-if="Number(form.project_stage_id) === 1">
                        <InputLabel value="Next Actions" />
                        <TextInput v-model="form.next_actions" type="text" class="mt-1.5 block w-full text-xs" placeholder="Next actionable deal step" />
                        <InputError :message="form.errors.next_actions" class="mt-1" />
                    </div>

                    <!-- Contract Details -->
                    <div v-if="Number(form.project_stage_id) === 10" class="space-y-3 p-4 rounded-xl border border-gray-200 bg-gray-50/50">
                        <h4 class="font-bold text-gray-800 text-xs">Contract Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <InputLabel value="Contract Name" />
                                <TextInput v-model="form.contract_name" type="text" class="mt-1 block w-full text-xs" />
                            </div>
                            <div>
                                <InputLabel value="Contract Number" />
                                <TextInput v-model="form.contract_number" type="text" class="mt-1 block w-full text-xs" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <InputLabel value="Validity (Months)" />
                                <TextInput v-model="form.contract_validity" type="text" class="mt-1 block w-full text-xs" />
                            </div>
                            <div>
                                <InputLabel value="Start Date" />
                                <TextInput v-model="form.contract_start_date" type="text" class="mt-1 block w-full text-xs" placeholder="dd/mm/yyyy" />
                            </div>
                            <div>
                                <InputLabel value="End Date" />
                                <TextInput v-model="form.contract_end_date" type="text" class="mt-1 block w-full text-xs" placeholder="dd/mm/yyyy" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <InputLabel value="Contract License" />
                                <select v-model="form.contract_license" class="w-full mt-1 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2">
                                    <option value="None">None</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                            <div v-if="showLicenseTypeX">
                                <InputLabel value="License Type" />
                                <select v-model="form.contract_license_type" class="w-full mt-1 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2">
                                    <option value="Perpetual">Perpetual</option>
                                    <option value="Renewable">Renewable</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="form.contract_license_type === 'Renewable'" class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                            <div>
                                <InputLabel value="Renewal Validity (Months)" />
                                <TextInput v-model="form.contract_license_renewal_validity" type="text" class="mt-1 block w-full text-xs" />
                            </div>
                            <div>
                                <InputLabel value="Renewal Start" />
                                <TextInput v-model="form.contract_license_renewal_start_date" type="text" class="mt-1 block w-full text-xs" placeholder="dd/mm/yyyy" />
                            </div>
                            <div>
                                <InputLabel value="Renewal End" />
                                <TextInput v-model="form.contract_license_renewal_end_date" type="text" class="mt-1 block w-full text-xs" placeholder="dd/mm/yyyy" />
                            </div>
                        </div>
                    </div>

                    <!-- Evaluation details expected month -->
                    <div v-if="[3, 4].includes(Number(form.project_stage_id))" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Expected Sale Value (Ksh)" />
                            <TextInput v-model="form.expected_sale_value" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Projected Month of Closure" />
                            <TextInput v-model="form.projected_month_of_closure" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Closures probability expected closures -->
                    <div v-if="Number(form.project_stage_id) === 4" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Probability of Closure" />
                            <select v-model="form.probability_of_closure" class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5">
                                <option :value="-1" disabled>Select probability</option>
                                <option value="Most Likely">Most Likely</option>
                                <option value="Likely">Likely</option>
                                <option value="Unlikely">Unlikely</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Expected Month of Closure" />
                            <TextInput v-model="form.expected_month_of_closure" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Closed Won metrics -->
                    <div v-if="Number(form.project_stage_id) === 5" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel value="Deal Amount (Ksh)" />
                            <TextInput v-model="form.deal_amount" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Margins Projection (Ksh)" />
                            <TextInput v-model="form.margins_projection" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Project Delivery Date" />
                            <TextInput v-model="form.project_delivery_date" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 6">
                        <InputLabel value="Reason for Losing" />
                        <TextInput v-model="form.reason_for_losing" type="text" class="mt-1.5 block w-full text-xs" />
                    </div>

                    <div v-if="Number(form.project_stage_id) === 7">
                        <InputLabel value="Select Assigned Presale" />
                        <select v-model="form.presale_assigned" class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5">
                            <option :value="-1" disabled>Select presale executive</option>
                            <option v-for="presale in props.presales" :key="presale.id" :value="presale.id">
                                {{ presale.first_name }} {{ presale.last_name }}
                            </option>
                        </select>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 8" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Site Survey Comments" />
                            <TextInput v-model="form.site_survey_comments" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Site Survey Due Date" />
                            <TextInput v-model="form.site_survey_due_date" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 9" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Reason for Overdue" />
                            <TextInput v-model="form.reason_for_overdue" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Next Remedial Steps" />
                            <TextInput v-model="form.overdue_next_steps" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Contact Details Selection checkbox -->
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" id="salesPersonCheckbox" v-model="form.useaccountcontacts" class="rounded border-gray-300 text-primary focus:ring-primary/10">
                        <label for="salesPersonCheckbox" class="text-xs font-bold text-gray-700">Use Account's Contacts</label>
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

                        <div class="space-y-3 max-h-[160px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(contact, index) in form.contact_information" :key="contact.id" 
                                 class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-3 relative group">
                                <button type="button" @click="removeContact(contact)"
                                        class="absolute top-2.5 right-2.5 text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <InputLabel value="Contact Name" />
                                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full text-xs" />
                                    </div>
                                    <div>
                                        <InputLabel value="Designation" />
                                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full text-xs" />
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
                                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full text-xs" />
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Email" />
                                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full text-xs" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents upload list -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900 text-xs">Documents ({{ form.documents.length }})</h4>
                            <SecondaryButton @click="addDocuments" type="button" class="!py-1.5 !px-3">
                                <div class="flex items-center gap-1">
                                    <Icon icon="material-symbols:add" class="h-4 w-4" />
                                    Add Document
                                </div>
                            </SecondaryButton>
                        </div>

                        <div class="space-y-3 max-h-[160px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(doc, index) in form.documents" :key="doc.id"
                                 class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between gap-3 relative">
                                <div class="flex-1">
                                    <TextInput v-model="doc.name" type="text" class="w-full text-xs" placeholder="e.g. Scoping Doc" />
                                </div>
                                <div class="flex-1">
                                    <input @change="handleFileChange($event.target.files[0], index)" type="file" class="text-xs w-full" />
                                </div>
                                <button type="button" @click="removeDocument(doc)"
                                        class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <SecondaryButton @click="closeAddModal">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="save">
                        Add Business
                    </PrimaryButton>
                </div>
            </template>
        </DialogModal>

        <!-- Update Stage Modal -->
        <DialogModal :show="updateStageModal" @close="closeUpdateStageModal">
            <template #title>Update Stage for Business Account</template>
            <template #content>
                <div class="space-y-4">
                    <div>
                        <InputLabel value="Select Next Stage" />
                        <select v-model="form.project_stage_id"
                            class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                            <option :value="-1" disabled>Select stage</option>
                            <option v-for="projectstage in projectstages" :key="projectstage.id" :value="projectstage.id">
                                {{ projectstage.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.project_stage_id" class="mt-1" />
                    </div>

                    <!-- Push to Presales -->
                    <div v-if="Number(form.project_stage_id) === 2" class="flex items-center gap-2 mt-2">
                        <input type="checkbox" id="pushToPresalesStageCheckbox" v-model="form.pushtopresales" class="rounded border-gray-300 text-primary focus:ring-primary/10">
                        <label for="pushToPresalesStageCheckbox" class="text-xs font-bold text-gray-700">Push To Presales</label>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 2 && form.pushtopresales" class="mt-3">
                        <InputLabel value="Pre-Sales Scoping Instructions" />
                        <TextInput v-model="form.presale_actions" type="text" class="mt-1.5 block w-full text-xs" placeholder="Site instructions..." />
                        <InputError :message="form.errors.presale_actions" class="mt-1" />
                    </div>

                    <div v-if="Number(form.project_stage_id) === 1">
                        <InputLabel value="Next Actions" />
                        <TextInput v-model="form.next_actions" type="text" class="mt-1.5 block w-full text-xs" />
                    </div>

                    <div v-if="[3, 4].includes(Number(form.project_stage_id))" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Expected Sale Value" />
                            <TextInput v-model="form.expected_sale_value" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Projected Closure Month" />
                            <TextInput v-model="form.projected_month_of_closure" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 4" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-1">
                            <InputLabel value="Probability" />
                            <select v-model="form.probability_of_closure" class="w-full mt-1 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2">
                                <option value="Most Likely">Most Likely</option>
                                <option value="Likely">Likely</option>
                                <option value="Unlikely">Unlikely</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel value="Expected Closure Month" />
                            <TextInput v-model="form.expected_month_of_closure" type="text" class="mt-1 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 5" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel value="Deal Amount" />
                            <TextInput v-model="form.deal_amount" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Margins Projection" />
                            <TextInput v-model="form.margins_projection" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Project Delivery Date" />
                            <TextInput v-model="form.project_delivery_date" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 6">
                        <InputLabel value="Reason for Losing Deal" />
                        <TextInput v-model="form.reason_for_losing" type="text" class="mt-1.5 block w-full text-xs" />
                    </div>

                    <div v-if="Number(form.project_stage_id) === 7">
                        <InputLabel value="Assigned Presale Officer" />
                        <select v-model="form.presale_assigned" class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5">
                            <option v-for="presale in props.presales" :key="presale.id" :value="presale.id">
                                {{ presale.first_name }} {{ presale.last_name }}
                            </option>
                        </select>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 8" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Site Survey Comments" />
                            <TextInput v-model="form.site_survey_comments" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Site Survey Date" />
                            <TextInput v-model="form.site_survey_due_date" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(form.project_stage_id) === 9" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Reason for Overdue" />
                            <TextInput v-model="form.reason_for_overdue" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Remedial Actions" />
                            <TextInput v-model="form.overdue_next_steps" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Documents upload list -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900 text-xs">Stage Attachments ({{ form.documents.length }})</h4>
                            <SecondaryButton @click="addDocuments" type="button" class="!py-1.5 !px-3">
                                <div class="flex items-center gap-1">
                                    <Icon icon="material-symbols:add" class="h-4 w-4" />
                                    Add Document
                                </div>
                            </SecondaryButton>
                        </div>

                        <div class="space-y-3 max-h-[160px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(doc, index) in form.documents" :key="doc.id"
                                 class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between gap-3 relative">
                                <div class="flex-1">
                                    <TextInput v-model="doc.name" type="text" class="w-full text-xs" placeholder="Document Name" />
                                </div>
                                <div class="flex-1">
                                    <input @change="handleFileChange($event.target.files[0], index)" type="file" class="text-xs w-full" />
                                </div>
                                <button type="button" @click="removeDocument(doc)"
                                        class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <SecondaryButton @click="closeUpdateStageModal">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.prevent="updateDepartment">
                        Update Stage
                    </PrimaryButton>
                </div>
            </template>
        </DialogModal>

        <!-- Edit Account Modal -->
        <DialogModal :show="updateAccountModal" @close="closeUpdateAccountModal">
            <template #title>Edit Business Meta Info</template>
            <template #content>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Business Type" />
                            <select v-model="editForm.client_type_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option v-for="clienttype in clienttypes" :key="clienttype.id" :value="clienttype.id">
                                    {{ clienttype.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <InputLabel value="Current Stage" />
                            <select v-model="editForm.project_stage_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option v-for="projectstage in projectstages" :key="projectstage.id" :value="projectstage.id">
                                    {{ projectstage.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <InputLabel value="Business Solution Title" />
                        <TextInput v-model="editForm.business_name" type="text" class="mt-1.5 block w-full text-xs" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div v-if="showSolutionCategoryEdit">
                            <InputLabel value="Solution Category" />
                            <select v-model="editForm.solution_type_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option v-for="solutiontype in props.solutiontypes" :key="solutiontype.id" :value="solutiontype.id">
                                    {{ solutiontype.solution_type_name }}
                                </option>
                            </select>
                        </div>

                        <div v-if="showSolutionFieldEdit">
                            <InputLabel value="Select Solution" />
                            <select v-model="editForm.solution_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option v-for="solution in props.solutions" :key="solution.id" :value="solution.id">
                                    {{ solution.solution_name }}
                                </option>
                            </select>
                        </div>

                        <div v-if="showSolutionSubtypeFieldEdit">
                            <InputLabel value="Solution Subtype" />
                            <select v-model="editForm.solution_sub_type_id"
                                class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                                <option v-for="subtype in props.solutionsubtypes" :key="subtype.id" :value="subtype.id">
                                    {{ subtype.solution_sub_type_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Evaluation details Expected Sale value -->
                    <div v-if="[3, 4].includes(Number(editForm.project_stage_id))" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Expected Sale Value" />
                            <TextInput v-model="editForm.expected_sale_value" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Projected Closure Month" />
                            <TextInput v-model="editForm.projected_month_of_closure" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Probability expected closures -->
                    <div v-if="Number(editForm.project_stage_id) === 4" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Probability of Closure" />
                            <select v-model="editForm.probability_of_closure" class="w-full mt-1.5 border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5">
                                <option value="Most Likely">Most Likely</option>
                                <option value="Likely">Likely</option>
                                <option value="Unlikely">Unlikely</option>
                            </select>
                        </div>
                        <div>
                            <InputLabel value="Expected Month of Closure" />
                            <TextInput v-model="editForm.expected_month_of_closure" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <!-- Closed Won metrics -->
                    <div v-if="Number(editForm.project_stage_id) === 5" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <InputLabel value="Deal Amount" />
                            <TextInput v-model="editForm.deal_amount" type="number" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Margins Projection" />
                            <TextInput v-model="editForm.margins_projection" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                        <div>
                            <InputLabel value="Project Delivery Date" />
                            <TextInput v-model="editForm.project_delivery_date" type="text" class="mt-1.5 block w-full text-xs" />
                        </div>
                    </div>

                    <div v-if="Number(editForm.project_stage_id) === 6">
                        <InputLabel value="Reason for Losing" />
                        <TextInput v-model="editForm.reason_for_losing" type="text" class="mt-1.5 block w-full text-xs" />
                    </div>

                    <!-- Contact Details lists edit -->
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

                        <div class="space-y-3 max-h-[160px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(contact, index) in editForm.contact_information" :key="contact.id" 
                                 class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-3 relative group">
                                <button type="button" @click="removeEditContact(contact)"
                                        class="absolute top-2.5 right-2.5 text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <InputLabel value="Contact Name" />
                                        <TextInput v-model="contact.name" type="text" class="mt-1 block w-full text-xs" />
                                    </div>
                                    <div>
                                        <InputLabel value="Designation" />
                                        <TextInput v-model="contact.designation" type="text" class="mt-1 block w-full text-xs" />
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
                                        <TextInput v-model="contact.phone" type="text" class="mt-1 block w-full text-xs" />
                                    </div>
                                    <div class="md:col-span-1">
                                        <InputLabel value="Email" />
                                        <TextInput v-model="contact.email" type="email" class="mt-1 block w-full text-xs" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents upload list edit -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-900 text-xs">Documents ({{ editForm.documents.length }})</h4>
                            <SecondaryButton @click="addDocuments2" type="button" class="!py-1.5 !px-3">
                                <div class="flex items-center gap-1">
                                    <Icon icon="material-symbols:add" class="h-4 w-4" />
                                    Add Document
                                </div>
                            </SecondaryButton>
                        </div>

                        <div class="space-y-3 max-h-[160px] overflow-y-auto thin-scrollbar pr-1">
                            <div v-for="(doc, index) in editForm.documents" :key="doc.id"
                                 class="p-3 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-between gap-3 relative">
                                <div class="flex-1">
                                    <TextInput v-model="doc.name" type="text" class="w-full text-xs" placeholder="Document Name" />
                                </div>
                                <div class="flex-1">
                                    <input @change="handleFileChange2($event.target.files[0], index)" type="file" class="text-xs w-full" />
                                </div>
                                <button type="button" @click="removeDocument2(doc)"
                                        class="text-gray-400 hover:text-red-600 transition-colors p-1 rounded-lg hover:bg-red-50">
                                    <Icon icon="material-symbols:delete-outline" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end gap-2">
                    <SecondaryButton @click="closeUpdateAccountModal">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': editForm.processing }" :disabled="editForm.processing" @click.prevent="updateAccount">
                        Save Changes
                    </PrimaryButton>
                </div>
            </template>
        </DialogModal>
    </AppLayout>
</template>

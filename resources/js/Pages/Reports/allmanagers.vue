<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, toRefs, ref, watchEffect } from 'vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Icon } from "@iconify/vue";
import * as XLSX from "xlsx";


const props = defineProps({
    accounts: Array,
    totals: Object,
    counts: Object,
    closedaccountsdata: Object,
    evaluationaccountsdata: Object,
    approvalaccountsdata: Object,
    prospectaccountsdata: Object,
    scopingaccountsdata: Object,
    lostaccountsdata: Object,
    overdueaccountsdata: Object,
    projectsaccountsdata: Object,
    startdate: Object,
    enddate: Object,
});

const { closedaccountsdata } = toRefs(props);
const { evaluationaccountsdata } = toRefs(props);
const { approvalaccountsdata } = toRefs(props);
const { prospectaccountsdata } = toRefs(props);
const { scopingaccountsdata } = toRefs(props);
const { lostaccountsdata } = toRefs(props);
const { overdueaccountsdata } = toRefs(props);
const { projectsaccountsdata } = toRefs(props);


const closedUserIds = ref([]);
const evaluationUserIds = ref([]);
const approvalUserIds = ref([]);
const prospectUserIds = ref([]);
const scopingUserIds = ref([]);
const lostUserIds = ref([]);
const overdueUserIds = ref([]);
const projectsUserIds = ref([]);

const closedAccountData = ref([]);
const evaluationAccountData = ref([]);
const approvalAccountData = ref([]);
const prospectAccountData = ref([]);
const scopingAccountData = ref([]);
const lostAccountData = ref([]);
const overdueAccountData = ref([]);
const projectsAccountData = ref([]);


const closedData = ref([]);
const evaluationData = ref([]);
const approvalData = ref([]);
const prospectData = ref([]);
const scopingData = ref([]);
const lostData = ref([]);
const overdueData = ref([]);


for (const data of closedaccountsdata.value) {
    if (closedUserIds.value.findIndex(item => item === data.user_id) === -1) {
        closedUserIds.value.push(data.user_id);
    }
}

for (const data of closedaccountsdata.value) {
    closedAccountData.value.push(data);
}

for (const data of evaluationaccountsdata.value) {
    evaluationAccountData.value.push(data);
    if (evaluationUserIds.value.findIndex(item => item === data.user_id) === -1) {
        evaluationUserIds.value.push(data.user_id);
    }
}

for (const data of approvalaccountsdata.value) {
    approvalAccountData.value.push(data);
    if (approvalUserIds.value.findIndex(item => item === data.user_id) === -1) {
        approvalUserIds.value.push(data.user_id);
    }
}

for (const data of prospectaccountsdata.value) {
    prospectAccountData.value.push(data);
    if (prospectUserIds.value.findIndex(item => item === data.user_id) === -1) {
        prospectUserIds.value.push(data.user_id);
    }
}

for (const data of scopingaccountsdata.value) {
    scopingAccountData.value.push(data);
    if (scopingUserIds.value.findIndex(item => item === data.user_id) === -1) {
        scopingUserIds.value.push(data.user_id);
    }
}

for (const data of lostaccountsdata.value) {
    lostAccountData.value.push(data);
    if (lostUserIds.value.findIndex(item => item === data.user_id) === -1) {
        lostUserIds.value.push(data.user_id);
    }
}

for (const data of overdueaccountsdata.value) {
    overdueAccountData.value.push(data);
    if (overdueUserIds.value.findIndex(item => item === data.user_id) === -1) {
        overdueUserIds.value.push(data.user_id);
    }
}

for (const data of projectsaccountsdata.value) {
    projectsAccountData.value.push(data);
    if (projectsUserIds.value.findIndex(item => item === data.user_id) === -1) {
        projectsUserIds.value.push(data.user_id);
    }
}

watchEffect(() => {
    for (const data of closedAccountData.value) {
        if(data.meta.find(item => item.key === 'Deal Amount')) {
            closedData.value.push(Number.parseFloat((data.meta).find(item => item.key === 'Deal Amount').value.split(",").join("")));
        }
    }
})

watchEffect(() => {
    for (const data of evaluationAccountData.value) {
        if((data.meta).find(item => item.key === 'Expected Sale Value')) {
            evaluationData.value.push(Number.parseFloat((data.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")));
        }
    }
})

watchEffect(() => {
    for (const data of approvalAccountData.value) {
        if((data.meta).find(item => item.key === 'Expected Sale Value')) {
            approvalData.value.push(Number.parseFloat((data.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")));
        }
    }
})


function formatClients(clients) {
    if (!clients) {
        return '';
    }
    return clients.replace(/\|\|/g, '<br>');
}

function formatDate(date){
    const options = { year: 'numeric', month: 'long', day: '2-digit' };
      return new Date(date).toLocaleDateString('en-US', options);
}


function printDiv() {
    const divToPrint = document.querySelector('.overflow-x-auto').innerHTML;
    const newWindow = window.open('', '', 'height=600,width=800');
    newWindow.document.write('<html><head><title>Xtranet Accounts</title>');
    newWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>'); // You can adjust or add styles here
    newWindow.document.write('</head><body >');
    newWindow.document.write(divToPrint);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
}

function exportToExcel() {
    const workbook = XLSX.utils.book_new();

    // Export accounts
    if (props.accounts) {
        const accountsWorksheet = XLSX.utils.json_to_sheet(props.accounts);
        XLSX.utils.book_append_sheet(workbook, accountsWorksheet, "Accounts");
    }

    // Export totals
    if (props.totals) {
        const totalsWorksheet = XLSX.utils.json_to_sheet([props.totals]);
        XLSX.utils.book_append_sheet(workbook, totalsWorksheet, "Totals");
    }

    // Export counts
    if (props.counts) {
        const countsWorksheet = XLSX.utils.json_to_sheet([props.counts]);
        XLSX.utils.book_append_sheet(workbook, countsWorksheet, "Counts");
    }

    // Export individual account data
    const accountTypes = [
        { type: 'closed', label: 'Closed' },
        { type: 'evaluation', label: 'Evaluation' },
        { type: 'approval', label: 'Approval' },
        { type: 'prospect', label: 'Prospect' },
        { type: 'scoping', label: 'Scoping' },
        { type: 'lost', label: 'Lost' },
        { type: 'overdue', label: 'Overdue' },
        { type: 'projects', label: 'Projects' }
    ];

    for (const { type, label } of accountTypes) {
        const data = props[`${type}accountsdata`];
        if (data && data.length > 0) {
            const worksheet = XLSX.utils.json_to_sheet(data);
            XLSX.utils.book_append_sheet(workbook, worksheet, `${label} Accounts`);
        }
    }

    // Export start and end dates
    if (props.startdate && props.enddate) {
        const dateWorksheet = XLSX.utils.json_to_sheet([
            { 'Start Date': formatDate(props.startdate), 'End Date': formatDate(props.enddate) }
        ]);
        XLSX.utils.book_append_sheet(workbook, dateWorksheet, "Date Range");
    }

    // Write the workbook to a file
    XLSX.writeFile(workbook, "accounts_data.xlsx");
}

</script>

<style scoped>
.truncate {
    white-space: pre-wrap; /* Preserve white spaces and line breaks */
    overflow-wrap: break-word; /* Allow breaking long words */
}
</style>

<template>
    <AppLayout title="Accounts Sheet">
        <PageHeader title="Accounts Sheet" name="Accounts Sheet" />

        <section class="bg-gray-50">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <!-- Add Print Button here -->
                        <SecondaryButton @click="printDiv" class="inline-flex gap-2"><Icon icon="material-symbols-light:print-outline" class="h-6 w-6" />Print</SecondaryButton>
                    </div>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <SecondaryButton @click="exportToExcel" class="inline-flex gap-2"><Icon icon="vscode-icons:file-type-excel" class="h-6 w-6" />Excel</SecondaryButton>

                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10">
                        <h1 class="px-4 py-3 uppercase text-black font-bold">
                         REPORT FOR PERIOD <span class="text-blue-600">{{ formatDate(startdate) }}</span> TO <span class="text-blue-600">{{ formatDate(enddate) }}</span>
                        </h1>
                    </div>
                    <h2 class="px-3">SUMMARY TABLE</h2>
                    <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                        <thead class="text-xs text-gray-700 text-center uppercase bg-gray-50">
                            <tr>
                                <th class="px-3 py-1">Account Manager</th>
                                <!-- <th class="px-3 py-1">Total Accounts </th> -->
                                <th class="px-3 py-1">Prospects Accounts</th>
                                <th class="px-3 py-1">Scoping Accounts</th>
                                <th class="px-3 py-1">Evaluation Accounts</th>
                                <th class="px-3 py-1">Approval Accounts</th>
                                <th class="px-3 py-1">Closed Accounts</th>
                                <th class="px-3 py-1">Lost Accounts</th>
                                <th class="px-3 py-1">Presales Accounts</th>
                                <th class="px-3 py-1">Projects Accounts</th>
                                <th class="px-3 py-1">Overdue Accounts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in counts" :key="item.user_id">
                                <td class="px-6 font-medium text-gray-900">{{ item.first_name }} {{ item.last_name }}</td>
                                <!-- <td class="px-6 py-4">{{ item.accounts_count }}</td> -->
                                <td>{{ item.prospects_count }}</td>
                                <td>{{ item.scooping_count }}</td>
                                <td>{{ item.evaluation_count }}</td>
                                <td>{{ item.approval_count }}</td>
                                <td>{{ item.closed_count }}</td>
                                <td>{{ item.lost_count }}</td>
                                <td>{{ item.presales_count }}</td>
                                <td>{{ item.projects_count }}</td>
                                <td>{{ item.overdue_count }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- <div class="" v-for="(section, key) in accounts" :key="key">
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8 pb-7">
                            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">{{ key }}</h1>
      <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th>Accounts</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in section" :key="item.user_id">
                <td>
                    <table>
                        <thead>
                            <th>#</th>
                            <th>Account Name</th>
                            <th>Meta</th>
                        </thead>
                        <tbody>
                            <tr v-if="item.business_names" v-for="(name, index) in item.business_names.split('||')" :key="index">
                                <td>{{ index+1 }}</td>
                                <td>{{ name }}</td>

                                <td class="px-2 py-2 border-b border-gray-300">
                                    <div v-if="item.metas" v-for="i in JSON.parse(item.metas)[index]">
                                        <div v-if="i" v-for="newItem in i">
                                            <p>
                                                <p class="px-2">
                                                    <span class="font-bold">{{ newItem["key"] }}</span>
                                                    :
                                                    <span v-if="typeof newItem['value'] === 'object'" v-for="document in newItem['value']">
                                                        <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                    </span>
                                                     <span v-else>{{ newItem["value"].toLocaleString() }}</span>
                                                </p>
                                                <hr/>
                                            </p>
                                        </div>
                                    </div>
                                </td>


                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
                            </div>
                        </div>
                    </div> -->


                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Prospects Deals</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in prospectUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ prospectaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ prospectaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}

                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Client</th>
                                    <th>Meta</th>
                                    <th>Contact Information</th>
                                    <th>Date Created</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in prospectaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td> {{ account.clientname}}<br>{{ account.clientemail }} <br> {{ account.clientlocation }}<br>{{ account.clientwebsiteurl }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                   
                                                </p>
                                                 <hr/>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.mainclientcontactinformation" v-for="i in (account.mainclientcontactinformation)">
                                                <div v-if="i">
                                                    <p>{{ i.name !== null || i.name !== undefined || i.name !== '' ? i.name : '' }}</p>
                                                    <p>{{ i.email !== null || i.email !== undefined || i.email !== '' ? i.email : '' }}</p>
                                                    <p>{{ i.phone !== null || i.phone !== undefined || i.phone !== '' ? i.phone : '' }}</p>
                                                    <p>{{ i.designation !== null || i.designation !== undefined || i.designation !== '' ? i.designation : '' }}</p>
                                                </div>
                                        </div>
                                    </td>

                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Scoping Deals</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in scopingUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ scopingaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ scopingaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}

                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Meta</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in scopingaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                   
                                                </p>
                                                 <hr/>
                                        </div>
                                    </td>

                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Evaluation Deals</h1>
                            <h1 class="pl-6 uppercase text-black font-bold">Total Amount (KSH {{evaluationData.reduce((a, b) => a + b, 0).toLocaleString()}})</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in evaluationUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ evaluationaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ evaluationaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}
                                    Total ( Ksh.
                                    <span v-if="evaluationaccountsdata.filter(item => item.user_id === id)">
                                        {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => (item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(',').join('')), 0).toLocaleString() }}
                                    </span>
                                    )
                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Meta</th>
                                    <th>Amount</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in evaluationaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                   
                                                </p>
                                                 <hr/>
                                        </div>
                                    </td>
                                    <td v-if="(account.meta).find(item => item.key === 'Expected Sale Value')">Ksh. {{ parseFloat((account.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")).toLocaleString() }}</td>
                                    <td v-else></td>
                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Approval Deals</h1>
                            <h1 class="pl-6 uppercase text-black font-bold">Total Amount (KSH {{approvalData.reduce((a, b) => a + b, 0).toLocaleString()}})</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in approvalUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ approvalaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ approvalaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}
                                    Total ( Ksh.
                                    <span v-if="approvalaccountsdata.filter(item => item.user_id === id)">
                                        {{ [...approvalaccountsdata.filter(item => item.user_id === id).map(item => (item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(',').join('')), 0).toLocaleString() }}
                                    </span>
                                    )
                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Meta</th>
                                    <th>Amount</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in approvalaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                   
                                                </p>
                                                 <hr/>
                                        </div>
                                    </td>
                                    <td v-if="(account.meta).find(item => item.key === 'Expected Sale Value')">Ksh. {{ parseFloat((account.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")).toLocaleString() }}</td>
                                    <td v-else></td>
                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Closed Deals</h1>
                            <h1 class="pl-6 uppercase text-black font-bold">Total  Amount (KSH {{closedData.reduce((a, b) => a + b, 0).toLocaleString()}})</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in closedUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ closedaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ closedaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}
                                    Total ( Ksh.
                                    <span v-if="closedaccountsdata.filter(item => item.user_id === id)">
                                        {{ [...closedaccountsdata.filter(item => item.user_id === id).map(item => (item.meta).find(item => item.key === 'Deal Amount'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(',').join('')), 0).toLocaleString() }}
                                    </span>
                                    )
                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Meta</th>
                                    <th>Amount</th>
                                    <th>Date Closed</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in closedaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                    
                                                </p>
                                                <hr/>
                                        </div>
                                    </td>
                                    <td v-if="(account.meta).find(item => item.key === 'Deal Amount')">Ksh. {{ parseFloat((account.meta).find(item => item.key === 'Deal Amount').value.split(",").join("")).toLocaleString() }}</td>
                                    <td v-else></td>
                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="closedaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...closedaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Deal Amount'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Lost Deals</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in lostUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ lostaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ lostaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}

                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Client</th>
                                    <th>Meta</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in lostaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td> {{ account.clientname}}<br>{{ account.clientemail }} <br> {{ account.clientlocation }}<br>{{ account.clientwebsiteurl }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                   
                                                </p>
                                                 <hr/>
                                        </div>
                                    </td>

                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Projects Stage</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in projectsUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ projectsaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ projectsaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}

                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Client</th>
                                    <th>Meta</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in projectsaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td> {{ account.clientname}}<br>{{ account.clientemail }} <br> {{ account.clientlocation }}<br>{{ account.clientwebsiteurl }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                    
                                                </p>
                                                <hr/>
                                        </div>
                                    </td>

                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Overdue Deals</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in overdueUserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">

                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ overdueaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ overdueaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}

                                    </h1>
                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Client</th>
                                    <th>Meta</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(account, index) in overdueaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }} <br> {{ account.solution_name }} <br> {{ account.solution_sub_type_name }}</td>
                                    <td> {{ account.clientname}}<br>{{ account.clientemail }} <br> {{ account.clientlocation }}<br>{{ account.clientwebsiteurl }}</td>
                                    <td class="px-2 py-2 border-b border-gray-300">
                                        <div v-if="account.meta" v-for="i in (account.meta)">
                                                <p>
                                                    <p class="px-2">
                                                        <span class="font-bold">{{ i["key"] }}</span>
                                                        :
                                                        <span v-if="typeof i['value'] === 'object'" v-for="document in i['value']">
                                                            <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                        </span>
                                                        <span v-else>{{ i["value"].toLocaleString() }}</span>
                                                    </p>
                                                </p>
                                                <hr/>
                                        </div>
                                    </td>

                                    <td>{{ formatDate(account.pdate) }}</td>
                                    <!-- <td v-if="evaluationaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>


                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, toRefs, ref, watchEffect } from 'vue';
import * as XLSX from "xlsx";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Icon } from "@iconify/vue";


const props = defineProps({
    accounts: Array,
    totals: Object,
    counts: Object,
    closedaccountsdata: Object,
    evaluationaccountsdata: Object,
});

const { closedaccountsdata } = toRefs(props);
const { evaluationaccountsdata } = toRefs(props);


const userIds = ref([]);
const euserIds = ref([]);
const accountData = ref([]);
const eaccountData = ref([]);
const myData = ref([]);
const emyData = ref([]);

for (const data of closedaccountsdata.value) {
    if (userIds.value.findIndex(item => item === data.user_id) === -1) {
        userIds.value.push(data.user_id);
    }
}

for (const data of evaluationaccountsdata.value) {
    if (euserIds.value.findIndex(item => item === data.user_id) === -1) {
        euserIds.value.push(data.user_id);
    }
}

for (const data of closedaccountsdata.value) {
    accountData.value.push(data);
}

for (const data of evaluationaccountsdata.value) {
    eaccountData.value.push(data);
}

watchEffect(() => {
    for (const data of accountData.value) {
        if(JSON.parse(data.meta).find(item => item.key === 'Deal Amount')) {
            myData.value.push(Number.parseFloat(JSON.parse(data.meta).find(item => item.key === 'Deal Amount').value.split(",").join("")));
        }
    }
})

watchEffect(() => {
    for (const data of eaccountData.value) {
        if(JSON.parse(data.meta).find(item => item.key === 'Expected Sale Value')) {
            emyData.value.push(Number.parseFloat(JSON.parse(data.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")));
        }
    }
})

function formatClients(clients) {
    if (!clients) {
        return '';
    }
    return clients.replace(/\|\|/g, '<br>');
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
                        <!-- Add other buttons or actions here if needed -->
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <h2 class="px-3">SUMMARY TABLE</h2>
                    <table class="w-full text-left text-sm  text-gray-500 border-separate border-spacing-y-4">
                        <thead class="text-xs text-gray-700 text-center uppercase bg-gray-50">
                            <tr>
                                <th class="px-3 py-3">Account Manager</th>
                                <th class="px-3 py-3">Total Accounts ({{ totals.total }})</th>
                                <th class="px-3 py-3">Prospects ({{ totals.prospects }})</th>
                                <th class="px-3 py-3">Scoping ({{ totals.scooping }})</th>
                                <th class="px-3 py-3">Evaluation ({{ totals.evaluation }})</th>
                                <th class="px-3 py-3">Approval ({{ totals.approval }})</th>
                                <th class="px-3 py-3">Closed ({{ totals.closed }})</th>
                                <th class="px-3 py-3">Lost ({{ totals.lost }})</th>
                                <th class="px-3 py-3">Presales ({{ totals.presales }})</th>
                                <th class="px-3 py-3">Projects ({{ totals.projects }})</th>
                                <th class="px-3 py-3">Overdue ({{ totals.overdue }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in counts" :key="item.user_id">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ item.first_name }} {{ item.last_name }}</td>
                                <td class="px-6 py-4">{{ item.accounts_count }}</td>
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

                    <div class="" v-for="(section, key) in accounts" :key="key">
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8 pb-7">
                            <div class="bg-white relative shadow-md sm:rounded-lg overflow-hidden">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">{{ key }}</h1>
                                <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th class="px-6 py-3">Account Manager</th>
                <th class="px-6 py-3">Count</th>

                <th>Accounts</th>
                <!-- <th>Client</th> -->
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in section" :key="item.user_id">
                <td class="px-6 py-4 font-medium text-gray-900">{{ item.name }}</td>
                <td class="px-6 py-4">{{ item.count }}</td>
                <td>
                    <table>
                        <thead>
                            <th>Account Name</th>
                            <!-- <th>Client</th> -->
                            <th>Meta</th>
                        </thead>
                        <tbody>
                            <tr v-if="item.business_names" v-for="(name, index) in item.business_names.split('||')" :key="index">
                                <td>{{ name }}</td>
                                <!-- <td class=" border-b border-gray-300">
                                    <div v-if="item.contact_informations" v-for="i in JSON.parse(item.contact_informations)[index]" :key="i.id">
                                        <div v-if="i">
                                            <p>{{ i.name !== null || i.name !== undefined || i.name !== '' ? i.name : '' }}</p>
                                            <p>{{ i.email !== null || i.email !== undefined || i.email !== '' ? i.email : '' }}</p>
                                            <p>{{ i.phone !== null || i.phone !== undefined || i.phone !== '' ? i.phone : '' }}</p>
                                            <p>{{ i.designation !== null || i.designation !== undefined || i.designation !== '' ? i.designation : '' }}</p>
                                        </div>
                                    </div>
                                </td> -->
                                <td class="px-2 py-2 border-b border-gray-300">
                                    <div v-if="item.metas" v-for="i in JSON.parse(item.metas)[index]">
                                        <div v-if="i" v-for="newItem in i">
                                            <p>
                                                <span class="px-2">
                                                    <span class="font-bold">{{ newItem["key"] }}</span>
                                                    :
                                                    <span v-if="typeof newItem['value'] === 'object'" v-for="document in newItem['value']">
                                                        <a :href="document['value']" target="_blank" class="text-blue-500">{{ document["key"] }}</a>
                                                    </span>
                                                     <span v-else>{{ newItem["value"].toLocaleString() }}</span>
                                                </span>
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
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Closed Deals</h1>
                            <h1 class="pl-6 uppercase text-black font-bold">Total  Amount (KSH {{myData.reduce((a, b) => a + b, 0).toLocaleString()}})</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in userIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ closedaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ closedaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}
                                    Total ( Ksh.
                                    <span v-if="closedaccountsdata.filter(item => item.user_id === id)">
                                        {{ [...closedaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Deal Amount'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(',').join('')), 0).toLocaleString() }}
                                    </span>
                                    )
                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Amount</th>
                                    <th>Date Closed</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="account in closedaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }}</td>
                                    <td v-if="JSON.parse(account.meta).find(item => item.key === 'Deal Amount')">Ksh. {{ parseFloat(JSON.parse(account.meta).find(item => item.key === 'Deal Amount').value.split(",").join("")).toLocaleString() }}</td>
                                    <td v-else></td>
                                    <td>{{ account.pdate }}</td>
                                    <!-- <td v-if="closedaccountsdata.filter(item => item.user_id === id)">Ksh. {{ [...closedaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Deal Amount'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(",").join("")), 0).toLocaleString() }}</td> -->
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>
                    </div>

                    <div>
                        <div class="p-2">
                            <h1 class="pl-6 uppercase text-black font-bold">Evaluation Deals</h1>
                            <h1 class="pl-6 uppercase text-black font-bold">Total Amount (KSH {{emyData.reduce((a, b) => a + b, 0).toLocaleString()}})</h1>

                        </div>
                        <div class="mx-auto max-w-screen-xl px-2 lg:px-8">
                            <div v-for="id in euserIds" class=" bg-white relative shadow-md sm:rounded-lg overflow-hidden pb-2 mb-6 px-10 border border-black rounded-md">
                                <h1 class="px-4 py-3 uppercase text-black font-bold">
                                    {{ evaluationaccountsdata.filter(item => item.user_id === id)[0].accountmanagerfirstname }}
                                    {{ evaluationaccountsdata.filter(item => item.user_id === id)[0].accountmanagerlastname }}
                                    Total ( Ksh.
                                    <span v-if="evaluationaccountsdata.filter(item => item.user_id === id)">
                                        {{ [...evaluationaccountsdata.filter(item => item.user_id === id).map(item => JSON.parse(item.meta).find(item => item.key === 'Expected Sale Value'))].filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.split(',').join('')), 0).toLocaleString() }}
                                    </span>
                                    )
                                    </h1>

                        <table class="w-full text-left text-sm text-gray-500 border-separate border-spacing-y-4">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Solution</th>
                                    <th>Amount</th>
                                    <th>Date Updated</th>
                                    <!-- <th>Total</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="account in evaluationaccountsdata.filter(item => item.user_id === id)">
                                    <td>{{ account.business_name }}</td>
                                    <td>{{ account.solution_type_name }}</td>
                                    <td v-if="JSON.parse(account.meta).find(item => item.key === 'Expected Sale Value')">Ksh. {{ parseFloat(JSON.parse(account.meta).find(item => item.key === 'Expected Sale Value').value.split(",").join("")).toLocaleString() }}</td>
                                    <td v-else></td>
                                    <td>{{ account.pdate }}</td>
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

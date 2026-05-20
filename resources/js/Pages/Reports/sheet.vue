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

function parseMeta(meta) {
    if (!meta) return [];
    if (typeof meta === 'string') {
        try { return JSON.parse(meta); } catch { return []; }
    }
    return Array.isArray(meta) ? meta : [meta];
}

function parseMetas(metas) {
    if (!metas) return [];
    if (typeof metas === 'string') {
        try { return JSON.parse(metas); } catch { return []; }
    }
    return Array.isArray(metas) ? metas : [];
}

watchEffect(() => {
    for (const data of accountData.value) {
        const meta = parseMeta(data.meta);
        const dealAmount = meta.find(item => item.key === 'Deal Amount');
        if (dealAmount) {
            myData.value.push(Number.parseFloat(dealAmount.value.split(",").join("")));
        }
    }
})

watchEffect(() => {
    for (const data of eaccountData.value) {
        const meta = parseMeta(data.meta);
        const esv = meta.find(item => item.key === 'Expected Sale Value');
        if (esv) {
            emyData.value.push(Number.parseFloat(esv.value.split(",").join("")));
        }
    }
})

function getMetaValue(meta, key) {
    const arr = parseMeta(meta);
    const found = arr.find(item => item && item.key === key);
    return found ? found.value : null;
}

function metaAmount(meta, key) {
    const val = getMetaValue(meta, key);
    if (!val) return null;
    return parseFloat(String(val).split(',').join(''));
}

function sumMetaForUsers(data, userId, key) {
    return data
        .filter(item => item.user_id === userId)
        .reduce((total, item) => {
            const amt = metaAmount(item.meta, key);
            return total + (amt || 0);
        }, 0);
}

function formatClients(clients) {
    if (!clients) {
        return '';
    }
    return clients.replace(/\|\|/g, '<br>');
}


function printDiv() {
    const divToPrint = document.querySelector('.overflow-x-auto').innerHTML;
    const newWindow = window.open('', '', 'height=600,width=800');
    newWindow.document.write('<html><head><title>CRM by sell.ke – Accounts</title>');
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

        <section class="px-4 sm:px-8 pb-12 space-y-8 mt-4">

            <!-- Page header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Accounts Sheet</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Pipeline overview across all account managers</p>
                </div>
                <button @click="printDiv"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 shadow-sm transition">
                    <Icon icon="material-symbols-light:print-outline" class="h-5 w-5" />
                    Print
                </button>
            </div>

            <!-- Pipeline totals stat bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-gray-800">{{ totals.total }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium">Total</p>
                </div>
                <div class="bg-sky-50 rounded-2xl border border-sky-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-sky-600">{{ totals.prospects }}</p>
                    <p class="text-xs text-sky-500 mt-1 font-medium">Prospects</p>
                </div>
                <div class="bg-violet-50 rounded-2xl border border-violet-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-violet-600">{{ totals.scooping }}</p>
                    <p class="text-xs text-violet-500 mt-1 font-medium">Scoping</p>
                </div>
                <div class="bg-amber-50 rounded-2xl border border-amber-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ totals.evaluation }}</p>
                    <p class="text-xs text-amber-500 mt-1 font-medium">Evaluation</p>
                </div>
                <div class="bg-orange-50 rounded-2xl border border-orange-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-orange-600">{{ totals.approval }}</p>
                    <p class="text-xs text-orange-500 mt-1 font-medium">Approval</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ totals.closed }}</p>
                    <p class="text-xs text-emerald-500 mt-1 font-medium">Closed</p>
                </div>
            </div>

            <!-- Summary table -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    <h2 class="font-semibold text-gray-800">Pipeline by Account Manager</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account Manager</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-sky-500 uppercase tracking-wider">Prospects</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-violet-500 uppercase tracking-wider">Scoping</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-amber-500 uppercase tracking-wider">Evaluation</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-orange-500 uppercase tracking-wider">Approval</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-emerald-600 uppercase tracking-wider">Closed</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-red-400 uppercase tracking-wider">Lost</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-indigo-500 uppercase tracking-wider">Presales</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-teal-500 uppercase tracking-wider">Projects</th>
                                <th class="px-5 py-3.5 text-center text-xs font-semibold text-rose-500 uppercase tracking-wider">Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="item in counts" :key="item.user_id" class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-semibold text-gray-800">{{ item.first_name }} {{ item.last_name }}</td>
                                <td class="px-5 py-3.5 text-center font-bold text-gray-700">{{ item.accounts_count }}</td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-sky-50 text-sky-600 font-semibold text-xs">{{ item.prospects_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-violet-50 text-violet-600 font-semibold text-xs">{{ item.scooping_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-50 text-amber-600 font-semibold text-xs">{{ item.evaluation_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-50 text-orange-600 font-semibold text-xs">{{ item.approval_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-xs">{{ item.closed_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-50 text-red-500 font-semibold text-xs">{{ item.lost_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 font-semibold text-xs">{{ item.presales_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-teal-50 text-teal-600 font-semibold text-xs">{{ item.projects_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-rose-50 text-rose-500 font-semibold text-xs">{{ item.overdue_count }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pipeline stages breakdown -->
            <div v-for="(section, key) in accounts" :key="key" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <h2 class="font-semibold text-gray-800 capitalize">{{ key }}</h2>
                    </div>
                    <span class="text-xs text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-lg font-medium">
                        {{ section.reduce((s, i) => s + (i.count || 0), 0) }} accounts
                    </span>
                </div>
                <div class="divide-y divide-gray-50">
                    <div v-for="item in section" :key="item.user_id" class="px-6 py-4">
                        <!-- Rep header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ (item.name?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <span class="font-semibold text-gray-800 text-sm">{{ item.name }}</span>
                            </div>
                            <span class="text-xs bg-gray-100 text-gray-600 font-semibold px-2.5 py-1 rounded-lg">{{ item.count }} account{{ item.count !== 1 ? 's' : '' }}</span>
                        </div>
                        <!-- Account pills -->
                        <div v-if="item.business_names" class="flex flex-wrap gap-2">
                            <span v-for="(name, index) in item.business_names.split('||')" :key="index"
                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-50 border border-gray-100 text-xs text-gray-700 font-medium">
                                {{ name }}
                            </span>
                        </div>
                        <p v-else class="text-xs text-gray-400 italic">No accounts in this stage</p>
                    </div>
                </div>
            </div>

            <!-- Closed Deals section -->
            <div v-if="userIds.length > 0">
                <!-- Section header with total -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h2 class="font-bold text-gray-800 text-lg">Closed Deals</h2>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-right">
                        <p class="text-xs text-emerald-600 font-medium">Total Revenue</p>
                        <p class="text-lg font-bold text-emerald-700">Ksh {{ myData.reduce((a, b) => a + b, 0).toLocaleString() }}</p>
                    </div>
                </div>
                <!-- Per-rep cards -->
                <div class="space-y-4">
                    <div v-for="id in userIds" :key="id" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <!-- Rep header -->
                        <div class="px-6 py-4 bg-emerald-50/60 border-b border-emerald-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
                                    {{ (closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                        {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanageremail }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-emerald-600 font-medium">Total Closed</p>
                                <p class="text-lg font-bold text-emerald-700">Ksh {{ sumMetaForUsers(closedaccountsdata, id, 'Deal Amount').toLocaleString() }}</p>
                            </div>
                        </div>
                        <!-- Deals table -->
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50/60 border-b border-gray-100">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account / Client</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Solution</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Deal Amount</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Closed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="account in closedaccountsdata.filter(item => item.user_id === id)" :key="account.id"
                                    class="hover:bg-emerald-50/20 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <p class="font-semibold text-gray-800">{{ account.business_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ account.clientname }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span v-if="account.solution_type_name" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-medium">
                                            {{ account.solution_type_name }}
                                        </span>
                                        <span v-else class="text-gray-400 text-xs">—</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span v-if="getMetaValue(account.meta, 'Deal Amount')" class="font-bold text-emerald-700">
                                            Ksh {{ metaAmount(account.meta, 'Deal Amount').toLocaleString() }}
                                        </span>
                                        <span v-else class="text-gray-400 text-xs">—</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500 text-xs">{{ account.pdate }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Evaluation Deals section -->
            <div v-if="euserIds.length > 0">
                <!-- Section header with total -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <h2 class="font-bold text-gray-800 text-lg">Evaluation Deals</h2>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-2 text-right">
                        <p class="text-xs text-amber-600 font-medium">Total Pipeline Value</p>
                        <p class="text-lg font-bold text-amber-700">Ksh {{ emyData.reduce((a, b) => a + b, 0).toLocaleString() }}</p>
                    </div>
                </div>
                <!-- Per-rep cards -->
                <div class="space-y-4">
                    <div v-for="id in euserIds" :key="id" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <!-- Rep header -->
                        <div class="px-6 py-4 bg-amber-50/60 border-b border-amber-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center text-sm font-bold">
                                    {{ (evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                        {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanageremail }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-amber-600 font-medium">Expected Value</p>
                                <p class="text-lg font-bold text-amber-700">Ksh {{ sumMetaForUsers(evaluationaccountsdata, id, 'Expected Sale Value').toLocaleString() }}</p>
                            </div>
                        </div>
                        <!-- Deals table -->
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50/60 border-b border-gray-100">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Account / Client</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Solution</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Expected Value</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Updated</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="account in evaluationaccountsdata.filter(item => item.user_id === id)" :key="account.id"
                                    class="hover:bg-amber-50/20 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <p class="font-semibold text-gray-800">{{ account.business_name }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ account.clientname }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span v-if="account.solution_type_name" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-medium">
                                            {{ account.solution_type_name }}
                                        </span>
                                        <span v-else class="text-gray-400 text-xs">—</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span v-if="getMetaValue(account.meta, 'Expected Sale Value')" class="font-bold text-amber-700">
                                            Ksh {{ metaAmount(account.meta, 'Expected Sale Value').toLocaleString() }}
                                        </span>
                                        <span v-else class="text-gray-400 text-xs">—</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-500 text-xs">{{ account.pdate }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </AppLayout>
</template>

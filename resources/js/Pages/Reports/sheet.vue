<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, toRefs, ref, watchEffect } from 'vue';
import * as XLSX from "xlsx";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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
    newWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>');
    newWindow.document.write('</head><body>');
    newWindow.document.write(divToPrint);
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.focus();
    newWindow.print();
}

function exportToExcel() {
    const workbook = XLSX.utils.book_new();

    if (props.accounts) {
        const accountsWorksheet = XLSX.utils.json_to_sheet(props.accounts);
        XLSX.utils.book_append_sheet(workbook, accountsWorksheet, "Accounts");
    }

    if (props.totals) {
        const totalsWorksheet = XLSX.utils.json_to_sheet([props.totals]);
        XLSX.utils.book_append_sheet(workbook, totalsWorksheet, "Totals");
    }

    if (props.counts) {
        const countsWorksheet = XLSX.utils.json_to_sheet([props.counts]);
        XLSX.utils.book_append_sheet(workbook, countsWorksheet, "Counts");
    }

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

    if (props.startdate && props.enddate) {
        const dateWorksheet = XLSX.utils.json_to_sheet([
            { 'Start Date': props.startdate, 'End Date': props.enddate }
        ]);
        XLSX.utils.book_append_sheet(workbook, dateWorksheet, "Date Range");
    }

    XLSX.writeFile(workbook, "accounts_data.xlsx");
}

const avatarColors = ['bg-indigo-500','bg-blue-500','bg-emerald-500','bg-purple-500','bg-pink-500','bg-amber-500'];
const avatarBg = (id) => avatarColors[id % avatarColors.length];
</script>

<template>
    <AppLayout title="Accounts Sheet">
        <div class="space-y-6">

            <!-- Page header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Accounts Sheet</h1>
                    <p class="text-xs text-gray-500 mt-1 font-semibold">Comprehensive pipeline overview across all managers</p>
                </div>
                <div class="flex items-center gap-2">
                    <SecondaryButton @click="printDiv">
                        <div class="flex items-center gap-1.5">
                            <Icon icon="material-symbols:print" class="h-4 w-4" />
                            Print
                        </div>
                    </SecondaryButton>
                    <PrimaryButton @click="exportToExcel">
                        <div class="flex items-center gap-1.5">
                            <Icon icon="material-symbols:download" class="h-4 w-4" />
                            Excel Export
                        </div>
                    </PrimaryButton>
                </div>
            </div>

            <!-- Pipeline totals stat bar -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-gray-900 leading-none mb-1">{{ totals.total }}</p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">All Accounts</p>
                </div>
                <div class="bg-sky-50/50 rounded-xl border border-sky-100 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-sky-700 leading-none mb-1">{{ totals.prospects }}</p>
                    <p class="text-[10px] text-sky-500 font-bold uppercase tracking-wider">Prospects</p>
                </div>
                <div class="bg-indigo-50/50 rounded-xl border border-indigo-100 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-indigo-700 leading-none mb-1">{{ totals.scooping }}</p>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider">Scoping</p>
                </div>
                <div class="bg-amber-50/50 rounded-xl border border-amber-100 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-amber-700 leading-none mb-1">{{ totals.evaluation }}</p>
                    <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider">Evaluation</p>
                </div>
                <div class="bg-orange-50/50 rounded-xl border border-orange-100 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-orange-700 leading-none mb-1">{{ totals.approval }}</p>
                    <p class="text-[10px] text-orange-500 font-bold uppercase tracking-wider">Approval</p>
                </div>
                <div class="bg-emerald-50/50 rounded-xl border border-emerald-100 shadow-card p-4 text-center">
                    <p class="text-2xl font-black text-emerald-700 leading-none mb-1">{{ totals.closed }}</p>
                    <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider">Closed Won</p>
                </div>
            </div>

            <!-- Summary table -->
            <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    <h2 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Pipeline count by Account Manager</h2>
                </div>
                <div class="overflow-x-auto thin-scrollbar">
                    <table class="min-w-full text-xs text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="px-5 py-3.5">Account Manager</th>
                                <th class="px-5 py-3.5 text-center">Total</th>
                                <th class="px-5 py-3.5 text-center text-sky-600">Prospects</th>
                                <th class="px-5 py-3.5 text-center text-indigo-600">Scoping</th>
                                <th class="px-5 py-3.5 text-center text-amber-600 font-bold">Evaluation</th>
                                <th class="px-5 py-3.5 text-center text-orange-600">Approval</th>
                                <th class="px-5 py-3.5 text-center text-emerald-600 font-bold">Closed</th>
                                <th class="px-5 py-3.5 text-center text-red-500">Lost</th>
                                <th class="px-5 py-3.5 text-center text-purple-600">Presales</th>
                                <th class="px-5 py-3.5 text-center text-teal-600">Projects</th>
                                <th class="px-5 py-3.5 text-center text-rose-500 font-bold">Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in counts" :key="item.user_id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-gray-900">{{ item.first_name }} {{ item.last_name }}</td>
                                <td class="px-5 py-3.5 text-center font-extrabold text-gray-800">{{ item.accounts_count }}</td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-sky-50 text-sky-600 font-bold text-[10px]">{{ item.prospects_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-bold text-[10px]">{{ item.scooping_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 font-bold text-[10px]">{{ item.evaluation_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-orange-50 text-orange-600 font-bold text-[10px]">{{ item.approval_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[10px]">{{ item.closed_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-bold text-[10px]">{{ item.lost_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-purple-50 text-purple-600 font-bold text-[10px]">{{ item.presales_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-teal-50 text-teal-600 font-bold text-[10px]">{{ item.projects_count }}</span></td>
                                <td class="px-5 py-3.5 text-center"><span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-bold text-[10px]">{{ item.overdue_count }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pipeline stages breakdown -->
            <div v-for="(section, key) in accounts" :key="key" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <h2 class="font-bold text-gray-800 text-xs uppercase tracking-wider">{{ key }}</h2>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 font-bold text-[10px]">
                        {{ section.reduce((s, i) => s + (i.count || 0), 0) }} Accounts
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    <div v-for="item in section" :key="item.user_id" class="px-5 py-4">
                        <!-- Rep header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-primary text-white flex items-center justify-center text-[10px] font-extrabold flex-shrink-0">
                                    {{ (item.name?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <span class="font-bold text-gray-900 text-xs">{{ item.name }}</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-50 border border-gray-100 text-[10px] text-gray-500 font-bold">{{ item.count }} Deal{{ item.count !== 1 ? 's' : '' }}</span>
                        </div>
                        <!-- Account pills -->
                        <div v-if="item.business_names" class="flex flex-wrap gap-2">
                            <span v-for="(name, index) in item.business_names.split('||')" :key="index"
                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-gray-50 border border-gray-100/80 text-[10px] text-gray-700 font-semibold transition-all hover:bg-gray-100">
                                {{ name }}
                            </span>
                        </div>
                        <p v-else class="text-[10px] text-gray-400 font-medium italic">No accounts in this stage</p>
                    </div>
                </div>
            </div>

            <!-- Closed Deals section -->
            <div v-if="userIds.length > 0" class="space-y-4">
                <!-- Section header with total -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h2 class="font-bold text-gray-900 text-base">Closed Deals</h2>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-right">
                        <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-wider">Total Revenue</p>
                        <p class="text-base font-black text-emerald-700">Ksh {{ myData.reduce((a, b) => a + b, 0).toLocaleString() }}</p>
                    </div>
                </div>
                <!-- Per-rep cards -->
                <div class="space-y-4">
                    <div v-for="id in userIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <!-- Rep header -->
                        <div class="px-5 py-3.5 bg-emerald-50/30 border-b border-emerald-100/50 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                                    {{ (closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-xs">
                                        {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                        {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-semibold">{{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanageremail }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-wider">Total Closed</p>
                                <p class="text-sm font-black text-emerald-700">Ksh {{ sumMetaForUsers(closedaccountsdata, id, 'Deal Amount').toLocaleString() }}</p>
                            </div>
                        </div>
                        <!-- Deals table -->
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3">Account / Client</th>
                                        <th class="px-5 py-3">Solution Type</th>
                                        <th class="px-5 py-3 text-right">Deal Amount</th>
                                        <th class="px-5 py-3">Date Closed</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="account in closedaccountsdata.filter(item => item.user_id === id)" :key="account.id"
                                        class="hover:bg-emerald-50/10 transition-colors">
                                        <td class="px-5 py-3.5">
                                            <p class="font-bold text-gray-900">{{ account.business_name }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ account.clientname }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span v-if="account.solution_type_name" class="badge badge-success font-bold">
                                                {{ account.solution_type_name }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-bold text-emerald-700">
                                            <span v-if="getMetaValue(account.meta, 'Deal Amount')">
                                                Ksh {{ metaAmount(account.meta, 'Deal Amount').toLocaleString() }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ account.pdate }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation Deals section -->
            <div v-if="euserIds.length > 0" class="space-y-4">
                <!-- Section header with total -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <h2 class="font-bold text-gray-900 text-base">Evaluation Deals</h2>
                    </div>
                    <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-2 text-right">
                        <p class="text-[9px] text-amber-600 font-bold uppercase tracking-wider">Total Pipeline Value</p>
                        <p class="text-base font-black text-amber-700">Ksh {{ emyData.reduce((a, b) => a + b, 0).toLocaleString() }}</p>
                    </div>
                </div>
                <!-- Per-rep cards -->
                <div class="space-y-4">
                    <div v-for="id in euserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <!-- Rep header -->
                        <div class="px-5 py-3.5 bg-amber-50/30 border-b border-amber-100/50 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                                    {{ (evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname?.[0] ?? '?').toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-xs">
                                        {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                        {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 font-semibold">{{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanageremail }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-amber-600 font-bold uppercase tracking-wider">Expected Value</p>
                                <p class="text-sm font-black text-amber-700">Ksh {{ sumMetaForUsers(evaluationaccountsdata, id, 'Expected Sale Value').toLocaleString() }}</p>
                            </div>
                        </div>
                        <!-- Deals table -->
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3">Account / Client</th>
                                        <th class="px-5 py-3">Solution Type</th>
                                        <th class="px-5 py-3 text-right">Expected Value</th>
                                        <th class="px-5 py-3">Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="account in evaluationaccountsdata.filter(item => item.user_id === id)" :key="account.id"
                                        class="hover:bg-amber-50/10 transition-colors">
                                        <td class="px-5 py-3.5">
                                            <p class="font-bold text-gray-900">{{ account.business_name }}</p>
                                            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">{{ account.clientname }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span v-if="account.solution_type_name" class="badge badge-warning font-bold">
                                                {{ account.solution_type_name }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-bold text-amber-700">
                                            <span v-if="getMetaValue(account.meta, 'Expected Sale Value')">
                                                Ksh {{ metaAmount(account.meta, 'Expected Sale Value').toLocaleString() }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ account.pdate }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

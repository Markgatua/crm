<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { defineProps, toRefs, ref, watchEffect } from 'vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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
    closedData.value = [];
    for (const data of closedAccountData.value) {
        const metaObj = safeParseMeta(data.meta);
        const dealAmount = (metaObj || []).find(item => item && item.key === 'Deal Amount');
        if (dealAmount) {
            closedData.value.push(Number.parseFloat(dealAmount.value.toString().split(",").join("")));
        }
    }
})

watchEffect(() => {
    evaluationData.value = [];
    for (const data of evaluationAccountData.value) {
        const metaObj = safeParseMeta(data.meta);
        const esv = (metaObj || []).find(item => item && item.key === 'Expected Sale Value');
        if (esv) {
            evaluationData.value.push(Number.parseFloat(esv.value.toString().split(",").join("")));
        }
    }
})

watchEffect(() => {
    approvalData.value = [];
    for (const data of approvalAccountData.value) {
        const metaObj = safeParseMeta(data.meta);
        const esv = (metaObj || []).find(item => item && item.key === 'Expected Sale Value');
        if (esv) {
            approvalData.value.push(Number.parseFloat(esv.value.toString().split(",").join("")));
        }
    }
})

function safeParseMeta(meta) {
    if (!meta) return [];
    if (typeof meta === 'string') {
        try {
            return JSON.parse(meta);
        } catch (e) {
            return [];
        }
    }
    return meta;
}

function safeParseJSON(str) {
    if (!str) return [];
    try {
        return typeof str === 'string' ? JSON.parse(str) : str;
    } catch(e) {
        return [];
    }
}

function formatClients(clients) {
    if (!clients) {
        return '';
    }
    return clients.replace(/\|\|/g, '<br>');
}

function formatDate(date){
    if (!date) return '—';
    const options = { year: 'numeric', month: 'long', day: '2-digit' };
    return new Date(date).toLocaleDateString('en-US', options);
}

function printDiv() {
    const divToPrint = document.querySelector('.printable-area').innerHTML;
    const newWindow = window.open('', '', 'height=600,width=800');
    newWindow.document.write('<html><head><title>Executive Pipeline Statement</title>');
    newWindow.document.write('<style>body { font-family: sans-serif; margin: 30px; color: #333; } table { width: 100%; border-collapse: collapse; margin-bottom: 25px; } th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 11px; } th { bg-color: #f7fafc; font-weight: bold; } h1, h2, h3 { font-family: sans-serif; } .header-banner { border-bottom: 2px solid #3b82f6; padding-bottom: 10px; margin-bottom: 20px; }</style>');
    newWindow.document.write('</head><body>');
    newWindow.document.write('<div class="printable-area">' + divToPrint + '</div>');
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    newWindow.focus();
    setTimeout(() => {
        newWindow.print();
    }, 500);
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
        const countsWorksheet = XLSX.utils.json_to_sheet(props.counts);
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
            { 'Start Date': formatDate(props.startdate), 'End Date': formatDate(props.enddate) }
        ]);
        XLSX.utils.book_append_sheet(workbook, dateWorksheet, "Date Range");
    }

    XLSX.writeFile(workbook, "consolidated_manager_report.xlsx");
}
</script>

<template>
    <AppLayout title="Consolidated Managers Report">
        <div class="space-y-6">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Consolidated Managers Report</h1>
                    <p class="text-xs text-gray-500 mt-1 font-semibold">Consolidated performance tracking across all account managers</p>
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

            <!-- Printable content area -->
            <div class="printable-area space-y-6">
                <!-- Title Card -->
                <div class="bg-white rounded-xl border border-gray-200/80 p-5 shadow-card">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div>
                            <span class="inline-flex px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-extrabold uppercase mb-2">Consolidated Statement</span>
                            <h2 class="text-base font-extrabold text-gray-900">All Account Managers Pipeline</h2>
                            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">Enterprise summary status indicators</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Report Duration</p>
                            <p class="text-xs font-bold text-gray-700 mt-1">
                                <span class="text-primary">{{ formatDate(startdate) }}</span>
                                <span class="mx-1.5 text-gray-300">to</span>
                                <span class="text-primary">{{ formatDate(enddate) }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Summary statistics table card -->
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2 bg-gray-50/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                        <h2 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Consolidated pipeline counts</h2>
                    </div>
                    <div class="overflow-x-auto thin-scrollbar">
                        <table class="min-w-full text-xs text-left">
                            <thead>
                                <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                    <th class="px-5 py-3">Account Manager</th>
                                    <th class="px-4 py-3 text-center text-sky-600">Prospects</th>
                                    <th class="px-4 py-3 text-center text-indigo-600">Scoping</th>
                                    <th class="px-4 py-3 text-center text-amber-600 font-bold">Evaluation</th>
                                    <th class="px-4 py-3 text-center text-orange-600 font-bold">Approval</th>
                                    <th class="px-4 py-3 text-center text-emerald-600 font-bold">Closed Won</th>
                                    <th class="px-4 py-3 text-center text-red-500">Lost</th>
                                    <th class="px-4 py-3 text-center text-purple-600">Presales</th>
                                    <th class="px-4 py-3 text-center text-teal-600">Projects</th>
                                    <th class="px-4 py-3 text-center text-rose-500 font-bold">Overdue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium text-gray-600">
                                <tr v-for="item in counts" :key="item.user_id" class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-5 py-3.5 font-bold text-gray-900">{{ item.first_name }} {{ item.last_name }}</td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-sky-50 text-sky-600 font-bold text-[10px]">{{ item.prospects_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 font-bold text-[10px]">{{ item.scooping_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 font-bold text-[10px]">{{ item.evaluation_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-orange-50 text-orange-600 font-bold text-[10px]">{{ item.approval_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[10px]">{{ item.closed_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-bold text-[10px]">{{ item.lost_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-purple-50 text-purple-600 font-bold text-[10px]">{{ item.presales_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-teal-50 text-teal-600 font-bold text-[10px]">{{ item.projects_count }}</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-bold text-[10px]">{{ item.overdue_count }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Prospects Deals -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                        <h3 class="font-bold text-gray-900 text-sm">Prospects Deals</h3>
                    </div>
                    <div v-for="id in prospectUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ prospectaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ prospectaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Client details</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3">Key Contacts</th>
                                        <th class="px-5 py-3">Date Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in prospectaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <p>{{ account.clientname }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ account.clientemail }}</p>
                                            <p class="text-[9px] text-gray-400 mt-0.5">{{ account.clientlocation }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1 text-[10px] font-semibold text-gray-600">
                                                <div v-for="(i, idx) in safeParseJSON(account.mainclientcontactinformation)" :key="idx" class="p-1 rounded bg-gray-50 border border-gray-100">
                                                    <p class="text-gray-900 font-bold">{{ i.name }}</p>
                                                    <p class="text-gray-500 font-medium">{{ i.email }} | {{ i.phone }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Scoping Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <h3 class="font-bold text-gray-900 text-sm">Scoping Deals</h3>
                    </div>
                    <div v-for="id in scopingUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ scopingaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ scopingaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in scopingaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-primary font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            <h3 class="font-bold text-gray-900 text-sm">Evaluation Deals</h3>
                        </div>
                        <span class="inline-flex px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100 shadow-xs">
                            Total Valuation: Ksh {{ evaluationData.reduce((a, b) => a + b, 0).toLocaleString() }}
                        </span>
                    </div>
                    <div v-for="id in evaluationUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ evaluationaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">
                                Executive Total: Ksh {{ evaluationaccountsdata.filter(item => item.user_id === id).map(item => safeParseMeta(item.meta).find(metaItem => metaItem.key === 'Expected Sale Value')).filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.toString().split(',').join('')), 0).toLocaleString() }}
                            </span>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3 text-right">Valuation</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in evaluationaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-warning font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-extrabold text-amber-700">
                                            <span v-if="safeParseMeta(account.meta).find(item => item.key === 'Expected Sale Value')">
                                                Ksh {{ parseFloat(safeParseMeta(account.meta).find(item => item.key === 'Expected Sale Value').value.toString().split(',').join('')).toLocaleString() }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Approval Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                            <h3 class="font-bold text-gray-900 text-sm">Approval Deals</h3>
                        </div>
                        <span class="inline-flex px-3 py-1.5 rounded-lg bg-orange-50 text-orange-700 text-xs font-bold border border-orange-100 shadow-xs">
                            Total Pipeline: Ksh {{ approvalData.reduce((a, b) => a + b, 0).toLocaleString() }}
                        </span>
                    </div>
                    <div v-for="id in approvalUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ approvalaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ approvalaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                            <span class="text-[10px] font-bold text-orange-700 bg-orange-50 px-2 py-0.5 rounded">
                                Executive Total: Ksh {{ approvalaccountsdata.filter(item => item.user_id === id).map(item => safeParseMeta(item.meta).find(metaItem => metaItem.key === 'Expected Sale Value')).filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.toString().split(',').join('')), 0).toLocaleString() }}
                            </span>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3 text-right">Approval Amount</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in approvalaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-warning font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-extrabold text-orange-700">
                                            <span v-if="safeParseMeta(account.meta).find(item => item.key === 'Expected Sale Value')">
                                                Ksh {{ parseFloat(safeParseMeta(account.meta).find(item => item.key === 'Expected Sale Value').value.toString().split(',').join('')).toLocaleString() }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Closed Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-bold text-gray-900 text-sm">Closed Won Deals</h3>
                        </div>
                        <span class="inline-flex px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100 shadow-xs">
                            Total Revenue: Ksh {{ closedData.reduce((a, b) => a + b, 0).toLocaleString() }}
                        </span>
                    </div>
                    <div v-for="id in closedUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ closedaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                                Executive Total: Ksh {{ closedaccountsdata.filter(item => item.user_id === id).map(item => safeParseMeta(item.meta).find(metaItem => metaItem.key === 'Deal Amount')).filter(deal => deal).reduce((total, deal) => total + parseFloat(deal.value.toString().split(',').join('')), 0).toLocaleString() }}
                            </span>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3 text-right">Deal Amount</th>
                                        <th class="px-5 py-3">Date Closed</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in closedaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-success font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-extrabold text-emerald-700">
                                            <span v-if="safeParseMeta(account.meta).find(item => item.key === 'Deal Amount')">
                                                Ksh {{ parseFloat(safeParseMeta(account.meta).find(item => item.key === 'Deal Amount').value.toString().split(',').join('')).toLocaleString() }}
                                            </span>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Lost Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <h3 class="font-bold text-gray-900 text-sm">Lost Deals</h3>
                    </div>
                    <div v-for="id in lostUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ lostaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ lostaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Client</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in lostaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-danger font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-600">
                                            <p>{{ account.clientname }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ account.clientemail }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Projects Stage -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                        <h3 class="font-bold text-gray-900 text-sm">Projects Stage</h3>
                    </div>
                    <div v-for="id in projectsUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ projectsaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ projectsaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Client</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="(account, index) in projectsaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-success bg-teal-50 text-teal-700 font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-600">
                                            <p>{{ account.clientname }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ account.clientemail }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Overdue Deals -->
                <div class="space-y-3 mt-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h3 class="font-bold text-gray-900 text-sm">Overdue Deals</h3>
                    </div>
                    <div v-for="id in overdueUserIds" :key="id" class="bg-white rounded-xl border border-gray-200/80 shadow-card overflow-hidden">
                        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h4 class="font-bold text-gray-800 text-[11px] uppercase tracking-wider">
                                {{ overdueaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerfirstname }}
                                {{ overdueaccountsdata.filter(item => item.user_id === id)[0]?.accountmanagerlastname }}
                            </h4>
                        </div>
                        <div class="overflow-x-auto thin-scrollbar">
                            <table class="min-w-full text-xs text-left">
                                <thead>
                                    <tr class="bg-gray-50/20 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                                        <th class="px-5 py-3 w-12">#</th>
                                        <th class="px-5 py-3">Account</th>
                                        <th class="px-5 py-3">Solution</th>
                                        <th class="px-5 py-3">Client</th>
                                        <th class="px-5 py-3">Meta values</th>
                                        <th class="px-5 py-3">Date Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-medium text-gray-600">
                                    <tr v-for="(account, index) in overdueaccountsdata.filter(item => item.user_id === id)" :key="account.id" class="hover:bg-gray-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-bold text-gray-400">{{ index + 1 }}</td>
                                        <td class="px-5 py-3.5 font-bold text-gray-900">{{ account.business_name }}</td>
                                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                                            <span class="badge badge-danger font-bold">{{ account.solution_type_name }}</span>
                                            <p class="text-[10px] text-gray-500 mt-1">{{ account.solution_name }}</p>
                                        </td>
                                        <td class="px-5 py-3.5 font-semibold">
                                            <p class="text-gray-900">{{ account.clientname }}</p>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ account.clientemail }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <div class="space-y-1">
                                                <div v-for="(i, idx) in safeParseMeta(account.meta)" :key="idx" class="text-[10px]">
                                                    <span class="font-bold text-gray-600">{{ i.key }}:</span>
                                                    <span v-if="typeof i.value === 'object'" class="inline-flex gap-1 ml-1">
                                                        <a v-for="(doc, dIdx) in i.value" :key="dIdx" :href="doc.value" target="_blank" class="text-primary hover:underline font-bold">{{ doc.key }}</a>
                                                    </span>
                                                    <span v-else class="text-gray-900 font-semibold ml-1">{{ i.value }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-500 font-medium">{{ formatDate(account.pdate) }}</td>
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

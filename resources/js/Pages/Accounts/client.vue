<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";

const props = defineProps({
    accounts: Array,
    titlename: String,
});

const search = ref('');

const filteredAccounts = computed(() => {
    if (!search.value) return props.accounts;
    const q = search.value.toLowerCase();
    return props.accounts.filter(a =>
        (a.business_name || '').toLowerCase().includes(q) ||
        (a.clientname || '').toLowerCase().includes(q) ||
        (a.accountmanagerfirstname || '').toLowerCase().includes(q) ||
        (a.accountmanagerlastname || '').toLowerCase().includes(q)
    );
});

const latestStage = (account) => {
    if (!account.project_stage_information || !account.project_stage_information.length) return null;
    return account.project_stage_information[account.project_stage_information.length - 1];
};

const stageBadgeClass = (stageName) => {
    const n = (stageName || '').toLowerCase();
    if (n.includes('prospect')) return 'bg-blue-100 text-blue-700';
    if (n.includes('scoop')) return 'bg-yellow-100 text-yellow-700';
    if (n.includes('eval')) return 'bg-orange-100 text-orange-700';
    if (n.includes('approv')) return 'bg-purple-100 text-purple-700';
    if (n.includes('closed') || n.includes('won')) return 'bg-green-100 text-green-700';
    return 'bg-gray-100 text-gray-600';
};

const avatarBg = (name) => {
    const colors = ['bg-blue-500','bg-indigo-500','bg-violet-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-cyan-500'];
    let h = 0;
    for (const c of (name || 'X')) h = (h * 31 + c.charCodeAt(0)) % colors.length;
    return colors[h];
};

const initials = (first, last) => ((first?.[0] || '') + (last?.[0] || '')).toUpperCase() || '?';
</script>


<template>
    <AppLayout :title="titlename">
        <PageHeader :title="titlename" optionalText="Clients" name="Client Accounts" />

        <div class="px-4 sm:px-8 mt-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <!-- Table toolbar -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700">
                        {{ accounts.length }} Account{{ accounts.length !== 1 ? 's' : '' }}
                    </h2>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search accounts…"
                            class="pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Business</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Account Manager</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Type</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Current Stage</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Contact</th>
                                <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="filteredAccounts.length === 0">
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No accounts found.</td>
                            </tr>
                            <tr
                                v-for="item in filteredAccounts"
                                :key="item.id"
                                class="hover:bg-gray-50/70 transition-colors duration-100"
                            >
                                <!-- Business Name -->
                                <td class="px-5 py-4">
                                    <Link :href="route('accounts.account', { id: item.id })" class="font-semibold text-primary hover:underline">
                                        {{ item.business_name }}
                                    </Link>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ item.clientlocation }}</p>
                                </td>

                                <!-- Account Manager -->
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div :class="[avatarBg(item.accountmanagerfirstname), 'w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0']">
                                            {{ initials(item.accountmanagerfirstname, item.accountmanagerlastname) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 text-xs">{{ item.accountmanagerfirstname }} {{ item.accountmanagerlastname }}</p>
                                            <p class="text-xs text-gray-400">{{ item.accountmanageremail }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Type -->
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                        {{ item.clienttype || '—' }}
                                    </span>
                                </td>

                                <!-- Current Stage -->
                                <td class="px-5 py-4">
                                    <template v-if="latestStage(item)">
                                        <span :class="['inline-flex px-2 py-0.5 rounded-full text-xs font-semibold', stageBadgeClass(latestStage(item).project_stage_name)]">
                                            {{ latestStage(item).project_stage_name }}
                                        </span>
                                    </template>
                                    <span v-else class="text-gray-300 text-xs">No stage</span>
                                </td>

                                <!-- Main Contact -->
                                <td class="px-5 py-4">
                                    <p class="text-xs font-medium text-gray-700">{{ item.main_contact_person_name || '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ item.main_contact_person_email }}</p>
                                    <p class="text-xs text-gray-400">{{ item.main_contact_person_phone }}</p>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 text-right">
                                    <Link
                                        :href="route('accounts.account', { id: item.id })"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:text-primary-dark transition-colors"
                                    >
                                        View
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

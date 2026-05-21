<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import { Icon } from "@iconify/vue";

defineProps({
    accounts: Object,
    titlename: String,
});

function safeParseJSON(str) {
    if (!str) return [];
    try {
        return typeof str === 'string' ? JSON.parse(str) : str;
    } catch(e) {
        return [];
    }
}
</script>

<template>
    <AppLayout title="Business Account Details">
        <PageHeader :title="titlename || 'Business Details'" name="Business Details" />

        <div class="space-y-6">
            <div v-for="item in accounts" :key="item.id" class="space-y-6">
                
                <!-- Main Grid: AM Card & General Client Info -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Account Manager Card -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-card p-6 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-xl bg-primary-light text-primary flex items-center justify-center mb-4">
                            <Icon icon="material-symbols:account-circle-outline" class="h-8 w-8" />
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider mb-4">Account Manager</h3>
                        
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-primary to-indigo-500 flex items-center justify-center text-white text-lg font-black shadow-md mb-4">
                            {{ (item.accountmanagerfirstname?.[0] ?? 'A').toUpperCase() }}{{ (item.accountmanagerlastname?.[0] ?? 'M').toUpperCase() }}
                        </div>
                        
                        <h4 class="text-sm font-bold text-gray-900">{{ item.accountmanagerfirstname }} {{ item.accountmanagerlastname }}</h4>
                        <p class="text-xs text-gray-500 font-semibold mt-1">{{ item.accountmanageremail }}</p>
                        <p class="text-[10px] text-gray-400 font-bold mt-0.5">{{ item.accountmanagerphone || 'No phone' }}</p>
                    </div>

                    <!-- Client General Details -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200/80 shadow-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="badge badge-emerald font-extrabold text-[10px]">
                                    STAGE: {{ item.project_stage_information?.[item.project_stage_information.length - 1]?.project_stage_name }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-black text-primary hover:underline mb-4">
                                <Link :href="route('dashboard')">{{ item.business_name }}</Link>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-semibold text-gray-600">
                                <div class="space-y-2">
                                    <p class="flex items-center gap-2">
                                        <Icon icon="material-symbols:domain" class="h-4 w-4 text-gray-400" />
                                        <span>Company: <strong class="text-gray-900">{{ item.clientname }}</strong></span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <Icon icon="material-symbols:mail-outline" class="h-4 w-4 text-gray-400" />
                                        <span>Email: <strong class="text-gray-900">{{ item.clientemail }}</strong></span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        <Icon icon="material-symbols:call-outline" class="h-4 w-4 text-gray-400" />
                                        <span>Phone: <strong class="text-gray-900">{{ item.clientphone }}</strong></span>
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <p class="flex items-center gap-2">
                                        <Icon icon="material-symbols:location-on-outline" class="h-4 w-4 text-gray-400" />
                                        <span>HQ Location: <strong class="text-gray-900">{{ item.clientlocation }}</strong></span>
                                    </p>
                                    <p v-if="item.clientwebsiteurl" class="flex items-center gap-2">
                                        <Icon icon="material-symbols:language" class="h-4 w-4 text-gray-400" />
                                        <span>Website: <a :href="item.clientwebsiteurl" target="_blank" class="text-primary hover:underline">{{ item.clientwebsiteurl }}</a></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacts Lists Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Account Contacts -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-card p-6">
                        <h4 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider mb-4">Account Contact Persons</h4>
                        <div class="space-y-3">
                            <div v-for="(it, index) in safeParseJSON(item.contact_information)" :key="index"
                                 class="flex items-start gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                                <div class="w-8 h-8 rounded-full bg-primary-light text-primary flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                    {{ it.name?.[0]?.toUpperCase() || 'C' }}
                                </div>
                                <div class="text-[11px] font-semibold text-gray-600 space-y-0.5">
                                    <p class="font-bold text-gray-900 text-xs">{{ it.name }}</p>
                                    <p class="text-gray-500">{{ it.designation }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold">{{ it.email }} | {{ it.phone }}</p>
                                </div>
                            </div>
                            <div v-if="safeParseJSON(item.contact_information).length === 0" class="text-center py-6 text-gray-400 font-semibold text-xs">
                                No contact persons registered for this business account.
                            </div>
                        </div>
                    </div>

                    <!-- Client Contacts -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-card p-6">
                        <h4 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider mb-4">Client Main Contact Persons</h4>
                        <div class="space-y-3">
                            <div v-for="(it, index) in safeParseJSON(item.mainclienccontactinformation)" :key="index"
                                 class="flex items-start gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50">
                                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0 text-xs font-bold border border-amber-100">
                                    {{ it.name?.[0]?.toUpperCase() || 'C' }}
                                </div>
                                <div class="text-[11px] font-semibold text-gray-600 space-y-0.5">
                                    <p class="font-bold text-gray-900 text-xs">{{ it.name }}</p>
                                    <p class="text-gray-500">{{ it.designation }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold">{{ it.email }} | {{ it.phone }}</p>
                                </div>
                            </div>
                            <div v-if="safeParseJSON(item.mainclienccontactinformation).length === 0" class="text-center py-6 text-gray-400 font-semibold text-xs">
                                No client main contacts registered.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution & Stages details -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Solution Specs -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-card p-6 space-y-4">
                        <h4 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider">Solution Specifications</h4>
                        
                        <div class="space-y-3 text-xs font-semibold text-gray-600">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Solution Target</span>
                                <span class="text-gray-900 font-bold text-sm">{{ item.solution_name }}</span>
                            </div>
                            <hr class="border-gray-100" />
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Solution Category</span>
                                <span class="text-gray-900 font-bold">{{ item.solution_type_name }}</span>
                            </div>
                            <hr class="border-gray-100" />
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Solution Sub Type</span>
                                <span class="text-gray-900 font-bold">{{ item.solution_sub_type_name || '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stage Logs -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200/80 shadow-card p-6">
                        <h4 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider mb-6">Pipeline Stage History</h4>
                        
                        <div class="relative pl-6 border-l border-gray-200 space-y-6">
                            <div v-for="(pitem, index) in item.project_stage_information" :key="pitem.id"
                                 class="relative">
                                
                                <!-- Bullet index -->
                                <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-primary text-white text-[8px] font-black flex items-center justify-center shadow-xs">
                                    {{ index + 1 }}
                                </div>

                                <div class="space-y-1.5 text-xs">
                                    <p class="font-bold text-gray-900 text-sm leading-none">{{ pitem.project_stage_name }}</p>
                                    <p class="text-gray-500 font-semibold leading-relaxed">{{ pitem.stage_information || 'No notes left for this stage.' }}</p>
                                    
                                    <!-- Parsed metadata grid -->
                                    <div class="mt-2.5 grid grid-cols-1 md:grid-cols-2 gap-2 max-w-lg">
                                        <div v-for="(meta, mIdx) in safeParseJSON(pitem.stage_meta)" :key="mIdx"
                                             class="p-2 rounded bg-gray-50 border border-gray-100 text-[10px]">
                                            <span v-if="meta.key !== 'Documents'">
                                                <strong class="text-gray-500 block uppercase tracking-wider text-[8px] mb-0.5">{{ meta.key.replace(/_/g, ' ') }}:</strong>
                                                <span class="font-bold text-gray-800">{{ meta.value }}</span>
                                            </span>
                                            <span v-else>
                                                <strong class="text-gray-500 block uppercase tracking-wider text-[8px] mb-0.5">Documents:</strong>
                                                <span v-for="(doc, dIdx) in meta.value" :key="dIdx" class="inline-flex gap-1 mr-2 mt-0.5">
                                                    <a :href="doc.value" target="_blank" class="text-primary font-bold hover:underline">{{ doc.key }}</a>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

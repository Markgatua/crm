<script setup>
defineProps({
    farm: {type: Object},
    fodder: Object,
    equipment: Object,
});
</script>

<template>
    <section class="w-full">
        <div class="h-auto w-full flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Owner's Name & Contact Details
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="w-full">
                        Farm Name: {{ farm.farm_name }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Farm Owner: {{ farm.farm_owner }}
                    </div>
                </div>
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Email: {{ farm.email }}
                    </div>
                    <div class="w-full">
                        Telephone: {{ farm.telephone }}
                    </div>
                </div>

            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Location Details
            </div>
            <div class="flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border-r border-green-800  w-full">
                        County: {{ farm.county }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Area: {{ farm.area }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Vision of the Farm's Owner
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                {{ farm.vision }}
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Main Breeds
            </div>
            <div
                class="border border-green-800 flex  flex-row w-full text-sm font-medium leading-6 text-gray-900">
                <span class="truncate font-medium">{{ farm.main_breeds.replace(/\[|\]|\"/g, "") }}</span>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Herd numbers by category.
            </div>
            <div class="flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Calves under milk-feeding: {{ farm.milk_feeding_calves }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Weaned calves (early weaners): {{ farm.early_weaned_calves }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Weaned Calves (Late weaners): {{ farm.late_weaned_calves }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Early lactation group: {{ farm.early_lactation }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Mid lactation group: {{ farm.mid_lactation }}
                    </div>
                </div>
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Late lactation group: {{ farm.late_lactation }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Yearlings: {{ farm.yearlings }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Served Heifers: {{ farm.served_heifers }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Dry Cows: {{ farm.dry_cows }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Bulls: {{ farm.bulls }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Bullying Heifers: {{ farm.bullying_heifers }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Milk Production per day (in liters) & daily Utilization
            </div>
            <div class=" flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Total milk produced (as of yesterday): {{ farm.total_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Sold: {{ farm.sold_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Fed to Calves: {{ farm.calves_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Given to workers: {{ farm.workers_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Discarded: {{ farm.discarded_milk }}
                    </div>
                </div>
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Spilled: {{ farm.spilled_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Home use/owner’s use: {{ farm.home_use_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Processed at the farm: {{ farm.processed_milk }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Other: {{ farm.other_milk }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Form in which Milk is Sold
            </div>
            <div class="flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border border-green-800 flex w-full">
                        <span>{{ farm.milk_sold_form.replace(/\[|\]|\"/g, "") }}  </span>
                    </div>
                    <div class="border border-green-800  w-full">
                        Others: {{ farm.other_milk_sold_form }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Methods of farming
            </div>
            <div class=" flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div v-if="farm.farming_method==='zero'" class="border border-green-800 flex w-full">
                        Intensive farming (zero-grazing) in the barns
                    </div>
                    <div v-else-if="farm.farming_method==='free'" class="border border-green-800 flex w-full">
                        Extensive farming (free grazing) in the pastures
                    </div>
                    <div v-else-if="farm.farming_method==='semi'" class="border border-green-800 flex w-full">
                        Semi-Intensive farming (pasture grazing with supplementation and some
                        (permanent) cow housing structures)
                    </div>
                    <div v-else class="border border-green-800 flex w-full">
                        No data!
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Total land devoted to the dairy enterprise.
            </div>
            <div class="flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border border-green-800 flex w-full">
                        Owned Fodder Land: {{ farm.owned_land }}
                    </div>
                    <div class="border border-green-800 flex w-full">
                        Leased Fodder Land: {{ farm.leased_land }}
                    </div>
                    <div class="border border-green-800 flex w-full">
                        Acreage devoted to dairy structures: {{ farm.structure_land }}
                    </div>
                </div>

            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Fodder types used for
                supplementation (other than
                pastures) and acreages allocated
            </div>
            <div class="flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex" v-for="item in fodder">
                    <div class="border border-green-800 flex w-full">
                        {{ item.type }}
                    </div>
                    <div class="border border-green-800 flex w-full">
                        {{ item.acreage }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Amount of Fodder Preserved
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="border border-green-800  w-full">
                        Maize Sillage: {{ farm.maize_sillage }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Other Sillage: {{ farm.other_sillage }}
                    </div>
                    <div class="border border-green-800  w-full">
                        Hay: {{ farm.hay }}
                    </div>
                </div>
                <div class="flex">
                    <div class=" w-full">
                        Concentrates: {{ farm.concentrates }}
                    </div>
                    <div class="border-l border-green-800  w-full">
                        Dairy Meal: {{ farm.dairy_meal }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Other major farming activities besides dairy
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class=" flex w-full">
                        {{ farm.enterprises }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Number of Workers.
            </div>
            <div
                class="border border-green-800 flex flex-row w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex flex-1">
                    <div class="flex w-full">
                        Casual Workers: {{ farm.casual_workers }}
                    </div>
                </div>
                <div class="flex flex-1">
                    <div class="border-l border-green-800 flex w-full">
                        Permanent Workers: {{ farm.permanent_workers }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Owner Involvement
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="flex w-full">
                        {{ farm.owner_involvement }}

                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Type & number of Equipment
                /Machinery owned
            </div>
            <div class=" flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex" v-for="item in equipment">
                    <div class="border border-green-800 flex w-full">
                        {{ item.name }}
                    </div>
                    <div class="border border-green-800 flex w-full">
                        {{ item.unit }}
                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Methods of Recording.
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="flex w-full">
                        {{ farm.recording_method }}

                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Education/ Training
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class=" flex w-full">
                        {{ farm.education_level }}

                    </div>
                    <div class=" flex w-full">
                        {{ farm.education_level_specify }}

                    </div>
                </div>
            </div>
        </div>
        <div class=" w-full h-auto flex">
            <div class="border border-green-800 w-52 font-bold text-sm text-slate-950">
                Other Additional Details
            </div>
            <div
                class="border border-green-800 flex flex-col w-full text-sm font-medium leading-6 text-gray-900">
                <div class="flex">
                    <div class="flex w-full">
                        {{ farm.other_farm_details }}
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>




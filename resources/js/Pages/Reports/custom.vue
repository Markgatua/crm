<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps, ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import CustomMultiSelect from '@/Components/Form/CustomMultiSelect.vue';
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Icon } from "@iconify/vue";
import * as XLSX from "xlsx";

const props = defineProps({
  accountmanagers: Object,
  solutions: Object,
  stages: Object,
  solutiontypes: Object,
  solutionsubtypes: Object,
  sectors: Object,
});

const form = useForm({
  accountmanager: null,
  solution_type_id: null,
  solution_id: null,
  solution_subtype_id: null,
  stages: [],
  startdate: "",
  enddate: "",
  sector_id: null,
});

const showSolutionField = computed(() => form.solution_type_id !== null);
const showSolutionSubtypeField = computed(
  () => form.solution_id !== null && form.solution_type_id !== null
);

const filteredSolutions = computed(() =>
  props.solutions.filter(
    (solution) => solution.solution_type_id == form.solution_type_id
  )
);
const filteredSolutionSubtypes = computed(() =>
  props.solutionsubtypes.filter(
    (subtype) => subtype.solution_id == form.solution_id
  )
);

// Watchers to reset dependent fields
watch(
  () => form.solution_type_id,
  (newVal) => {
    if (newVal === null) {
      form.solution_id = null;
      form.solution_subtype_id = null;
    } else {
      form.solution_id = null; // Reset solution and solution subtype when solution type changes
      form.solution_subtype_id = null;
    }
  }
);

watch(
  () => form.solution_id,
  (newVal) => {
    if (newVal === null) {
      form.solution_subtype_id = null;
    }
  }
);

const today = new Date().toISOString().split("T")[0];

const save = () => {
  if (!form.startdate) {
    form.startdate = "2024-06-01";
  }
  if (!form.enddate) {
    form.enddate = today;
  }

  form.post(route("reports.custom.create"), {
    data: {
      ...form.data(),
      start_date: form.startdate,
      end_date: form.enddate,
    },
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: (e) => {
      alert(e);
    },
  });
};
</script>

<template>
  <AppLayout title="Custom Report Builder">
    <PageHeader title="Custom Report Builder" name="Custom Report" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Info banner -->
      <div class="lg:col-span-1 space-y-4">
        <div class="bg-gradient-to-br from-primary-light/40 to-blue-50/20 border border-primary/10 rounded-xl p-5 shadow-card">
          <div class="flex items-center gap-2 mb-3">
            <span class="p-1 rounded-lg bg-primary-light text-primary">
              <Icon icon="material-symbols:info-outline" class="h-5 w-5" />
            </span>
            <h3 class="font-extrabold text-gray-900 text-xs uppercase tracking-wider">Report Guidelines</h3>
          </div>
          <ul class="space-y-2 text-xs font-semibold text-gray-600 pl-1">
            <li class="flex gap-2">
              <span class="text-primary mt-0.5">•</span>
              <span>Leave <strong class="text-gray-900">Account Manager</strong> empty to aggregate performance across all active representatives.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-primary mt-0.5">•</span>
              <span>Leave <strong class="text-gray-900">Solution Category</strong> empty to include all active categories and solutions.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-primary mt-0.5">•</span>
              <span>Leave <strong class="text-gray-900">Account Stages</strong> unselected to capture every deal state sequentially.</span>
            </li>
            <li class="flex gap-2">
              <span class="text-primary mt-0.5">•</span>
              <span>If not set, duration defaults from <strong class="text-primary">June 01, 2024</strong> up to <strong class="text-primary">Today</strong>.</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Config panel card -->
      <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200/80 shadow-card p-6">
        <h3 class="text-sm font-bold text-gray-900 mb-5">Configure Criteria</h3>

        <form @submit.prevent="save" class="space-y-4">
          <!-- Row 1: Solution type, solution, subtype -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <InputLabel value="Solution Category" class="mb-1.5" />
              <select v-model="form.solution_type_id"
                class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                <option :value="null">All Categories</option>
                <option v-for="type in props.solutiontypes" :key="type.id" :value="type.id">
                  {{ type.solution_type_name }}
                </option>
              </select>
            </div>

            <div>
              <InputLabel value="Specific Solution" class="mb-1.5" />
              <select v-model="form.solution_id" :disabled="!showSolutionField"
                class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all disabled:bg-gray-50 disabled:text-gray-400">
                <option :value="null">All Solutions</option>
                <option v-for="sol in filteredSolutions" :key="sol.id" :value="sol.id">
                  {{ sol.solution_name }}
                </option>
              </select>
            </div>

            <div>
              <InputLabel value="Solution Subtype" class="mb-1.5" />
              <select v-model="form.solution_subtype_id" :disabled="!showSolutionSubtypeField"
                class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all disabled:bg-gray-50 disabled:text-gray-400">
                <option :value="null">All Subtypes</option>
                <option v-for="sub in filteredSolutionSubtypes" :key="sub.id" :value="sub.id">
                  {{ sub.solution_sub_type_name }}
                </option>
              </select>
            </div>
          </div>

          <!-- Row 2: Account Manager, Sector -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <InputLabel value="Account Manager" class="mb-1.5" />
              <select v-model="form.accountmanager"
                class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                <option :value="null">All Managers</option>
                <option v-for="manager in props.accountmanagers" :key="manager.id" :value="manager.id">
                  {{ manager.first_name }} {{ manager.last_name }}
                </option>
              </select>
            </div>

            <div>
              <InputLabel value="Sector" class="mb-1.5" />
              <select v-model="form.sector_id"
                class="w-full border-gray-200 focus:border-primary focus:ring-primary/10 rounded-lg text-xs py-2.5 transition-all">
                <option :value="null">All Sectors</option>
                <option v-for="sec in props.sectors" :key="sec.id" :value="sec.id">
                  {{ sec.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- Row 3: Date range picker -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <InputLabel value="Start Date" class="mb-1.5" />
              <TextInput type="date" v-model="form.startdate" class="w-full text-xs" />
            </div>

            <div>
              <InputLabel value="End Date" class="mb-1.5" />
              <TextInput type="date" v-model="form.enddate" class="w-full text-xs" />
            </div>
          </div>

          <!-- Multi-select stages -->
          <div class="pt-2">
            <InputLabel value="Pipeline Stages filter" class="mb-1.5" />
            <CustomMultiSelect v-model="form.stages" :options="props.stages" />
          </div>

          <!-- Actions -->
          <div class="flex justify-end pt-4 border-t border-gray-100">
            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              <div class="flex items-center gap-2">
                <Icon icon="material-symbols:analytics-outline" class="h-4 w-4" />
                Generate Custom Report
              </div>
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

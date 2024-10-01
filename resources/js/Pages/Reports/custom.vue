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

const formatDate = (date) => {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0"); // Months are 0-based
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
};

const today = new Date().toISOString().split("T")[0];

const save = () => {
  if (!form.startdate) {
    form.startdate = "2024-06-01";
  }
  if (!form.enddate) {
    form.enddate = today;
  }
  

 
  const formattedStartDate = form.startdate;
  const formattedEndDate = form.enddate;

  form.startdate = formattedStartDate;
  form.enddate = formattedEndDate;
  // Include formatted dates in the form data
  form.post(route("reports.custom.create"), {
    data: {
      ...form.data(),
      start_date: formattedStartDate,
      end_date: formattedEndDate,
    },
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // location.reload();
    },
    onError: (e) => {
      alert(e);
      toast.add(e);
    },
  });
};

function printDiv() {
  const divToPrint = document.querySelector(".overflow-x-auto").innerHTML;
  const newWindow = window.open("", "", "height=600,width=800");
  newWindow.document.write("<html><head><title>Xtranet Accounts</title>");
  newWindow.document.write(
    "<style>body { font-family: Arial, sans-serif; margin: 20px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }</style>"
  ); // You can adjust or add styles here
  newWindow.document.write("</head><body >");
  newWindow.document.write(divToPrint);
  newWindow.document.write("</body></html>");
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

<template>
  <AppLayout title="Custom Report">
    <PageHeader title="Custom Report" name="Custom Report" />
    <section class="bg-white-50">
      <div class="max-w-screen-xl pt-8 lg:px-10">
        <SecondaryButton @click="printDiv" class="inline-flex gap-2"><Icon icon="material-symbols-light:print-outline" class="h-6 w-6" />Print</SecondaryButton>
        <div class=" py-4 mb-10">
        <ul class="list-disc pl-5 space-y-2 text-gray-700 bg-blue-50 p-4 rounded-lg shadow-md">
          <li class="hover:text-blue-600 transition-colors duration-200">
            To generate a report for all Account Managers, leave the select box empty.
          </li>
          <li class="hover:text-blue-600 transition-colors duration-200">
            To generate a report with all solutions, leave the Solution Category box empty.
          </li>
          <li class="hover:text-blue-600 transition-colors duration-200">
            To generate a report for all Stages, leave the Stages box empty.
          </li>
        </ul> 
          <div class="flex flex-wrap space-x-4">
            <!-- Solution Category -->
            <div class="mt-4 flex-1">
              <label for="">Select Solution Category</label>
              <select
                v-model="form.solution_type_id"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none"
              >
                <option value="" disabled>Select Solution Category</option>
                <option
                  v-for="solutiontype in props.solutiontypes"
                  :key="solutiontype.id"
                  :value="solutiontype.id"
                >
                  {{ solutiontype.solution_type_name }}
                </option>
              </select>
              <InputError
                :message="form.errors.solution_type_id"
                class="mt-2"
              />
            </div>

            <!-- Solution -->
            <div class="mt-4 flex-1" v-if="showSolutionField">
              <label for="">Select Solution</label>
              <select
                v-model="form.solution_id"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none"
              >
                <option value="" disabled>Select Solution</option>
                <option
                  v-for="solution in filteredSolutions"
                  :key="solution.id"
                  :value="solution.id"
                >
                  {{ solution.solution_name }}
                </option>
              </select>
              <InputError :message="form.errors.solution_id" class="mt-2" />
            </div>

            <!-- Solution Subtype -->
            <div class="mt-4 flex-1" v-if="showSolutionSubtypeField">
              <label for="">Select Solution Subtype</label>
              <select
                v-model="form.solution_subtype_id"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none"
              >
                <option value="" disabled>Select Solution Subtype</option>
                <option
                  v-for="subtype in filteredSolutionSubtypes"
                  :key="subtype.id"
                  :value="subtype.id"
                >
                  {{ subtype.solution_sub_type_name }}
                </option>
              </select>
              <InputError
                :message="form.errors.solution_subtype_id"
                class="mt-2"
              />
            </div>
          </div>
          <div class="flex flex-wrap space-x-4">
            <!-- Solution Category -->
            <div class="mt-4 flex-1">
              <label for="">Select Account Manager</label>
              <select
                v-model="form.accountmanager"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none"
              >
                <option value="" disabled>Select Account Manager</option>
                <option
                  v-for="accountmanager in props.accountmanagers"
                  :key="accountmanager.id"
                  :value="accountmanager.id"
                >
                  {{ accountmanager.first_name }} {{ accountmanager.last_name }}
                </option>
              </select>
              <InputError :message="form.errors.accountmanager" class="mt-2" />
            </div>

            <div class="mt-4 flex-1">
              <label for="start_date">Start Date</label>
              <TextInput
                id="start_date"
                v-model="form.startdate"
                type="date"
                class="mt-1 block w-full"
              />
            </div>
            <div class="mt-4 flex-1">
              <label for="end_date">End Date</label>
              <TextInput
                id="end_date"
                v-model="form.enddate"
                type="date"
                class="mt-1 block w-full"
              />
            </div>
          </div>
          <div class="grid grid-cols-3 gap-2 items-start">
            <div class="mt-4 w-full col-span-2">
             <InputLabel value="Account Stages"/>
              <CustomMultiSelect class="mt-1" v-model="form.stages" :options="props.stages" />
              <InputError
                :message="form.errors.stage"
                class="mt-2 text-red-600"
              />
            </div>
            <div class="mt-4 w-full col-span-1">
              <InputLabel value="Select sector"/>
              <select
                v-model="form.sector_id"
                class="w-full rounded border border-stroke bg-gray py-3 px-4.5 font-normal text-black focus:border-primary focus-visible:outline-none"
              >
                <option value="" disabled>Select Sector</option>
                <option
                  v-for="sector in props.sectors"
                  :key="sector.id"
                  :value="sector.id"
                >
                  {{ sector.name }}
                </option>
              </select>
              <InputError :message="form.errors.sector" class="mt-2" />
            </div>
          </div>
          <div class="mt-10 flex items-start justify-start">
            <PrimaryButton
              class=""
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click.prevent="save"
            >
              Generate Report
            </PrimaryButton>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>



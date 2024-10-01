<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import MultiSelect from "primevue/multiselect";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DatePicker from "primevue/datepicker";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Icon } from "@iconify/vue";
import PageHeader from "@/CustomComponents/PageHeader.vue";
import CustomMultiSelect from "@/Components/Form/CustomMultiSelect.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/Form/SelectInput.vue";

const props = defineProps({
  accountmanagers: Object,
  solutions: Object,
  stages: Object,
  solutiontypes: Object,
  solutionsubtypes: Object,
  sectors: Object,
});
const form = useForm({
  accountmanager: props.accountmanagers,
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
// const formatDate = (date) => {
//     console.log(date);

//     const year = date.getFullYear();
//     const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
//     const day = String(date.getDate()).padStart(2, '0');
//     return `${year}-${month}-${day}`;
// };
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
</script>

<template>
  <AppLayout title="My Custom Report">
    <PageHeader title="My Custom Report" name="My Custom Report" />

    <section class="bg-white-50">
      <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
        <div
          class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 py-4"
        >
          <div class="w-full md:w-1/2">
            <SecondaryButton @click="printDiv" class="inline-flex gap-2"
              ><Icon
                icon="material-symbols-light:print-outline"
                class="h-6 w-6"
              />Print</SecondaryButton
            >
          </div>
          <div
            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
          >
            <!-- Add other buttons or actions here if needed -->
          </div>
        </div>

        <div class="pb-6 mb-10">
          <ul
            class="list-disc pl-5 space-y-2 text-gray-700 bg-blue-50 p-4 rounded-lg shadow-md"
          >
            <li class="hover:text-blue-600 transition-colors duration-200">
              To generate a report for all Account Managers, leave the select
              box empty.
            </li>
            <li class="hover:text-blue-600 transition-colors duration-200">
              To generate a report with all solutions, leave the Solution
              Category box empty.
            </li>
            <li class="hover:text-blue-600 transition-colors duration-200">
              To generate a report for all Stages, leave the Stages box empty.
            </li>
          </ul>

          <div class="flex flex-wrap space-x-4">
            <!-- Solution Category -->
            <div class="mt-4 flex-1">
              <label for="">Select Solution Category</label>
              <SelectInput v-model="form.solution_type_id">
                <option value="" disabled>Select Solution Category</option>
                <option
                  v-for="solutiontype in props.solutiontypes"
                  :key="solutiontype.id"
                  :value="solutiontype.id"
                >
                  {{ solutiontype.solution_type_name }}
                </option>
              </SelectInput>
              <InputError
                :message="form.errors.solution_type_id"
                class="mt-2"
              />
            </div>
            <!-- Solution -->
            <div class="mt-4 flex-1" v-if="showSolutionField">
              <label for="">Select Solution</label>
              <SelectInput v-model="form.solution_id">
                <option value="" disabled>Select Solution</option>
                <option
                  v-for="solution in filteredSolutions"
                  :key="solution.id"
                  :value="solution.id"
                >
                  {{ solution.solution_name }}
                </option>
              </SelectInput>
              <InputError :message="form.errors.solution_id" class="mt-2" />
            </div>
            <!-- Solution Subtype -->
            <div class="mt-4 flex-1" v-if="showSolutionSubtypeField">
              <label for="">Select Solution Subtype</label>
              <SelectInput v-model="form.solution_subtype_id">
                <option value="" disabled>Select Solution Subtype</option>
                <option
                  v-for="subtype in filteredSolutionSubtypes"
                  :key="subtype.id"
                  :value="subtype.id"
                >
                  {{ subtype.solution_sub_type_name }}
                </option>
              </SelectInput>
              <InputError
                :message="form.errors.solution_subtype_id"
                class="mt-2"
              />
            </div>
          </div>
          <div class="flex flex-wrap space-x-4">
            <!-- Solution Category -->
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
            <div class="mt-4 flex-1">
              <label for="">Select Sectors</label>
              <SelectInput v-model="form.sector_id">
                <option value="" disabled>Select Sectors</option>
                <option
                  v-for="sector in props.sectors"
                  :key="sector.id"
                  :value="sector.id"
                >
                  {{ sector.name }}
                </option>
              </SelectInput>
              <InputError :message="form.errors.sector" class="mt-2" />
            </div>
          </div>
          <div class="mt-4 w-full col-span-2">
            <InputLabel value="Account Stages" />
            <CustomMultiSelect
              class="mt-1"
              v-model="form.stages"
              :options="props.stages"
            />
            <InputError
              :message="form.errors.stage"
              class="mt-2 text-red-600"
            />
          </div>
          <div class="mt-4 flex-2">
            <PrimaryButton
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

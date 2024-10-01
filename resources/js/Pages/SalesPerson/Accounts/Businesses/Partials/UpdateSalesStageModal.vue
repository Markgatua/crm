    <script setup>
    import AppLayout from "@/Layouts/AppLayout.vue";
    import { ref, computed, watch } from "vue";
    import { useForm, Link } from "@inertiajs/vue3";
    import PrimaryButton from "@/Components/PrimaryButton.vue";
    import DangerButton from "@/Components/DangerButton.vue";
    import WarningButton from "@/Components/WarningButton.vue";
    import SecondaryButton from "@/Components/SecondaryButton.vue";
    import SelectInput from "@/Components/Form/SelectInput.vue";
    
    import InputError from "@/Components/InputError.vue";
    import DialogModal from "@/Components/DialogModal.vue";
    import TextInput from "@/Components/TextInput.vue";
    import toast from "@/Stores/toast.js";
    import DatePicker from "primevue/datepicker";
    import { MdDelete } from "vue3-icons/md";
    
    const props = defineProps({
      client: Object,
      clients: Array,
      clienttypes: Array,
      projectstages: Array,
      solutiontypes: Array,
      solutions: Array,
      solutionsubtypes: Array,
      presales: Array,
      accounts: Array,
      passtitle: Array,
    });
    
    const filters = ref({
      global: "",
    });
    
    const clients = ref(props.accounts);
    

    
    const editItem = ref(null);
    
    const form = useForm({
      solution_name: "",
      client_type_id: -1,
      project_stage_id: -1,
      solution_type_id: null,
      solution_id: null,
      solution_subtype_id: null,
      useaccountcontacts: false,
      contact_information: [],
      client_id: props.accounts.client_id,
      next_actions: "",
      expected_sale_value: "",
      expected_month_of_closure: "",
      projected_month_of_closure: "",
      project_delivery_date: "",
      margins_projection: "",
      deal_amount: "",
      probability_of_closure: -1,
      reason_for_losing: "",
      reason_for_overdue: "",
      overdue_next_steps: "",
      presale_assigned: -1,
      documents: [],
      account_id: "",
      site_survey_comments: "",
      site_survey_due_date: "",
      contract_name: "",
      contract_number: "",
      contract_validity: "",
      contract_start_date: "",
      contract_end_date: "",
      contract_license: "",
      contract_license_type: "",
      contract_license_renewal_validity: "",
      contract_license_renewal_start_date: "",
      contract_license_renewal_end_date: "",
      pushToPresales: false,
      presale_actions: "",
      presale_type: null,
      presale_options: [],
      populatePresaleOptions() {
        switch (this.presale_type) {
          case "1": // Technical Meeting
            this.presale_options = [
              {key: 'date', type: 'date', value: ''},
              {key: 'time', type: 'time', value: ''},
              {key: 'venue', type: 'text', value: ''},
              {key: 'guests', type: 'array', value: []},
              {key: 'furtherInstructions', type: 'text', value: ''}
            ];
            break;
          case "2": // Tenders
            this.presale_options = [
              {key: 'instructions', type: 'text', value: ''},
              {key: 'tenderRef', type: 'text', value: ''},
              {key: 'tenderNotice', type: 'text', value: ''},
              {key: 'tenderClosingDate', type: 'date', value: ''},
              {key: 'tenderValue', type: 'number', value: ''}
            ];
            break;
          case "3": // Others
            this.presale_options = [
              {key: 'instructions', type: 'text', value: ''}
            ];
            break;
          default:
            this.presale_options = [];
        }
      },
    });

    
    const addDocuments = () => {
      form.documents.push({
        id: Math.random(),
        name: "",
        file: null,
      });
    };
    
    const addDocuments2 = () => {
      editForm.documents.push({
        id: Math.random(),
        name: "",
        file: null,
      });
    };
    
    const handleFileChange = (file, index) => {
      form.documents[index].file = file;
    };
    
    const removeDocument = (document) => {
      form.documents = form.documents.filter((x) => x.id != document.id);
    };
    
    const handleFileChange2 = (file, index) => {
      editForm.documents[index].file = file;
    };
    
    const removeDocument2 = (document) => {
      editForm.documents = editForm.documents.filter((x) => x.id != document.id);
    };
  
    
    const closeAddModal = () => {
      addingModal.value = false;
      form.reset();
    };
    
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
    
    const showSolutionCategoryX = computed(() => form.project_stage_id == 2);
    const showSolutionFieldX = computed(
      () => form.solution_type_id !== null && form.project_stage_id == 2
    );
    const showSolutionSubtypeFieldX = computed(
      () =>
        form.solution_id !== null &&
        form.project_stage_id == 2 &&
        props.solutionsubtypes.some(
          (subtype) => subtype.solution_id == form.solution_id
        )
    );
    
    const showLicenseX = computed(() => form.project_stage_id == 10);
    const showLicenseTypeX = computed(
      () => form.contract_license === "Yes" && form.project_stage_id == 10
    );
    
    
    watch(
      () => form.project_stage_id,
      (newStage) => {
        if (newStage === 1 || newStage === 2 || newStage === 3) {
          form.next_actions = "";
          form.expected_month_of_closure = "";
          form.expected_sale_value = "";
        }
      }
    );
    const updateStageModal = ref(false);
    
    
    const moveStage = () => {
      form.post(
        route("sales_person.accounts.businesses.update_stage", {
          id: editItem.value.id,
        }),
        {
          preserveScroll: true,
          onSuccess: () => {
            closeUpdateStageModal();
            form.reset();
            updateForm.reset();
            location.reload();
          },
          onError: (e) => {
            toast.add(e);
          },
        }
      );
    };

    </script>
    
    
    <template>
    <DialogModal :show="show" @close="$emit('close')">
      <template #title> Update Account Stage </template>
      <template #content>
        <div class="mt-4">
          <label for="">Select Stage</label>
          <SelectInput
            v-model="form.project_stage_id"
           
          >
            <option value="" :disabled="true">Select stage</option>
            <option
              v-for="projectstage in projectstages"
              :key="projectstage.id"
              :value="projectstage.id"
            >
              {{ projectstage.name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.client_type_id" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 2">
          <TextInput
            v-model="form.solution_name"
            type="text"
            class="mt-1 block w-full"
            placeholder="Business Name (How you Love it)"
          />
          <InputError :message="form.errors.solution_name" class="mt-2" />
        </div>

        <!-- <TextInput v-model="form.account_id" value={{ slotProps.data.id }} /> -->

        <div class="mt-4" v-if="showSolutionCategoryX">
          <label for="">Select Solution Category</label>
          <SelectInput
            v-model="form.solution_type_id"
          >
            <option value="" :disabled="true">Select Solution Category</option>
            <option
              v-for="solutiontype in props.solutiontypes"
              :key="solutiontype.id"
              :value="solutiontype.id"
            >
              {{ solutiontype.solution_type_name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.solution_type_id" class="mt-2" />
        </div>

        <div class="mt-4" v-if="showSolutionFieldX">
          <label for="">Select Solution</label>
          <SelectInput
            v-model="form.solution_id"
          >
            <option value="" :disabled="true">Select Solution</option>
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

        <div class="mt-4" v-if="showSolutionSubtypeFieldX">
          <label for="">Select Solution Subtype</label>
          <SelectInput
            v-model="form.solution_subtype_id"
          >
            <option value="" :disabled="true">Select Solution Subtype</option>
            <option
              v-for="subtype in filteredSolutionSubtypes"
              :key="subtype.id"
              :value="subtype.id"
            >
              {{ subtype.solution_sub_type_name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.solution_subtype_id" class="mt-2" />
        </div>

        <div v-if="form.project_stage_id === 2" class="flex-1 ml-2 mt-3">
          <input
            type="checkbox"
            id="pushToPresalesCheckbox"
            v-model="form.pushToPresales"
          />
          <label for="pushToPresalesCheckbox" class="pr-4">
            Push To Presales</label
          >
        </div>
        <div
          class="mt-4"
          v-if="form.project_stage_id === 2 && form.pushToPresales"
        >
          <TextInput
            v-model="form.presale_actions"
            type="text"
            class="mt-1 block w-full"
            placeholder="Pre-Sales Instructions"
          />
          <InputError :message="form.errors.presale_actions" class="mt-2" />
          <div class="mt-4">
            <InputLabel for="presale_type" value="Presale Type" />
            <SelectInput
              id="presale_type"
              class="mt-1 block w-full"
              v-model="form.presale_type"
              @change="form.populatePresaleOptions()"
              required
            >
              <option value="1">Technical Meeting</option>
              <option value="2">Tenders</option>
              <option value="3">Others</option>
            </SelectInput>
            <InputError class="mt-2" :message="form.errors.presale_type" />
          </div>
        </div>
        <div class="mt-4" v-if="form.presale_type">
          <div v-for="option in form.presale_options" :key="option.key" class="mt-2">
            <InputLabel :for="option.key" :value="option.key" />
            <TextInput
              v-if="option.type === 'text' || option.type === 'number'"
              :id="option.key"
              v-model="option.value"
              :type="option.type"
              class="mt-1 block w-full"
              :placeholder="option.key"
              required
            />
            <TextInput
              v-else-if="option.type === 'date'"
              :id="option.key"
              v-model="option.value"
              type="date"
              class="mt-1 block w-full"
              required
            />
            <TextInput
              v-else-if="option.type === 'time'"
              :id="option.key"
              v-model="option.value"
              type="time"
              class="mt-1 block w-full"
              required
            />
            <div v-else-if="option.type === 'array'" class="mt-1">
              <div v-for="(guest, index) in option.value" :key="index" class="flex items-center mt-1">
                <TextInput
                  v-model="option.value[index]"
                  type="text"
                  class="block w-full"
                  :placeholder="`Guest ${index + 1}`"
                  required
                />
                <PrimaryButton @click="option.value.splice(index, 1)" class="ml-2">Remove</PrimaryButton>
              </div>
              <PrimaryButton @click="option.value.push('')" class="mt-1">Add Guest</PrimaryButton>
            </div>
            <InputError :message="form.errors[option.key]" class="mt-2" />
          </div>
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 10">
          <TextInput
            v-model="form.contract_name"
            type="text"
            class="mt-1 block w-full"
            placeholder="Contract Name"
          />
          <InputError :message="form.errors.contract_name" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 10">
          <TextInput
            v-model="form.contract_number"
            type="text"
            class="mt-1 block w-full"
            placeholder="Contract Number"
          />
          <InputError :message="form.errors.contract_number" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 10">
          <TextInput
            v-model="form.contract_validity"
            type="text"
            class="mt-1 block w-full"
            placeholder="Contract Validity (In Months)"
          />
          <InputError :message="form.errors.contract_validity" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 10">
          <TextInput
            v-model="form.contract_start_date"
            type="text"
            class="mt-1 block w-full"
            placeholder="Contract Start Date (dd/mm/yyyy)"
          />
          <InputError :message="form.errors.contract_start_date" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 10">
          <TextInput
            v-model="form.contract_end_date"
            type="text"
            class="mt-1 block w-full"
            placeholder="Contract End Date (dd/mm/yyyy)"
          />
          <InputError :message="form.errors.contract_end_date" class="mt-2" />
        </div>

        <div class="mt-4" v-if="showLicenseX">
          <label for="">Select Contract License</label>
          <SelectInput
            v-model="form.contract_license"
          >
            <option value="" :disabled="true">Select Contract License</option>
            <option value="None">None</option>
            <option value="Yes">Yes</option>
          </SelectInput>
          <InputError :message="form.errors.contract_license" class="mt-2" />
        </div>

        <div class="mt-4" v-if="showLicenseTypeX">
          <label for="">Select License Type</label>
          <SelectInput
            v-model="form.contract_license_type"
          >
            <option value="" :disabled="true">Select License Type</option>
            <option value="Perpetual">Perpetual</option>
            <option value="Renewable">Renewable</option>
          </SelectInput>
          <InputError
            :message="form.errors.contract_license_type"
            class="mt-2"
          />
        </div>

        <div
          class="mt-4"
          v-if="
            form.contract_license_type === 'Renewable' &&
            form.project_stage_id === 10
          "
        >
          <TextInput
            v-model="form.contract_license_renewal_validity"
            type="text"
            class="mt-1 block w-full"
            placeholder="Renewable Contract License Validity (In Months)"
          />
          <InputError
            :message="form.errors.contract_license_renewal_validity"
            class="mt-2"
          />
        </div>

        <div
          class="mt-4"
          v-if="
            form.contract_license_type === 'Renewable' &&
            form.project_stage_id === 10
          "
        >
          <TextInput
            v-model="form.contract_license_renewal_start_date"
            type="text"
            class="mt-1 block w-full"
            placeholder="Renewable Contract License Start Date (dd/mm/yyyy)"
          />
          <InputError
            :message="form.errors.contract_license_renewal_start_date"
            class="mt-2"
          />
        </div>

        <div
          class="mt-4"
          v-if="
            form.contract_license_type === 'Renewable' &&
            form.project_stage_id === 10
          "
        >
          <TextInput
            v-model="form.contract_license_renewal_end_date"
            type="text"
            class="mt-1 block w-full"
            placeholder="Renewable Contract License End Date (dd/mm/yyyy)"
          />
          <InputError
            :message="form.errors.contract_license_renewal_end_date"
            class="mt-2"
          />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 1">
          <TextInput
            v-model="form.next_actions"
            type="text"
            class="mt-1 block w-full"
            placeholder="Next Actions"
          />
          <InputError :message="form.errors.next_actions" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 3">
          <TextInput
            v-model="form.expected_sale_value"
            type="number"
            class="mt-1 block w-full"
            placeholder="Expected Sale Value"
          />
          <InputError :message="form.errors.expected_sale_value" class="mt-2" />

          <TextInput
            v-model="form.projected_month_of_closure"
            type="text"
            class="mt-4 block w-full"
            placeholder="Projected Month of Closure"
          />
          <InputError
            :message="form.errors.projected_month_of_closure"
            class="mt-2"
          />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 5">
          <TextInput
            v-model="form.deal_amount"
            type="number"
            class="mt-1 block w-full"
            placeholder="Deal Amount"
          />
          <InputError :message="form.errors.deal_amount" class="mt-2" />

          <TextInput
            v-model="form.margins_projection"
            type="text"
            class="mt-4 block w-full"
            placeholder="Margins Projection"
          />
          <InputError :message="form.errors.margins_projection" class="mt-2" />

          <TextInput
            v-model="form.project_delivery_date"
            type="text"
            class="mt-4 block w-full"
            placeholder="Project Delivery Date"
          />
          <InputError
            :message="form.errors.project_delivery_date"
            class="mt-2"
          />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 4">
          <label for="">Select Probability of Closure</label>
          <SelectInput
            v-model="form.probability_of_closure"
          >
            <option value="" :disabled="true">
              Select Probability of Closure
            </option>
            <option value="Most Likely">Most Likely</option>
            <option value="Likely">Likely</option>
            <option value="Unlikely">Unlikely</option>
          </SelectInput>
          <InputError
            :message="form.errors.probability_of_closure"
            class="mt-2"
          />

          <TextInput
            v-model="form.expected_sale_value"
            type="number"
            class="mt-1 block w-full"
            placeholder="Expected Sale Value"
          />
          <InputError :message="form.errors.expected_sale_value" class="mt-2" />

          <TextInput
            v-model="form.expected_month_of_closure"
            type="text"
            class="mt-4 block w-full"
            placeholder="Expected Month of Closure"
          />
          <InputError
            :message="form.errors.expected_month_of_closure"
            class="mt-2"
          />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 6">
          <TextInput
            v-model="form.reason_for_losing"
            type="text"
            class="mt-1 block w-full"
            placeholder="Reason  for Losing"
          />
          <InputError :message="form.errors.reason_for_losing" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 7">
          <label for="">Select Assigned Presale</label>
          <SelectInput
            v-model="form.presale_assigned"
          >
            <option value="" :disabled="true">Select Assigned Presale</option>
            <option
              v-for="presale in props.presales"
              :key="presale.id"
              :value="presale.id"
            >
              {{ presale.first_name }} {{ presale.last_name }}
            </option>
          </SelectInput>
          <InputError :message="form.errors.presale_assigned" class="mt-2" />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 8">
          <TextInput
            v-model="form.site_survey_comments"
            type="text"
            class="mt-4 block w-full"
            placeholder="Site Survey Comments"
          />
          <InputError
            :message="form.errors.site_survey_comments"
            class="mt-2"
          />

          <TextInput
            v-model="form.site_survey_due_date"
            type="text"
            class="mt-4 block w-full"
            placeholder="Site Survey Due Date"
          />
          <InputError
            :message="form.errors.site_survey_due_date"
            class="mt-2"
          />
        </div>

        <div class="mt-4" v-if="form.project_stage_id === 9">
          <TextInput
            v-model="form.reason_for_overdue"
            type="text"
            class="mt-1 block w-full"
            placeholder="Reason  for Overdue"
          />
          <InputError :message="form.errors.reason_for_overdue" class="mt-2" />

          <TextInput
            v-model="form.overdue_next_steps"
            type="text"
            class="mt-4 block w-full"
            placeholder="Next Steps"
          />
          <InputError :message="form.errors.overdue_next_steps" class="mt-2" />
        </div>

        <div>
          <div class="mt-4">
            <p>Documents</p>
            <InputError :message="form.errors.documents" class="mt-2" />
          </div>

          <div
            v-for="(document, index) in form.documents"
            :key="document.id"
            class="mt-4 flex items-center"
          >
            <div class="flex-1 mr-2">
              <TextInput
                v-model="document.name"
                type="text"
                class="mt-1 block w-full"
                placeholder="Document Name"
              />
            </div>
            <div class="flex-1 ml-2">
              <TextInput
                @change="handleFileChange($event.target.files[0], index)"
                type="file"
                class="mt-1 block w-full"
                placeholder="Upload File"
              />
            </div>

            <div class="flex-1 ml-2">
              <PrimaryButton
                class="ms-3 bg-red-500"
                @click="removeDocument(document)"
              >
                <MdDelete />
              </PrimaryButton>
            </div>
          </div>

          <div class="mt-4 flex justify-end">
            <PrimaryButton class="ms-3" @click="addDocuments">
              Add Document
            </PrimaryButton>
          </div>
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="closeUpdateStageModal">
          Cancel
        </SecondaryButton>

        <PrimaryButton
          class="ms-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click.prevent="moveStage()"
        >
          Update Stage
        </PrimaryButton>
      </template>
    </DialogModal>
    </template>
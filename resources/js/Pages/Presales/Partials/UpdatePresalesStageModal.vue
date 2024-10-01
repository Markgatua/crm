<template>
  <DialogModal :show="show" @close="$emit('close')">
    <template v-if="account"" #title>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Update {{ account.business_name || "Account" }}</h1>
    <h2 class="text-xl font-semibold text-gray-700 mb-2">Account Name: {{ account.client.name }}</h2>
    <h3 class="text-lg font-medium text-gray-600 mb-2">Solution Name: {{ account.solution_name }}</h3>
    <h3 class="text-lg font-medium text-gray-600 mb-2 underline">Action Points and Details</h3>
    <ul class="list-disc list-inside">
    <li v-for="item in account.latest_stage.meta" :key="item.key">
       {{ item.key.replace(/_/g, ' ') }}: 
       <template v-if="Array.isArray(item.value)">
              <ul class="list-outside pl-6">
                <li v-for="subItem in item.value" class="flex items-center gap-2" :key="subItem.key">
                  <!-- Check if the key is 'Documents' -->
                  <template v-if="item.key === 'Documents'">
                  <Icon icon="flat-color-icons:document"/>
                    <a :href="subItem.value" target="_blank" class='text-blue-500 font-bold'>{{ subItem.key }}: {{ subItem.value }}</a>
                  </template>
                  <template v-else>
                  <Icon icon="mingcute:choice-fill" class="text-blue-500"/>
                    {{ subItem.key.replace(/_/g, ' ') }}: {{ subItem.value }}
                  </template>
                </li>
              </ul>
            </template>
       <template v-else>
         {{ item.value }}
       </template>
     </li>

    </ul>
    </template>
    <template #content>
      <div class="p-6">
        <form @submit.prevent="submit">
          <div class="mb-2">
            <InputLabel for="description" value="1. Description" />
            <TextAreaInput
              id="description"
              type="text"
              class="mt-1 block w-full"
              v-model="form.presale_description"
              required
              autofocus
            />
            <InputError class="mt-2" :message="form.errors.presale_description" />
          </div>
          <div class="mb-2">
            <InputLabel for="quotation" value="2. Quotation" />
            <TextInput
              id="quotation"
              type="number"
              class="mt-1 block w-full"
              v-model="form.presale_quotation"
              autofocus
            />
            <InputError class="mt-2" :message="form.errors.presale_quotation" />
          </div>
          <span class="py-4">2. Document Section</span>
          <div class="flex flex-col gap-2 my-6">
            <div
              v-for="(field, index) in form.documents"
              :key="index"
              class="flex flex-col md:flex-row gap-1 md:gap-2"
            >
              <TextInput
                v-model="field.name"
                type="text"
                placeholder="File Name"
              />
              <TextInput
                @input="form.image = $event.target.files[0]"
                type="file"
                placeholder="file"
              />
              <DangerButton type="button" @click="removeFile(index)"
                >Remove</DangerButton
              >
            </div>
            <PrimaryButton type="button" @click="addFile" class="w-fit"
              >Add Field</PrimaryButton
            >
            <InputError class="mt-2" :message="form.errors.attachment" />
          </div>
          <div class="flex items-center justify-end mt-4">
            <SecondaryButton type="button" @click.prevent="$emit('close')"
              >Cancel</SecondaryButton
            >
            <PrimaryButton
              class="ms-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Update Stage
            </PrimaryButton>
          </div>
        </form>
      </div>
    </template>
  </DialogModal>
</template>

<script setup>
import { ref, watch } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { Icon } from "@iconify/vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/Form/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import DialogModal from "@/Components/DialogModal.vue";
import toast from "@/Stores/toast";

// Receive props
const props = defineProps({
  show: Boolean,
  account: { type: Object, default: null },
  nextStage:{type:Number}
});

const form = useForm({
  presale_description: "",
  presale_quotation: "",
  project_stage_id: props.nextStage,
  documents: [],
  account_id: null,
});

watch(() => props.account, (newAccount) => {
  if (newAccount) {
    form.account_id = newAccount.id;
  }
}, { immediate: true });

const addFile = () => {
  form.documents.push({ id: Math.random(), name: "", file: null });
};

const removeFile = (index) => {
  form.documents.splice(index, 1);
};

const emit = defineEmits(['close']);

const submit = () => {
  form.post(
    route("presales.update_stage"),
    {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        emit("close");
        location.reload();
      },
      onError: (e) => {
        toast.add(e);
      },
    }
  );
};
</script>

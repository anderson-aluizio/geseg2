<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import DialogModal from "./DialogModal.vue";
import ActionMessage from "./ActionMessage.vue";
import AlertErrors from "./AlertErrors.vue";
import SecondaryButton from "./SecondaryButton.vue";
import Tooltip from "./Tooltip.vue";
import DangerButton from "./DangerButton.vue";
import IconButton from "./IconButton.vue";

const props = defineProps({
  identificador: String | Number,
  rota: String,
  otherParams: {
    type: Object,
    default: {},
  },
  label: String,
  descricao: {
    type: String,
    default: "O registro será removido permanentemente.",
  },
  textButton: {
    type: String,
    default: "",
  },
  showLabel: {
    type: Boolean,
    default: false,
  }
})
let visible = ref(false);
let openConfirm = ref(false);
const form = useForm({
  id: props.identificador,
  ...props.otherParams,
});
const destroy = () => {
  form.delete(props.rota, {
    preserveState: (page) => Object.keys(page.props.errors).length,
    onSuccess: () => openConfirm = false,
  });
};
const textButtonIsEmpty = () => {
  return typeof props.textButton == "string" && props.textButton.length < 1;
};
</script>
<template>
  <div>
    <Tooltip v-if="textButtonIsEmpty()" description="Excluir" @click="openConfirm = true">
      <IconButton>
        <font-awesome-icon class="text-red-800" :class="{ 'pr-4': showLabel }" icon="times" @mouseenter="visible = true"
          @mouseleave="visible = false" />
      </IconButton>
    </Tooltip>
    <DangerButton v-else @click="openConfirm = true"
      class="px-3 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-800 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
      {{ textButton }}</DangerButton>

    <DialogModal :show="openConfirm" :closeable="false" @close="openConfirm = false">
      <template #title>Atenção!</template>
      <template #content>
        <div class="mt-2">
          <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />
          <div>
            <span>Tem certeza que deseja excluir ?</span>
          </div>
          <div>
            <span class="text-red-600">{{ descricao }}</span>
          </div>
          <ActionMessage :on="form.recentlySuccessful" class="mr-3">
            <span class="bg-green-200">Registro excluído com sucesso</span>
          </ActionMessage>
        </div>
      </template>
      <template #footer>
        <SecondaryButton type="primary" @click="openConfirm = false">Cancelar</SecondaryButton>
        <DangerButton @click="destroy" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">{{ label }}
        </DangerButton>
      </template>
    </DialogModal>
  </div>
</template>

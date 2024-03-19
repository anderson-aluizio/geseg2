<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import DialogModal from '@/Components/DialogModal.vue';

const emit = defineEmits(['close']);
const props = defineProps({
  show: false
})

const form = useForm({
  name: '',
});

const save = () => {
  form.post(route('accesscontrol.role.store'), {
    onSuccess: () => close(),
  }, {
    preserveState: false,
    preserveScroll: true
  });
}
const close = () => {
  emit('close');
}
</script>
<template>
  <DialogModal :show="show" :closeable="true" @close="close" max-width="2xl">
    <template #title>
      Cadastro
    </template>
    <template #content>
      <div class="grid grid-cols-1 py-2">
        <div class="col-span-12 sm:col-span-2">
          <label class="block font-medium text-sm text-gray-700">
            Nome
          </label>
          <TextInput id="name" v-model="form.name" type="text" required />
          <InputError :message="form.errors.name" class="mt-2" />
        </div>
      </div>
    </template>
    <template #footer>
      <ActionMessage :on="form.recentlySuccessful" class="mr-3">
        Salvo.
      </ActionMessage>
      <SecondaryButton @click="close">
        Cancelar
      </SecondaryButton>
      <PrimaryButton @click="save" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Salvar
      </PrimaryButton>
    </template>
  </DialogModal>
</template>


<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showpass = ref(false);

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <AuthenticationCard>
        <AuthenticationCardLogo />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-2 pt-2">
            <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />

            <TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" placeholder="E-mail" required
                autofocus autocomplete="username" />

            <TextInput id="password" class="mt-1 block w-full" v-model="form.password"
                :type="showpass ? 'text' : 'password'" placeholder="Senha" required />

            <button type="button" class="flex items-center mt-1" @click="showpass = !showpass">
                <font-awesome-icon class="text-[#bc272d]" :icon="!showpass ? 'eye' : 'eye-slash'" />
                <span class="ml-2 text-sm text-gray-600">Exibir senha</span>
            </button>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Acessar
            </PrimaryButton>
        </form>
    </AuthenticationCard>
</template>
<style>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
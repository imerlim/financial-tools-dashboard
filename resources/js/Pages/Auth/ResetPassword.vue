<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <CustomInput
                    v-model="form.email"
                    type="email"
                    label="Usuário"
                    id="email"
                    name="email"
                    :autofocus="true"
                    autocomplete="username"
                    :required="true"
                >
                </CustomInput>
            </div>

            <div class="mt-4">
                <CustomInput
                    v-model="form.password"
                    type="password"
                    label="Senha"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    :required="true"
                >
                </CustomInput>
            </div>

            <div class="mt-4">
                <CustomInput
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirmar senha"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    :required="true"
                >
                </CustomInput>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resetar senha
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { getCurrentInstance } from 'vue';

const { proxy } = getCurrentInstance();
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),

        onError: errors => {
            console.log('error', errors);
            if (errors.email) {
                switch (errors.email) {
                    case 'The email field must be a valid email address.':
                        console.log('error', errors.password);
                        return proxy.$msg.warning('O campo email deve conter um email válido.');
                }
            }
            if (errors.password) {
                switch (errors.password) {
                    case 'The password field must be at least 8 characters.':
                        console.log('error', errors.password);
                        return proxy.$msg.warning('Senha deve conter no mínimo 8 caracteres.');

                    case 'The password field confirmation does not match.':
                        return proxy.$msg.warning('Os campos senhas devem ser iguais.');
                }
            }
        },
    });
    console.log('teste');
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <CustomInput
                    v-model="form.name"
                    label="Usuário"
                    id="name"
                    name="name"
                    :autofocus="true"
                    autocomplete="username"
                    :required="true"
                >
                </CustomInput>
            </div>

            <div class="mt-4">
                <CustomInput v-model="form.email" label="Email" id="email" name="email" autocomplete="username" :required="true">
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
                <Link
                    href="/login"
                    class="rounded-md text-base text-slate-900 dark:text-white underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    já cadastrado?
                </Link>

                <PrimaryButton type="submit" class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Cadastrar
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <CustomInput
                    type="email"
                    v-model="form.email"
                    label="Email"
                    id="email"
                    name="email"
                    :autofocus="true"
                    :required="true"
                    autocomplete="username"
                >
                </CustomInput>
            </div>

            <div class="mt-4">
                <CustomInput
                    type="password"
                    v-model="form.password"
                    label="Senha"
                    id="password"
                    name="password"
                    :autofocus="true"
                    :required="true"
                    autocomplete="username"
                >
                </CustomInput>
            </div>

            <div class="mt-4 block">
                <CustomCheckbox
                    label="Remember me"
                    id="remember"
                    name="remember"
                    v-model="form.remember"
                ></CustomCheckbox>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-slate-900 dark:text-white underline dark:hover:text-slate-300 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ml-4" :disabled="form.processing">Log in</PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

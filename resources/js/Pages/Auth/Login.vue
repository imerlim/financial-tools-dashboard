<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { getCurrentInstance } from 'vue';

const { proxy } = getCurrentInstance();

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
        onError: errors => {
            if (errors.email) {
                proxy.$msg.warning('Usuário ou senha incorretos.');
            }
            console.log('Objeto de erro completo:', errors);
        },
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
                    label="Password"
                    id="password"
                    name="password"
                    :required="true"
                    autocomplete="username"
                >
                </CustomInput>
            </div>

            <div class="mt-4 block">
                <CustomCheckbox label="Remember me" id="remember" name="remember" v-model="form.remember"></CustomCheckbox>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="'/forgot-password'"
                    class="rounded-md text-sm text-slate-900 dark:text-white underline dark:hover:text-slate-300 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton type="submit" class="ml-4" :disabled="form.processing">Log in</PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

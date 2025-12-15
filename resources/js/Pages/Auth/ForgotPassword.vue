<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-base text-slate-900 dark:text-white">
            Forgot your password? No problem. Enter your registered email address and we will send you a link to reset your password.
        </div>

        <div v-if="status" class="mb-4 text-base font-medium text-green-600">
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

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"> Enviar </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

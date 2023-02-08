<script setup>
import {Head, useForm} from '@inertiajs/inertia-vue3';
import CookbookLayout from "@/Layouts/CookbookLayout.vue";
import PasswordField from "@/Components/Form/PasswordField.vue";

defineProps({
    canResetPassword: Boolean,
    status: String,
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
    <CookbookLayout>
        <Head title="Log in"/>

        <v-container>
            <v-card>
                <v-card-title>{{$t('pages.profile.login.title')}}</v-card-title>

                <v-container>
                    <v-form @submit.prevent="submit">
                        <v-text-field
                            id="email"
                            type="email"
                            :label="$t('pages.profile.email.title')"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            :error-messages="form.errors.email"
                        />

                        <PasswordField
                            id="password"
                            type="password"
                            :label="$t('pages.profile.password.title')"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="current-password"
                            :error-messages="form.errors.password"
                        />

                        <v-checkbox
                            name="remember"
                            :label="$t('pages.profile.login.remember_me')"
                            v-model="form.remember"/>

                        <v-btn v-if="canResetPassword" :href="route('password.request')">
                            {{ $t('pages.profile.login.forgot_password') }}
                        </v-btn>

                        <v-btn color="primary"
                               type="submit"
                               :disabled="form.processing">
                            {{ $t('pages.profile.login.submit') }}
                        </v-btn>
                    </v-form>
                </v-container>

            </v-card>
        </v-container>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

    </CookbookLayout>
</template>

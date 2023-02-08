<script setup>
import {useForm} from '@inertiajs/inertia-vue3';
import {ref} from 'vue';
import notification from "@/Util/notification";
import {useI18n} from "vue-i18n";
import PasswordField from "@/Components/Form/PasswordField.vue";

const {t} = useI18n();

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            notification.success(t('pages.profile.update_password.saved'));
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <v-container fluid>
        <p class="text-h6">
            {{ $t('pages.profile.update_password.title') }}
        </p>
        <p class="text-subtitle-1">
            {{ $t('pages.profile.update_password.description') }}
        </p>

        <v-form @submit.prevent="updatePassword">
            <PasswordField :label="$t('pages.profile.password.current')"
                           v-model="form.current_password"
                           autocomplete="password"
                           ref="currentPasswordInput"
                           :error-messages="form.errors.current_password"/>

            <PasswordField :label="$t('pages.profile.password.new')"
                          v-model="form.password"
                          auto-complete="new-password"
                          ref="passwordInput"
                          :error-messages="form.errors.password"/>

            <PasswordField :label="$t('pages.profile.password.confirm')"
                          v-model="form.password_confirmation"
                          auto-complete="new-password"
                          :error-messages="form.errors.password_confirmation"/>

            <v-btn color="primary" type="submit"
                   :disabled="form.processing">
                {{ $t('pages.common.save') }}
            </v-btn>
        </v-form>
    </v-container>
</template>

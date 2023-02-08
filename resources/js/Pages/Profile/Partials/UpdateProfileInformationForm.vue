<script setup>
import {useForm, usePage} from '@inertiajs/inertia-vue3';
import axios from "axios";
import notification from "@/Util/notification";
import {useI18n} from "vue-i18n";

const {t} = useI18n();


const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.value.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

function resentVerification() {
    axios.post(route('verification.send'))
        .then(() => {
            notification.info(
                t('pages.profile.email.verification-sent',
                    {email: user.name})
            );
        });
}

function onSubmited() {
    notification.success(t('pages.profile.update.saved'));
}

</script>

<template>
    <v-container fluid>
        <v-alert v-if="props.mustVerifyEmail && user.email_verified_at === null"
                 type="warning" icon="mdi-alert-circle-outline">
            <v-row>
                <v-col>
                    {{ $t('pages.profile.email.unverified') }}
                </v-col>
                <v-col cols="auto">
                    <v-btn
                        variant="tonal"
                        prepend-icon="mdi-refresh"
                        @click="resentVerification">
                        {{ $t('pages.profile.email.resent-verification') }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-alert>

        <p class="text-h6">
            {{ $t('pages.profile.update.title') }}
        </p>
        <p class="text-subtitle-1">
            {{ $t('pages.profile.update.description') }}
        </p>
        <v-form @submit.prevent="form.patch(route('profile.update'), {  onSuccess: onSubmited,})">
            <v-text-field id="name"
                          :label="$t('pages.profile.name')"
                          density="compact"
                          variant="outlined"
                          v-model="form.name"
                          autofocus
                          autocomplete="name"
                          :error-messages="form.errors.name"/>

            <v-text-field id="email"
                          :label="$t('pages.profile.email.title')"
                          density="compact"
                          variant="outlined"
                          v-model="form.email"
                          autocomplete="email"
                          :error-messages="form.errors.email"/>


            <div class="flex items-center gap-4">
                <v-btn color="primary" type="submit"
                       :disabled="form.processing">
                    {{ $t('pages.common.save') }}
                </v-btn>
            </div>
        </v-form>

    </v-container>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
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
        <Head :title="$t('pages.profile.reset_password.title')"/>

        <v-container>
            <v-row justify="center">
                <v-col cols="auto">
                    <v-card width="640" class="mt-10">
                        <v-card-title>{{ $t('pages.profile.reset_password.title') }}</v-card-title>

                        <v-alert type="success" v-if="status">
                            {{ status }}
                        </v-alert>

                        <v-card-text>
                            {{ $t('pages.profile.reset_password.description') }}
                        </v-card-text>

                        <v-container>
                            <v-text-field id="email"
                                          type="email"
                                          :label="$t('pages.profile.email.title')"
                                          v-model="form.email"
                                          required
                                          autofocus
                                          autocomplete="username"
                                          :error-messages="form.errors.email"/>
                        </v-container>

                        <v-card-actions class="flex">
                            <v-spacer/>
                            <v-btn color="primary"
                                   variant="elevated"
                                   :disabled="form.processing"
                                   @click="submit">
                                {{ $t('pages.profile.reset_password.submit') }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </GuestLayout>
</template>

<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import {nextTick, ref} from 'vue';
import PasswordField from "@/Components/Form/PasswordField.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <v-container fluid>
        <p class="text-h6">
            {{ $t('pages.profile.delete.title') }}
        </p>
        <p class="text-subtitle-1">
            {{ $t('pages.profile.delete.description') }}
        </p>

        <v-btn color="danger"
               :disabled="form.processing"
               @click="confirmUserDeletion">
            {{ $t('pages.profile.delete.submit') }}
        </v-btn>

        <v-dialog v-model="confirmingUserDeletion">
            <v-card>
                <v-card-title>{{ $t('pages.profile.delete.confirm.title') }}</v-card-title>
                <v-card-text>{{ $t('pages.profile.delete.confirm.description') }}</v-card-text>
                <v-container>
                    <PasswordField ref="passwordInput"
                                   v-model="form.password"
                                   @keydown.enter="deleteUser"
                                   :error-messages="form.errors.password"/>
                </v-container>
                <v-card-actions>
                    <v-btn :disabled="form.processing"
                           @click="closeModal">
                        {{ $t('pages.common.cancel') }}
                    </v-btn>
                    <v-btn color="danger"
                           :disabled="form.processing"
                           @click="deleteUser">
                        {{ $t('pages.profile.delete.submit') }}
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-dialog>

    </v-container>
    <section class="space-y-6">
        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

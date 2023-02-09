<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NotificationBar from "@/Components/NotificationBar.vue";

defineProps({
    hideLogin: Boolean,
    hideRegister: Boolean,
});

</script>

<template>
    <v-app>
        <v-app-bar color="primary">
            <v-container class="pa-0 pt-2">
                <v-row align="center">
                    <v-col cols="auto">
                        <ApplicationLogo style="fill: white;width: 50px"/>

                    </v-col>
                    <v-col>
                        <v-app-bar-title>{{ $t('app.name') }}</v-app-bar-title>
                    </v-col>
                </v-row>
            </v-container>

            <template v-slot:append>
                <v-btn v-if="!hideLogin"
                       :href="route('login')"
                       color="white">
                    {{ $t("pages.profile.login.title") }}
                </v-btn>
                <v-btn v-if="!hideRegister"
                       :href="route('register')"
                       color="white">
                    {{ $t("pages.profile.register") }}
                </v-btn>
            </template>
        </v-app-bar>
        <v-main>
            <slot/>
            <notification-bar/>
        </v-main>
    </v-app>
</template>

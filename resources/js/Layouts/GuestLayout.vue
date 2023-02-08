<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NotificationBar from "@/Components/NotificationBar.vue";</script>
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
                <v-btn :href="route('login')">{{ $t("pages.profile.login") }}</v-btn>
                <v-btn :href="route('register')">{{ $t("pages.profile.register") }}</v-btn>
            </template>
        </v-app-bar>
        <v-main>
            <slot/>
            <notification-bar/>
        </v-main>
    </v-app>
</template>

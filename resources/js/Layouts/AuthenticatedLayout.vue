<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import NotificationBar from "@/Components/NotificationBar.vue";

let names = usePage().props.value.auth.user.name.split(" ")
let initials = "";

if (names.length < 1) {
    initials = "N/A";
} else if (names.length === 1) {
    initials = names[0].substring(0, 2);
} else {
    initials = names.shift().substring(0, 1);
    initials += names.pop().substring(0, 1);
}
initials = initials.toUpperCase();


</script>
<template>
    <v-app>
        <v-app-bar color="primary">
            <v-app-bar-title>
                <ApplicationLogo style="fill: white;width: 50px"/>
                <v-app-bar-title>{{ $t('app.name') }}</v-app-bar-title>
            </v-app-bar-title>

            <template v-slot:append>
                <v-menu>
                    <template v-slot:activator="{props}">
                        <v-btn v-bind="props" icon>
                            <v-avatar color="white"
                                      variant="outlined">
                                {{ initials }}
                            </v-avatar>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-list>
                            <v-list-item :href="route('profile.edit')"
                                prepend-icon="mdi-account-circle"
                                         density="compact"
                                         :title="$t('pages.profile.settings')"/>
                        </v-list>
                    </v-card>
                </v-menu>
            </template>
        </v-app-bar>
        <v-main>
            <slot/>
            <notification-bar/>
        </v-main>
    </v-app>
</template>

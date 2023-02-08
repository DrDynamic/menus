<script>
import {defineComponent} from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import config from "tailwindcss/defaultConfig";

export default defineComponent({
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },
    computed: {
        config() {
            return config
        }
    },
    components: {ApplicationLogo},
})
</script>
<template>
    <v-app>
        <v-app-bar color="transparent" elevation="0" density="comfortable">
            <v-container class="pa-0 pt-2" fluid>
                <v-row align="center">
                    <v-col cols="auto">
                        <ApplicationLogo style="width: 50px"/>
                    </v-col>
                </v-row>
            </v-container>
            <template v-slot:append>
                <template v-if="canLogin">
                    <template v-if="$page.props.auth.user">
                        <v-btn>Open Cookbook</v-btn>
                    </template>
                    <template v-else>
                        <v-btn>login</v-btn>
                        <v-btn>register</v-btn>
                    </template>
                </template>
            </template>
            <template v-slot:extension>
                <v-container>
                    <v-tabs align-tabs="center">
                        <v-tab>Cookbook</v-tab>
                        <v-tab>Features</v-tab>
                        <v-tab>About</v-tab>
                    </v-tabs>
                </v-container>

            </template>
        </v-app-bar>
        <v-main>
            <slot/>
        </v-main>
    </v-app>
</template>
<style scoped>

</style>

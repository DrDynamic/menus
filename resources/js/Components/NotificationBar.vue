<script setup>
import eventBus from "@/Util/eventBus";


let isOpen = false;
let notification = null;

eventBus.$on('notification', (n) => {

    notification = n;
    isOpen = true;

    console.log("OPen Snackbar");
});
</script>
<template>
    <v-snackbar v-if="isOpen" v-model="isOpen" :color="notification.color">
        <v-icon>{{ notification.icon }}</v-icon>
        {{ notification.text }}
    </v-snackbar>
</template>

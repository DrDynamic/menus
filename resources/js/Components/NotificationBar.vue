<script setup>
import eventBus from "@/Util/eventBus";
import {ref} from "vue";


let isOpen = ref(false);
let notification = ref({
    color: '',
    icon: '',
    timeout: 4000,
    text: ''
});

eventBus.$on('notification', (n) => {
    notification.value.icon = n.icon || null;
    notification.value.color = n.color || 'secondary';
    notification.value.text = n.text || '';
    notification.value.timeout = n.timeout || 4000;

    isOpen.value = true;
});
</script>
<template>
    <div>
        <v-btn @click="isOpen=true">Open</v-btn>
        {{ isOpen }}
        <v-snackbar v-model="isOpen" :color="notification.color" :timeout="notification.timeout">
            <v-icon>{{ notification.icon }}</v-icon>
            {{ notification.text }}
        </v-snackbar>

    </div>

</template>

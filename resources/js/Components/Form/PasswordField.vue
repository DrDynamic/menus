<script setup>
import {ref} from "vue";

const show_password = ref(false)

</script>
<template>
    <v-text-field v-bind="$attrs"
                  :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show_password?'text':'password'"
                  @click:append="show_password = !show_password"/>
</template>

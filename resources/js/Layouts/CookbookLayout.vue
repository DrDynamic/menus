<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const isLoggedIn = !!usePage().props.value.auth.user


</script>
<template>
    <AuthenticatedLayout v-if="isLoggedIn">
        <slot/>
    </AuthenticatedLayout>
    <GuestLayout v-else>
        <slot/>
    </GuestLayout>
</template>

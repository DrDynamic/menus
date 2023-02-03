<template>
    <Head :title="$t('pages.dishes.title')"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('pages.dishes.title') }}</h2>
        </template>

        <v-btn>Test!</v-btn>

        <div class="py-12">

            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import {Head} from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    name: "DishesList",
    components: {Head, AuthenticatedLayout}
}
</script>

<style scoped>

</style>

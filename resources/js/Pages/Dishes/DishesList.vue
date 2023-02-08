<template>
    <Head :title="$t('pages.dishes.title')"/>

    <CookbookLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('pages.dishes.title') }}</h2>
        </template>

        <v-container>
            <v-row>
                <v-col v-for="dish in dishes">
                    <v-card width="350">
                        <v-img :src="dish.image_url" height="200" cover></v-img>
                        <v-card-title>{{ dish.name }}</v-card-title>

                        <v-divider/>
                        <v-card-text>
                            <div>
                            <span v-for="ingredient in dish.ingredients">
                                {{ ingredient.name }} •
                            </span>
                            </div>

                        </v-card-text>
                    </v-card>
                </v-col>

            </v-row>
        </v-container>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </CookbookLayout>
</template>
<script setup>
import {usePage} from "@inertiajs/inertia-vue3";
import CookbookLayout from "@/Layouts/CookbookLayout.vue";

defineProps({dishes: Array})
</script>
<script>
import {Head} from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    name: "DishesList",
    components: {Head, AuthenticatedLayout},
}
</script>

<style scoped>

</style>

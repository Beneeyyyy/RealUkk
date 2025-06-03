<template>
    <div v-if="links.length > 3" class="flex justify-center mt-4">
        <div class="flex space-x-1">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="px-4 py-2 text-gray-500 bg-white border rounded"
                    v-html="link.label"
                />
                <Link
                    v-else
                    :href="link.url"
                    class="px-4 py-2 border rounded"
                    :class="{
                        'bg-blue-500 text-white': link.active,
                        'bg-white text-gray-700 hover:bg-gray-100': !link.active,
                    }"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

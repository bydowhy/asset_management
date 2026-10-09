<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

defineProps<{
    links: PaginationLink[];
}>();
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            <!-- Disabled (mis. "&laquo; Previous" di halaman pertama) -->
            <span
                v-if="!link.url"
                class="rounded border px-3 py-1 text-sm opacity-50"
                v-html="link.label"
            />
            <!-- Active -->
            <Link
                v-else
                :href="link.url"
                class="rounded border px-3 py-1 text-sm hover:bg-accent"
                :class="{ 'bg-primary text-primary-foreground hover:bg-primary': link.active }"
                preserve-scroll
                v-html="link.label"
            />
        </template>
    </div>
</template>
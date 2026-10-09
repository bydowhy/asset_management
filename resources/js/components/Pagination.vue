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

/**
 * Laravel mengirim label dengan HTML entities:
 *   "&laquo; Previous", "Next &raquo;", "&hellip;", dsb.
 * Kita decode ke karakter unicode agar bisa dirender sebagai teks biasa
 * (tanpa v-html). Ini menghindari isu hydration pada Inertia <Link>.
 */
function cleanLabel(label: string): string {
    return label
        .replace(/&laquo;/g, '«')
        .replace(/&raquo;/g, '»')
        .replace(/&hellip;/g, '…')
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#039;/g, "'")
        .replace(/&nbsp;/g, ' ');
}
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            <!-- Disabled (mis. "« Previous" di halaman pertama) -->
            <span
                v-if="!link.url"
                class="rounded border px-3 py-1 text-sm opacity-50"
            >
                {{ cleanLabel(link.label) }}
            </span>

            <!-- Active / clickable -->
            <Link
                v-else
                :href="link.url"
                class="rounded border px-3 py-1 text-sm hover:bg-accent"
                :class="{ 'bg-primary text-primary-foreground hover:bg-primary': link.active }"
                preserve-scroll
            >
                {{ cleanLabel(link.label) }}
            </Link>
        </template>
    </div>
</template>
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

defineProps<{ photos: any }>();

function destroy(photo: any) {
    if (!confirm(`Hapus foto "${photo.file_name}"?`)) return;
    router.delete(`/photos/${photo.id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Photos" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Photos</h1>
            <Link href="/photos/create">
                <Button size="sm">+ Upload Photo</Button>
            </Link>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">
            <div
                v-for="photo in photos.data"
                :key="photo.id"
                class="group relative overflow-hidden rounded-lg border"
            >
                <img
                    :src="`/photos/${photo.id}/file`"
                    :alt="photo.caption ?? photo.file_name"
                    class="aspect-square w-full object-cover"
                    loading="lazy"
                />
                <div class="absolute inset-x-0 bottom-0 bg-black/60 p-2 text-xs text-white opacity-0 transition group-hover:opacity-100">
                    <p class="truncate">{{ photo.caption ?? photo.file_name }}</p>
                    <button
                        class="mt-1 text-red-300 hover:text-red-100"
                        @click="destroy(photo)"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <p v-if="photos.data.length === 0" class="text-center text-muted-foreground">
            No photos yet.
        </p>
    </div>
</template>
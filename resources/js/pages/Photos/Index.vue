<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import PhotoLightbox from '@/components/PhotoLightbox.vue';

const props = defineProps<{ photos: any }>();

function destroy(photo: any) {
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

        <p v-if="photos.data.length === 0" class="text-center text-muted-foreground">
            No photos yet.
        </p>

        <PhotoLightbox
            v-else
            :photos="photos.data"
            :show-delete="true"
            @delete="destroy"
        />
    </div>
</template>
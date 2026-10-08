<script setup lang="ts">
import { computed, ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Download, ExternalLink, Trash2 } from '@lucide/vue';

type Photo = {
    id: string;
    file_name?: string;
    caption?: string | null;
    file_size?: number;
    taken_at?: string | null;
    uploaded_by?: any;
};

const props = defineProps<{
    photos: Photo[];
    showDelete?: boolean;
}>();

const emit = defineEmits<{
    (e: 'delete', photo: Photo): void;
}>();

const open = ref(false);
const currentIndex = ref(0);

const current = computed(() => props.photos[currentIndex.value]);

const formattedSize = computed(() => {
    const bytes = current.value?.file_size ?? 0;
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / 1024 / 1024).toFixed(2)} MB`;
});

function openAt(index: number) {
    currentIndex.value = index;
    open.value = true;
}

function next() {
    currentIndex.value = (currentIndex.value + 1) % props.photos.length;
}

function prev() {
    currentIndex.value = (currentIndex.value - 1 + props.photos.length) % props.photos.length;
}

function onDelete() {
    if (!current.value) return;
    if (!confirm(`Hapus foto "${current.value.caption ?? current.value.file_name}"?`)) return;
    emit('delete', current.value);
    open.value = false;
}
</script>

<template>
    <!-- Grid thumbnail -->
    <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
        <button
            v-for="(photo, index) in photos"
            :key="photo.id"
            type="button"
            class="group relative aspect-square overflow-hidden rounded-lg border transition hover:ring-2 hover:ring-primary"
            @click="openAt(index)"
        >
            <img
                :src="`/photos/${photo.id}/file`"
                :alt="photo.caption ?? photo.file_name"
                class="h-full w-full object-cover transition group-hover:scale-105"
                loading="lazy"
            />
            <div
                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition group-hover:opacity-100"
            >
                <ExternalLink class="h-6 w-6 text-white" />
            </div>
            <p
                v-if="photo.caption"
                class="absolute inset-x-0 bottom-0 truncate bg-black/60 p-1.5 text-xs text-white"
            >
                {{ photo.caption }}
            </p>
        </button>
    </div>

    <!-- Lightbox -->
    <Dialog v-model:open="open">
        <DialogContent
            class="max-w-4xl gap-0 p-0"
            @keydown.right="next"
            @keydown.left="prev"
        >
            <DialogHeader class="sr-only">
                <DialogTitle>{{ current?.caption ?? current?.file_name ?? 'Photo' }}</DialogTitle>
                <DialogDescription>Preview foto</DialogDescription>
            </DialogHeader>

            <div v-if="current" class="relative">
                <!-- Image -->
                <div class="flex max-h-[70vh] items-center justify-center bg-black">
                    <img
                        :src="`/photos/${current.id}/file`"
                        :alt="current.caption ?? current.file_name"
                        class="max-h-[70vh] object-contain"
                    />
                </div>

                <!-- Prev / Next -->
                <button
                    v-if="photos.length > 1"
                    type="button"
                    class="absolute top-1/2 left-2 -translate-y-1/2 rounded-full bg-black/60 p-2 text-white hover:bg-black/80"
                    @click="prev"
                >
                    ‹
                </button>
                <button
                    v-if="photos.length > 1"
                    type="button"
                    class="absolute top-1/2 right-2 -translate-y-1/2 rounded-full bg-black/60 p-2 text-white hover:bg-black/80"
                    @click="next"
                >
                    ›
                </button>

                <!-- Meta bar -->
                <div class="flex items-center justify-between gap-4 border-t bg-background p-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">
                            {{ current.caption ?? current.file_name }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            {{ formattedSize }}
                            <span v-if="current.taken_at">
                                · {{ new Date(current.taken_at).toLocaleString() }}
                            </span>
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <a
                            :href="`/photos/${current.id}/file`"
                            :download="current.file_name"
                            target="_blank"
                        >
                            <Button variant="outline" size="sm">
                                <Download class="mr-1 h-4 w-4" />
                                Download
                            </Button>
                        </a>
                        <Button
                            v-if="showDelete"
                            variant="destructive"
                            size="sm"
                            @click="onDelete"
                        >
                            <Trash2 class="mr-1 h-4 w-4" />
                            Hapus
                        </Button>
                    </div>
                </div>

                <!-- Counter -->
                <p
                    v-if="photos.length > 1"
                    class="absolute top-3 left-3 rounded bg-black/60 px-2 py-1 text-xs text-white"
                >
                    {{ currentIndex + 1 }} / {{ photos.length }}
                </p>
            </div>
        </DialogContent>
    </Dialog>
</template>
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';

defineProps<{
    types: any[];
    errors?: Record<string, string>;
}>();

function destroy(type: any) {
    if (!confirm(`Hapus document type "${type.name}"?`)) return;
    router.delete(`/document-types/${type.id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Document Types" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Document Types</h1>
            <Link href="/document-types/create">
                <Button size="sm">+ New Document Type</Button>
            </Link>
        </div>

        <div
            v-if="errors?.delete"
            class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"
        >
            {{ errors.delete }}
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Code</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead class="text-center">Used by</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="type in types" :key="type.id">
                        <TableCell class="font-mono text-sm">{{ type.code }}</TableCell>
                        <TableCell class="font-medium">{{ type.name }}</TableCell>
                        <TableCell class="max-w-md truncate text-sm text-muted-foreground">
                            {{ type.description ?? '—' }}
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">{{ type.documents_count }}</Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/document-types/${type.id}/edit`">
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                                <Button variant="destructive" size="sm" @click="destroy(type)">
                                    Delete
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="types.length === 0">
                        <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                            No document types yet.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';

const props = defineProps<{
    locations: any[];
    errors?: Record<string, string>;
}>();

function destroy(location: any) {
    if (!confirm(`Hapus location "${location.name}"?`)) return;
    router.delete(`/locations/${location.id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Locations" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Locations</h1>
            <Link href="/locations/create">
                <Button size="sm">+ New Location</Button>
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
                        <TableHead>Parent</TableHead>
                        <TableHead class="text-center">Sub-locations</TableHead>
                        <TableHead class="text-center">Equipment</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="loc in locations" :key="loc.id">
                        <TableCell class="font-medium">{{ loc.code }}</TableCell>
                        <TableCell>{{ loc.name }}</TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            {{ loc.parent?.name ?? '—' }}
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">{{ loc.children_count }}</Badge>
                        </TableCell>
                        <TableCell class="text-center">
                            <Badge variant="secondary">{{ loc.equipment_count }}</Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/locations/${loc.id}/edit`">
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click="destroy(loc)"
                                >
                                    Delete
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="locations.length === 0">
                        <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                            No locations yet.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
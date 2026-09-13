<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import { ref, watch } from 'vue';

const props = defineProps<{
    documents: any;
    documentTypes: any[];
    filters: Record<string, string | undefined>;
}>();

const search = ref(props.filters.search ?? '');
const documentTypeId = ref(props.filters.document_type_id || 'all');

watch([search, documentTypeId], () => {
    router.get('/documents', {
        search: search.value,
        document_type_id: documentTypeId.value === 'all' ? '' : documentTypeId.value,
    }, { preserveState: true, replace: true });
});

function destroy(doc: any) {
    if (!confirm(`Hapus dokumen "${doc.name}"?`)) return;
    router.delete(`/documents/${doc.id}`, { preserveScroll: true });
}
</script>

<template>
    <Head title="Documents" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Documents</h1>
            <Link href="/documents/create">
                <Button size="sm">+ Upload Document</Button>
            </Link>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <Input v-model="search" placeholder="Search document name..." class="max-w-xs" />
            <Select v-model="documentTypeId">
                <SelectTrigger class="w-50"><SelectValue placeholder="All Types" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Types</SelectItem>
                    <SelectItem v-for="t in documentTypes" :key="t.id" :value="t.id">{{ t.name }}</SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead>Size</TableHead>
                        <TableHead>Uploaded By</TableHead>
                        <TableHead>Linked To</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="doc in documents.data" :key="doc.id">
                        <TableCell class="font-medium">{{ doc.name }}</TableCell>
                        <TableCell>{{ doc.type?.name ?? '-' }}</TableCell>
                        <TableCell class="text-sm">{{ (doc.file_size / 1024).toFixed(1) }} KB</TableCell>
                        <TableCell class="text-sm">{{ doc.uploaded_by?.name ?? '-' }}</TableCell>
                        <TableCell class="text-sm">
                            <span v-if="doc.links?.length">
                                {{ doc.links.length }} entity
                            </span>
                            <span v-else class="text-muted-foreground">—</span>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <a :href="`/documents/${doc.id}/download`">
                                    <Button variant="outline" size="sm">Download</Button>
                                </a>
                                <Button variant="destructive" size="sm" @click="destroy(doc)">Delete</Button>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="documents.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                            No documents yet.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
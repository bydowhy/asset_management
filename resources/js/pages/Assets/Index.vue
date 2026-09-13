<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import { ref, watch } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Assets', href: '/assets' },
];

const props = defineProps<{
    assets: any;
    assetTypes: any[];
    manufacturers: string[];
    filters: Record<string, string | undefined>;
}>();

const search = ref(props.filters.search ?? '');
const assetTypeId = ref(props.filters.asset_type_id ?? '');
const status = ref(props.filters.status ?? '');
const manufacturer = ref(props.filters.manufacturer ?? '');

const statusVariant = (s: string) => {
    if (s === 'active') return 'default';
    if (s === 'inactive') return 'secondary';
    return 'destructive';
};

watch([search, assetTypeId, status, manufacturer], () => {
    router.get('/assets', {
        search: search.value,
        asset_type_id: assetTypeId.value,
        status: status.value,
        manufacturer: manufacturer.value,
    }, { preserveState: true, replace: true });
});
</script>

<template>
    <Head title="Assets" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">Assets</h1>

            <div class="flex flex-wrap items-center gap-3">
                <Input v-model="search" placeholder="Search code, serial, model..." class="max-w-xs" />

                <Select v-model="assetTypeId">
                    <SelectTrigger class="w-45"><SelectValue placeholder="All Types" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem value="">All Types</SelectItem>
                        <SelectItem v-for="t in assetTypes" :key="t.id" :value="t.id">{{ t.name }}</SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="status">
                    <SelectTrigger class="w-37.5"><SelectValue placeholder="All Status" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem value="">All Status</SelectItem>
                        <SelectItem value="active">Active</SelectItem>
                        <SelectItem value="inactive">Inactive</SelectItem>
                        <SelectItem value="scrapped">Scrapped</SelectItem>
                    </SelectContent>
                </Select>

                <Select v-model="manufacturer">
                    <SelectTrigger class="w-45"><SelectValue placeholder="All Manufacturers" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem value="">All Manufacturers</SelectItem>
                        <SelectItem v-for="m in manufacturers" :key="m" :value="m">{{ m }}</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Code</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Manufacturer / Model</TableHead>
                            <TableHead>Serial</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="asset in assets.data" :key="asset.id">
                            <TableCell class="font-medium">{{ asset.asset_code }}</TableCell>
                            <TableCell>{{ asset.asset_type?.name ?? '-' }}</TableCell>
                            <TableCell>
                                <div>{{ asset.manufacturer ?? '-' }}</div>
                                <div class="text-xs text-muted-foreground">{{ asset.model ?? '' }}</div>
                            </TableCell>
                            <TableCell class="text-sm">{{ asset.serial_number ?? '-' }}</TableCell>
                            <TableCell>
                                <Badge :variant="statusVariant(asset.status)">{{ asset.status }}</Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <Link :href="`/assets/${asset.id}`">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="assets.data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                                No assets found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <div v-if="assets.links.length > 3" class="flex justify-center gap-1">
                <Link
                    v-for="link in assets.links" :key="link.label" :href="link.url ?? '#'"
                    class="rounded border px-3 py-1 text-sm"
                    :class="{ 'bg-primary text-primary-foreground': link.active, 'pointer-events-none opacity-50': !link.url }"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
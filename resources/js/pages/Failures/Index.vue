<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import { ref, watch } from 'vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Failures', href: '/failures' }];

const props = defineProps<{
    failures: any;
    assets: any[];
    filters: Record<string, string | undefined>;
}>();

const assetId = ref(props.filters.asset_id ?? '');
const failureType = ref(props.filters.failure_type ?? '');
const from = ref(props.filters.from ?? '');
const to = ref(props.filters.to ?? '');

watch([assetId, failureType, from, to], () => {
    router.get('/failures', {
        asset_id: assetId.value,
        failure_type: failureType.value,
        from: from.value,
        to: to.value,
    }, { preserveState: true, replace: true });
});
</script>

<template>
    <Head title="Failures" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Failures</h1>
                <Link href="/failures/create">
                    <Button size="sm">+ Record Failure</Button>
                </Link>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Select v-model="assetId">
                    <SelectTrigger class="w-50"><SelectValue placeholder="All Assets" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem value="">All Assets</SelectItem>
                        <SelectItem v-for="a in assets" :key="a.id" :value="a.id">{{ a.asset_code }}</SelectItem>
                    </SelectContent>
                </Select>
                <Input v-model="failureType" placeholder="Failure type..." class="max-w-xs" />
                <Input v-model="from" type="date" />
                <Input v-model="to" type="date" />
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Date</TableHead>
                            <TableHead>Asset</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Downtime</TableHead>
                            <TableHead>Reported By</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="f in failures.data" :key="f.id">
                            <TableCell class="text-sm">{{ new Date(f.failure_date).toLocaleString() }}</TableCell>
                            <TableCell>{{ f.asset?.asset_code ?? '-' }}</TableCell>
                            <TableCell>{{ f.failure_type }}</TableCell>
                            <TableCell>{{ f.downtime_hours ?? '-' }} h</TableCell>
                            <TableCell class="text-sm">{{ f.created_by?.name ?? '-' }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="`/failures/${f.id}`">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="failures.data.length === 0">
                            <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                                No failures recorded.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
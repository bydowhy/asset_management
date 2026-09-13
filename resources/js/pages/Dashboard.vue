<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Wrench, Package, CheckCircle, AlertTriangle } from '@lucide/vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
];

const props = defineProps<{
    stats: {
        total_equipment: number;
        total_assets: number;
        active_assets: number;
        recent_failures: number;
    };
    assetStatus: Record<string, number>;
    latestFailures: any[];
    equipmentAttention: any[];
    latestDocuments: any[];
    latestPhotos: any[];
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Stat Cards -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Equipment</CardTitle>
                        <Wrench class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_equipment }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Assets</CardTitle>
                        <Package class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_assets }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Active Assets</CardTitle>
                        <CheckCircle class="h-4 w-4 text-green-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.active_assets }}</div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Failures (30d)</CardTitle>
                        <AlertTriangle class="h-4 w-4 text-red-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.recent_failures }}</div>
                    </CardContent>
                </Card>
            </div>

            <!-- Widgets Row -->
            <div class="grid gap-4 md:grid-cols-2">
                <!-- Recent Failures -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Failures</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="failure in latestFailures" :key="failure.id" class="flex items-center justify-between border-b pb-2 last:border-0">
                                <div>
                                    <Link :href="`/assets/${failure.asset_id}`" class="font-medium hover:underline">
                                        {{ failure.asset?.asset_code ?? 'Unknown' }}
                                    </Link>
                                    <p class="text-sm text-muted-foreground">{{ failure.failure_type }}</p>
                                </div>
                                <span class="text-sm text-muted-foreground">{{ new Date(failure.failure_date).toLocaleDateString() }}</span>
                            </li>
                            <li v-if="latestFailures.length === 0" class="text-muted-foreground">No recent failures.</li>
                        </ul>
                    </CardContent>
                </Card>

                <!-- Equipment Attention -->
                <Card>
                    <CardHeader>
                        <CardTitle>Equipment Attention (90d)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="item in equipmentAttention" :key="item.id" class="flex items-center justify-between border-b pb-2 last:border-0">
                                <Link :href="`/equipment/${item.id}`" class="font-medium hover:underline">
                                    {{ item.asset_code }}
                                </Link>
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800">
                                    {{ item.failure_count }} failures
                                </span>
                            </li>
                            <li v-if="equipmentAttention.length === 0" class="text-muted-foreground">No data available.</li>
                        </ul>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
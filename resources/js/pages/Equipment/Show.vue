<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { Wrench, Package, AlertTriangle } from '@lucide/vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    equipment: any;
    currentAssets: any[];
    assetHistory: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Equipment', href: '/equipment' },
    { title: props.equipment.tag, href: `/equipment/${props.equipment.id}` },
];
</script>

<template>
    <Head :title="`${equipment.tag} - ${equipment.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Header -->
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ equipment.tag }} — {{ equipment.name }}</h1>
                    <p class="text-muted-foreground">
                        📍 {{ equipment.location?.name ?? 'Unknown location' }} ·
                        Type: {{ equipment.equipment_type ?? '-' }}
                    </p>
                </div>
                <!-- Tombol Edit bisa ditambahkan nanti -->
            </div>

            <!-- Tabs -->
            <Tabs default-value="current" class="w-full">
                <TabsList>
                    <TabsTrigger value="current">Current Assets</TabsTrigger>
                    <TabsTrigger value="history">History</TabsTrigger>
                    <TabsTrigger value="documents">Documents</TabsTrigger>
                    <TabsTrigger value="photos">Photos</TabsTrigger>
                </TabsList>

                <!-- Current Assets -->
                <TabsContent value="current" class="mt-4 space-y-4">
                    <Card v-for="asset in currentAssets" :key="asset.id">
                        <CardContent class="flex items-center justify-between p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10">
                                    <Wrench class="h-5 w-5 text-primary" />
                                </div>
                                <div>
                                    <p class="font-medium">{{ asset.asset_code }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ asset.asset_type?.name ?? 'Unknown type' }} ·
                                        {{ asset.manufacturer }} {{ asset.model }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Role: {{ asset.pivot?.relationship_role }} ·
                                        Installed: {{ new Date(asset.pivot?.installed_at).toLocaleDateString() }}
                                    </p>
                                </div>
                            </div>
                            <Link :href="`/assets/${asset.id}`">
                                <Badge variant="outline">View Asset →</Badge>
                            </Link>
                        </CardContent>
                    </Card>
                    <p v-if="currentAssets.length === 0" class="text-center text-muted-foreground">
                        No assets currently installed.
                    </p>
                </TabsContent>

                <!-- History -->
                <TabsContent value="history" class="mt-4 space-y-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Installation History</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ul class="space-y-4">
                                <li v-for="asset in assetHistory" :key="asset.pivot?.id" class="flex items-start gap-4">
                                    <div class="mt-1">
                                        <div
                                            class="h-3 w-3 rounded-full"
                                            :class="asset.pivot?.removed_at ? 'bg-red-500' : 'bg-green-500'"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="font-medium">{{ asset.asset_code }}</p>
                                            <Badge :variant="asset.pivot?.removed_at ? 'destructive' : 'default'">
                                                {{ asset.pivot?.removed_at ? 'Removed' : 'Active' }}
                                            </Badge>
                                        </div>
                                        <p class="text-sm text-muted-foreground">
                                            {{ asset.asset_type?.name }} · Role: {{ asset.pivot?.relationship_role }}
                                        </p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ new Date(asset.pivot?.installed_at).toLocaleDateString() }} →
                                            {{ asset.pivot?.removed_at ? new Date(asset.pivot.removed_at).toLocaleDateString() : 'now' }}
                                        </p>
                                        <p v-if="asset.pivot?.notes" class="mt-1 text-xs italic text-muted-foreground">
                                            {{ asset.pivot.notes }}
                                        </p>
                                    </div>
                                </li>
                                <li v-if="assetHistory.length === 0" class="text-muted-foreground">
                                    No history available.
                                </li>
                            </ul>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Documents (placeholder) -->
                <TabsContent value="documents" class="mt-4">
                    <Card>
                        <CardContent class="p-8 text-center text-muted-foreground">
                            Document management will be implemented in the next phase.
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Photos (placeholder) -->
                <TabsContent value="photos" class="mt-4">
                    <Card>
                        <CardContent class="p-8 text-center text-muted-foreground">
                            Photo gallery will be implemented in the next phase.
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
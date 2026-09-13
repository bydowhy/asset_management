<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { ref } from 'vue';
import { Wrench, Package, AlertTriangle } from '@lucide/vue';
import type { BreadcrumbItem } from '@/types';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';

const props = defineProps<{
    equipment: any;
    currentAssets: any[];
    assetHistory: any[];
}>();

const installDialogOpen = ref(false);
const installForm = useForm({
    asset_id: '',
    relationship_role: '',
    installed_at: new Date().toISOString().slice(0, 16),
    notes: '',
});

function submitInstall() {
    installForm.post(`/equipment/${props.equipment.id}/assets`, {
        onSuccess: () => { installDialogOpen.value = false; installForm.reset(); },
    });
}

const replaceDialogOpen = ref(false);
const replacingAssignment = ref<any>(null);
const replaceForm = useForm({
    asset_id: '',
    replaced_at: new Date().toISOString().slice(0, 16),
    close_relationships: true,
    notes: '',
});

function openReplace(assignment: any) {
    replacingAssignment.value = assignment;
    replaceForm.asset_id = '';
    replaceForm.replaced_at = new Date().toISOString().slice(0, 16);
    replaceForm.notes = '';
    replaceDialogOpen.value = true;
}

function submitReplace() {
    replaceForm.patch(`/assignments/${replacingAssignment.value.id}/replace`, {
        onSuccess: () => { replaceDialogOpen.value = false; replaceForm.reset(); },
    });
}

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
                    <!-- Tombol Install -->
                    <div class="flex justify-end">
                        <Dialog v-model:open="installDialogOpen">
                            <DialogTrigger as-child>
                                <Button size="sm">+ Install Asset</Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader><DialogTitle>Install Asset</DialogTitle></DialogHeader>
                                <div class="space-y-3 py-2">
                                    <div>
                                        <label class="text-sm font-medium">Asset ID</label>
                                        <Input v-model="installForm.asset_id" placeholder="UUID asset" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Role</label>
                                        <Input v-model="installForm.relationship_role" placeholder="pump / motor / inverter" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Installed At</label>
                                        <Input type="datetime-local" v-model="installForm.installed_at" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Notes</label>
                                        <Textarea v-model="installForm.notes" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button @click="submitInstall" :disabled="installForm.processing">Install</Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </div>

                    <!-- List current assets -->
                    <Card v-for="asset in currentAssets" :key="asset.id">
                        <CardContent class="flex items-center justify-between p-4">
                            <div>
                                <p class="font-medium">{{ asset.asset_code }}</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ asset.asset_type?.name }} · Role: {{ asset.pivot?.relationship_role }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    Installed: {{ new Date(asset.pivot?.installed_at).toLocaleDateString() }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <Button variant="outline" size="sm" @click="openReplace({ id: asset.pivot.id, asset_code: asset.asset_code })">
                                    Replace
                                </Button>
                                <Link :href="`/assets/${asset.id}`">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Dialog Replace -->
                    <Dialog v-model:open="replaceDialogOpen">
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Replace {{ replacingAssignment?.asset_code }}</DialogTitle>
                            </DialogHeader>
                            <div class="space-y-3 py-2">
                                <div>
                                    <label class="text-sm font-medium">New Asset ID</label>
                                    <Input v-model="replaceForm.asset_id" placeholder="UUID asset pengganti" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Replaced At</label>
                                    <Input type="datetime-local" v-model="replaceForm.replaced_at" />
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Notes</label>
                                    <Textarea v-model="replaceForm.notes" />
                                </div>
                            </div>
                            <DialogFooter>
                                <Button @click="submitReplace" :disabled="replaceForm.processing">Replace</Button>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
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
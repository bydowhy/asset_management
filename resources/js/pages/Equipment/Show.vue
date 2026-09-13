<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Wrench } from '@lucide/vue';
import { ref } from 'vue';

const props = defineProps<{
    equipment: any;
    currentAssets: any[];
    assetHistory: any[];
    documents: any[];
    photos: any[];
}>();

/* ===================== Install ===================== */
const installDialogOpen = ref(false);
const installForm = useForm({
    asset_id: '',
    relationship_role: '',
    installed_at: new Date().toISOString().slice(0, 16),
    notes: '',
});

function submitInstall() {
    installForm.post(`/equipment/${props.equipment.id}/assets`, {
        onSuccess: () => {
            installDialogOpen.value = false;
            installForm.reset();
        },
    });
}

/* ===================== Replace ===================== */
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
    if (!replacingAssignment.value) return;
    replaceForm.patch(`/assignments/${replacingAssignment.value.id}/replace`, {
        onSuccess: () => {
            replaceDialogOpen.value = false;
            replaceForm.reset();
        },
    });
}

/* ===================== Remove ===================== */
function removeAssignment(assignment: any) {
    const removedAt = prompt(
        'Tanggal lepas (YYYY-MM-DD HH:mm):',
        new Date().toISOString().slice(0, 16)
    );
    if (!removedAt) return;

    useForm({ removed_at: removedAt }).patch(`/assignments/${assignment.id}/remove`);
}
</script>

<template>
    <Head :title="`${equipment.tag} - ${equipment.name}`" />
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
            <Link :href="`/equipment/${equipment.id}/edit`">
                <Button variant="outline" size="sm">Edit</Button>
            </Link>
        </div>        

        <!-- Tabs -->
        <Tabs default-value="current" class="w-full">
            <TabsList>
                <TabsTrigger value="current">Current Assets</TabsTrigger>
                <TabsTrigger value="history">History</TabsTrigger>
                <TabsTrigger value="documents">Documents</TabsTrigger>
                <TabsTrigger value="photos">Photos</TabsTrigger>
            </TabsList>

            <!-- ============ Current Assets ============ -->
            <TabsContent value="current" class="mt-4 space-y-4">
                <div class="flex justify-end">
                    <Dialog v-model:open="installDialogOpen">
                        <DialogTrigger as-child>
                            <Button size="sm">+ Install Asset</Button>
                        </DialogTrigger>
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Install Asset</DialogTitle>
                            </DialogHeader>
                            <div class="space-y-3 py-2">
                                <div>
                                    <label class="text-sm font-medium">Asset ID</label>
                                    <Input
                                        v-model="installForm.asset_id"
                                        placeholder="UUID asset"
                                    />
                                    <p v-if="installForm.errors.asset_id" class="text-xs text-red-500">
                                        {{ installForm.errors.asset_id }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Role</label>
                                    <Input
                                        v-model="installForm.relationship_role"
                                        placeholder="pump / motor / inverter"
                                    />
                                    <p v-if="installForm.errors.relationship_role" class="text-xs text-red-500">
                                        {{ installForm.errors.relationship_role }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Installed At</label>
                                    <Input type="datetime-local" v-model="installForm.installed_at" />
                                    <p v-if="installForm.errors.installed_at" class="text-xs text-red-500">
                                        {{ installForm.errors.installed_at }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Notes</label>
                                    <Textarea v-model="installForm.notes" />
                                </div>
                            </div>
                            <DialogFooter>
                                <Button @click="submitInstall" :disabled="installForm.processing">
                                    Install
                                </Button>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
                </div>

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
                                    Installed:
                                    {{ new Date(asset.pivot?.installed_at).toLocaleDateString() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                @click="openReplace({
                                    id: asset.pivot.id,
                                    asset_code: asset.asset_code,
                                })"
                            >
                                Replace
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                @click="removeAssignment({ id: asset.pivot.id })"
                            >
                                Remove
                            </Button>
                            <Link :href="`/assets/${asset.id}`">
                                <Button variant="outline" size="sm">View</Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                <p
                    v-if="currentAssets.length === 0"
                    class="text-center text-muted-foreground"
                >
                    No assets currently installed.
                </p>

                <!-- Dialog Replace -->
                <Dialog v-model:open="replaceDialogOpen">
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>
                                Replace {{ replacingAssignment?.asset_code }}
                            </DialogTitle>
                        </DialogHeader>
                        <div class="space-y-3 py-2">
                            <div>
                                <label class="text-sm font-medium">New Asset ID</label>
                                <Input
                                    v-model="replaceForm.asset_id"
                                    placeholder="UUID asset pengganti"
                                />
                                <p v-if="replaceForm.errors.asset_id" class="text-xs text-red-500">
                                    {{ replaceForm.errors.asset_id }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium">Replaced At</label>
                                <Input type="datetime-local" v-model="replaceForm.replaced_at" />
                                <p v-if="replaceForm.errors.replaced_at" class="text-xs text-red-500">
                                    {{ replaceForm.errors.replaced_at }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium">Notes</label>
                                <Textarea v-model="replaceForm.notes" />
                            </div>
                        </div>
                        <DialogFooter>
                            <Button @click="submitReplace" :disabled="replaceForm.processing">
                                Replace
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
            </TabsContent>

            <!-- ============ History ============ -->
            <TabsContent value="history" class="mt-4 space-y-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Installation History</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-4">
                            <li
                                v-for="asset in assetHistory"
                                :key="asset.pivot?.id"
                                class="flex items-start gap-4"
                            >
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
                                        {{ asset.asset_type?.name }} · Role:
                                        {{ asset.pivot?.relationship_role }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ new Date(asset.pivot?.installed_at).toLocaleDateString() }} →
                                        {{
                                            asset.pivot?.removed_at
                                                ? new Date(asset.pivot.removed_at).toLocaleDateString()
                                                : 'now'
                                        }}
                                    </p>
                                    <p
                                        v-if="asset.pivot?.notes"
                                        class="mt-1 text-xs italic text-muted-foreground"
                                    >
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

            <!-- ============ Documents ============ -->
            <TabsContent value="documents" class="mt-4 space-y-4">
                <div class="flex justify-end">
                    <Link :href="`/documents/create?entity_type=equipment&entity_id=${equipment.id}`">
                        <Button size="sm">+ Upload Document</Button>
                    </Link>
                </div>

                <Card v-if="documents.length === 0">
                    <CardContent class="p-8 text-center text-muted-foreground">
                        Belum ada dokumen terkait equipment ini.
                    </CardContent>
                </Card>

                <Card v-for="doc in documents" :key="doc.id">
                    <CardContent class="flex items-center justify-between p-4">
                        <div>
                            <p class="font-medium">{{ doc.name }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ doc.type?.name }} · {{ (doc.file_size / 1024).toFixed(1) }} KB
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a :href="`/documents/${doc.id}/download`">
                                <Button variant="outline" size="sm">Download</Button>
                            </a>
                        </div>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- ============ Photos ============ -->
            <TabsContent value="photos" class="mt-4 space-y-4">
                <div class="flex justify-end">
                    <Link :href="`/photos/create?entity_type=equipment&entity_id=${equipment.id}`">
                        <Button size="sm">+ Upload Photo</Button>
                    </Link>
                </div>

                <p v-if="photos.length === 0" class="text-center text-muted-foreground">
                    Belum ada foto terkait equipment ini.
                </p>

                <div v-else class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div v-for="photo in photos" :key="photo.id" class="overflow-hidden rounded-lg border">
                        <img
                            :src="`/photos/${photo.id}/file`"
                            :alt="photo.caption ?? photo.file_name"
                            class="aspect-square w-full object-cover"
                            loading="lazy"
                        />
                        <p class="truncate p-2 text-xs">{{ photo.caption ?? photo.file_name }}</p>
                    </div>
                </div>
            </TabsContent>
        </Tabs>
    </div>
</template>
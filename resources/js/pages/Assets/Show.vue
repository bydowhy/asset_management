<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger,
} from '@/components/ui/dialog';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import { computed, ref } from 'vue';

const props = defineProps<{
    asset: any;
    currentRelationships: any[];
    relationshipHistory: any[];
    allAssets: any[];
    relationshipTypes: any[];
    documents: any[];
    photos: any[];
}>();

const statusVariant = (s: string) =>
    s === 'active' ? 'default' : s === 'inactive' ? 'secondary' : 'destructive';


/* ===================== Helper yang tahan data kosong ===================== */
// Backend bisa mengirim 'asset_type' (snake) atau 'assetType' (camel) tergantung
// konfigurasi serialisasi Laravel. Kita cek keduanya.
const assetType = computed(() =>
    props.asset.asset_type ?? props.asset.assetType ?? null
);

const definitions = computed(() =>
    assetType.value?.definitions ?? []
);

/* ===================== Spesifikasi ===================== */
const specDialogOpen = ref(false);

const initialSpecs: Record<string, string> = {};
for (const def of definitions.value) {
    const existing = (props.asset.specifications ?? []).find(
        (s: any) => s.definition_id === def.id
    );
    initialSpecs[def.id] = existing?.value ?? '';
}

const specForm = useForm({
    specifications: initialSpecs,
});

function openSpecDialog() {
    const fresh: Record<string, string> = {};
    for (const def of definitions.value) {
        const existing = (props.asset.specifications ?? []).find(
            (s: any) => s.definition_id === def.id
        );
        fresh[def.id] = existing?.value ?? '';
    }
    specForm.specifications = fresh;
    specDialogOpen.value = true;
}

function submitSpecs() {
    specForm.put(`/assets/${props.asset.id}/specifications`, {
        onSuccess: () => { specDialogOpen.value = false; },
    });
}

/* ===================== Relationship ===================== */
const relDialogOpen = ref(false);
const relForm = useForm({
    target_asset_id: '',
    relationship_type_id: '',
    valid_from: new Date().toISOString().slice(0, 16),
    description: '',
});

function submitRelationship() {
    relForm.post(`/assets/${props.asset.id}/relationships`, {
        onSuccess: () => { relDialogOpen.value = false; relForm.reset(); },
    });
}

function endRelationship(rel: any) {
    const validTo = prompt(
        'Tanggal berakhir (YYYY-MM-DD HH:mm):',
        new Date().toISOString().slice(0, 16)
    );
    if (!validTo) return;
    useForm({ valid_to: validTo }).patch(`/relationships/${rel.id}/end`);
}
</script>

<template>
    <Head :title="asset.asset_code" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <!-- Header -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ asset.asset_code }} — {{ assetType?.name }}</h1>
                <p class="text-muted-foreground">
                    {{ asset.manufacturer ?? '-' }} {{ asset.model ?? '' }}
                    · SN: {{ asset.serial_number ?? '-' }}
                </p>
            </div>
        <div class="flex items-center gap-2">
            <Badge :variant="statusVariant(asset.status)">{{ asset.status }}</Badge>
            <Link :href="`/assets/${asset.id}/edit`">
                <Button variant="outline" size="sm">Edit</Button>
            </Link>
        </div>

    </div>
        <Tabs default-value="overview">
            <TabsList>
                <TabsTrigger value="overview">Overview</TabsTrigger>
                <TabsTrigger value="specifications">Specifications</TabsTrigger>
                <TabsTrigger value="relationships">Relationships</TabsTrigger>
                <TabsTrigger value="failures">Failures</TabsTrigger>
                <TabsTrigger value="documents">Documents</TabsTrigger>
                <TabsTrigger value="photos">Photos</TabsTrigger>
            </TabsList>

            <!-- Overview -->
            <TabsContent value="overview" class="mt-4 space-y-4">
                <Card>
                    <CardHeader><CardTitle>Description</CardTitle></CardHeader>
                    <CardContent>
                        <p class="text-sm text-muted-foreground">
                            {{ asset.description || 'No description.' }}
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Current Installations</CardTitle></CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="ea in asset.equipment_assignments" :key="ea.id"
                                class="flex items-center justify-between border-b pb-2 last:border-0">
                                <div>
                                    <Link :href="`/equipment/${ea.equipment.id}`" class="font-medium hover:underline">
                                        {{ ea.equipment?.tag }} — {{ ea.equipment?.name }}
                                    </Link>
                                    <p class="text-xs text-muted-foreground">
                                        Role: {{ ea.relationship_role }} · Installed: {{ new Date(ea.installed_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <Badge :variant="ea.removed_at ? 'destructive' : 'default'">
                                    {{ ea.removed_at ? 'Removed' : 'Active' }}
                                </Badge>
                            </li>
                            <li v-if="asset.equipment_assignments.length === 0" class="text-muted-foreground">
                                Not installed on any equipment.
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Specifications -->
            <TabsContent value="specifications" class="mt-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle>Specifications</CardTitle>
                        <Dialog v-model:open="specDialogOpen">
                            <DialogTrigger as-child>
                                <Button size="sm" @click="openSpecDialog">Edit</Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader><DialogTitle>Edit Specifications</DialogTitle></DialogHeader>
                                <div class="space-y-3 max-h-[60vh] overflow-y-auto py-2">
                                    <div v-for="def in definitions" :key="def.id">
                                        <label class="text-sm font-medium">
                                            {{ def.name }}
                                            <span v-if="def.unit" class="text-muted-foreground">({{ def.unit }})</span>
                                            <span v-if="def.is_required" class="text-red-500">*</span>
                                        </label>
                                        <Input v-model="specForm.specifications[def.id]" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button @click="submitSpecs" :disabled="specForm.processing">Save</Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </CardHeader>
                    <CardContent>
                        <ul class="divide-y">
                            <li v-for="spec in asset.specifications" :key="spec.id"
                                class="flex justify-between py-2">
                                <span class="text-sm">{{ spec.definition?.name }}</span>
                                <span class="text-sm font-medium">
                                    {{ spec.value }} <span class="text-muted-foreground">{{ spec.definition?.unit }}</span>
                                </span>
                            </li>
                            <li v-if="asset.specifications.length === 0" class="py-4 text-center text-muted-foreground">
                                No specifications.
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Relationships -->
            <TabsContent value="relationships" class="mt-4 space-y-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle>Current Relationships</CardTitle>
                        <Dialog v-model:open="relDialogOpen">
                            <DialogTrigger as-child>
                                <Button size="sm">Add Relationship</Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader><DialogTitle>Add Relationship</DialogTitle></DialogHeader>
                                <div class="space-y-3 py-2">
                                    <div>
                                        <label class="text-sm font-medium">Type</label>
                                        <Select v-model="relForm.relationship_type_id">
                                            <SelectTrigger><SelectValue placeholder="Pilih tipe" /></SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="rt in relationshipTypes" :key="rt.id" :value="rt.id">
                                                    {{ rt.name }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Target Asset</label>
                                        <Select v-model="relForm.target_asset_id">
                                            <SelectTrigger><SelectValue placeholder="Pilih asset" /></SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="a in allAssets" :key="a.id" :value="a.id">
                                                    {{ a.asset_code }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Valid From</label>
                                        <Input type="datetime-local" v-model="relForm.valid_from" />
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium">Description</label>
                                        <Textarea v-model="relForm.description" />
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button @click="submitRelationship" :disabled="relForm.processing">Save</Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-3">
                            <li v-for="rel in currentRelationships" :key="rel.id"
                                class="flex items-start justify-between border-b pb-3 last:border-0">
                                <div>
                                    <p class="font-medium">
                                        <Badge>{{ rel.relationship_type?.name }}</Badge>
                                        → <Link :href="`/assets/${rel.target_asset.id}`" class="hover:underline">
                                            {{ rel.target_asset?.asset_code }}
                                        </Link>
                                    </p>
                                    <p class="text-xs text-muted-foreground mt-1">
                                        Since {{ new Date(rel.valid_from).toLocaleDateString() }}
                                        · {{ rel.description ?? '' }}
                                    </p>
                                </div>
                                <Button variant="outline" size="sm" @click="endRelationship(rel)">End</Button>
                            </li>
                            <li v-if="currentRelationships.length === 0" class="text-muted-foreground">
                                No current relationships.
                            </li>
                        </ul>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader><CardTitle>Relationship History</CardTitle></CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="rel in relationshipHistory" :key="rel.id"
                                class="flex justify-between py-2 border-b last:border-0 text-sm">
                                <div>
                                    <span class="font-medium">{{ rel.relationship_type?.name }}</span>
                                    → {{ rel.target_asset?.asset_code }}
                                </div>
                                <span class="text-muted-foreground">
                                    {{ new Date(rel.valid_from).toLocaleDateString() }} →
                                    {{ rel.valid_to ? new Date(rel.valid_to).toLocaleDateString() : 'now' }}
                                </span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Failures -->
            <TabsContent value="failures" class="mt-4">
                <Card>
                    <CardHeader><CardTitle>Failure History</CardTitle></CardHeader>
                    <CardContent>
                        <ul class="space-y-3">
                            <li v-for="f in asset.failures" :key="f.id" class="border-b pb-3 last:border-0">
                                <div class="flex justify-between">
                                    <p class="font-medium">{{ f.failure_type }}</p>
                                    <span class="text-xs text-muted-foreground">
                                        {{ new Date(f.failure_date).toLocaleDateString() }}
                                    </span>
                                </div>
                                <p class="text-sm text-muted-foreground">{{ f.symptom }}</p>
                                <p class="text-xs text-muted-foreground">
                                    Downtime: {{ f.downtime_hours ?? '-' }} h
                                </p>
                            </li>
                            <li v-if="asset.failures.length === 0" class="text-muted-foreground">
                                No failures recorded.
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </TabsContent>

            <!-- Media placeholder -->
            <TabsContent value="documents" class="mt-4 space-y-4">
                <div class="flex justify-end">
                    <Link :href="`/documents/create?entity_type=asset&entity_id=${asset.id}`">
                        <Button size="sm">+ Upload Document</Button>
                    </Link>
                </div>

                <Card v-if="documents.length === 0">
                    <CardContent class="p-8 text-center text-muted-foreground">
                        Belum ada dokumen terkait asset ini.
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

            <TabsContent value="photos" class="mt-4 space-y-4">
                <div class="flex justify-end">
                    <Link :href="`/photos/create?entity_type=asset&entity_id=${asset.id}`">
                        <Button size="sm">+ Upload Photo</Button>
                    </Link>
                </div>

                <p v-if="photos.length === 0" class="text-center text-muted-foreground">
                    Belum ada foto terkait asset ini.
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
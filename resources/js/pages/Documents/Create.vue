<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import { ref, computed } from 'vue';

const props = defineProps<{
    documentTypes: any[];
    equipmentList: { id: string; label: string }[];
    assetList: { id: string; label: string }[];
    preselected: {
        entity_type: string | null;
        entity_id: string | null;
    };
}>();

const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    name: '',
    document_type_id: '',
    description: '',
    file: null as File | null,
    entity_type: props.preselected.entity_type ?? 'none',
    entity_id: props.preselected.entity_id ?? '',
});

const entityOptions = computed(() => {
    if (form.entity_type === 'equipment') return props.equipmentList;
    if (form.entity_type === 'asset') return props.assetList;
    return [];
});

function onFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    form.file = target.files?.[0] ?? null;
    if (form.file && !form.name) {
        form.name = form.file.name;
    }
}

function submit() {
    form.transform((data) => ({
        ...data,
        entity_type: data.entity_type === 'none' ? null : data.entity_type,
        entity_id: data.entity_type === 'none' ? null : data.entity_id,
    })).post('/documents', { forceFormData: true });
}
</script>

<template>
    <Head title="Upload Document" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader><CardTitle>Upload Document</CardTitle></CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">File *</label>
                    <Input
                        ref="fileInput"
                        type="file"
                        accept=".pdf,.docx,.xlsx,.pptx"
                        @change="onFileChange"
                    />
                    <p class="mt-1 text-xs text-muted-foreground">
                        PDF, DOCX, XLSX, PPTX · Max 20 MB
                    </p>
                    <p v-if="form.errors.file" class="text-xs text-red-500">{{ form.errors.file }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Document Name *</label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Document Type *</label>
                    <Select v-model="form.document_type_id">
                        <SelectTrigger><SelectValue placeholder="Pilih tipe" /></SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="t in documentTypes" :key="t.id" :value="t.id">
                                {{ t.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.document_type_id" class="text-xs text-red-500">
                        {{ form.errors.document_type_id }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>

                <hr />

                <div>
                    <label class="text-sm font-medium">Link to Entity</label>
                    <Select v-model="form.entity_type" @update:model-value="form.entity_id = ''">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="none">— Tidak dilink —</SelectItem>
                            <SelectItem value="equipment">Equipment</SelectItem>
                            <SelectItem value="asset">Asset</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div v-if="entityOptions.length > 0">
                    <label class="text-sm font-medium">Pilih {{ form.entity_type }}</label>
                    <Select v-model="form.entity_id">
                        <SelectTrigger><SelectValue placeholder="Pilih..." /></SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="item in entityOptions" :key="item.id" :value="item.id">
                                {{ item.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.entity_id" class="text-xs text-red-500">
                        {{ form.errors.entity_id }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link href="/documents">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Upload</Button>
        </div>
    </div>
</template>
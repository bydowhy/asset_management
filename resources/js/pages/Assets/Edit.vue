<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import { computed } from 'vue';

const props = defineProps<{
    asset: any;
    assetTypes: any[];
}>();

// Bangun initial specifications dari data yang ada
const initialSpecs: Record<string, string> = {};
for (const spec of props.asset.specifications ?? []) {
    initialSpecs[spec.definition_id] = spec.value;
}

const form = useForm({
    asset_code: props.asset.asset_code,
    asset_type_id: props.asset.asset_type_id,
    manufacturer: props.asset.manufacturer ?? '',
    model: props.asset.model ?? '',
    serial_number: props.asset.serial_number ?? '',
    status: props.asset.status,
    description: props.asset.description ?? '',
    specifications: initialSpecs,
});

const selectedType = computed(() =>
    props.assetTypes.find((t) => t.id === form.asset_type_id)
);

const definitions = computed(() => selectedType.value?.definitions ?? []);

function onTypeChange(value: any) {
    form.asset_type_id = value;
    form.specifications = {};
}

function submit() {
    form.put(`/assets/${props.asset.id}`);
}

function destroy() {
    if (!confirm(`Hapus asset ${props.asset.asset_code}?`)) return;
    useForm({}).delete(`/assets/${props.asset.id}`);
}
</script>

<template>
    <Head :title="`Edit ${asset.asset_code}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>Edit Asset</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Asset Code *</label>
                    <Input v-model="form.asset_code" />
                    <p v-if="form.errors.asset_code" class="text-xs text-red-500">
                        {{ form.errors.asset_code }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Asset Type *</label>
                    <Select :model-value="form.asset_type_id" @update:model-value="onTypeChange">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="t in assetTypes" :key="t.id" :value="t.id">
                                {{ t.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Manufacturer</label>
                        <Input v-model="form.manufacturer" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Model</label>
                        <Input v-model="form.model" />
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium">Serial Number</label>
                    <Input v-model="form.serial_number" />
                </div>

                <div>
                    <label class="text-sm font-medium">Status *</label>
                    <Select v-model="form.status">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="active">Active</SelectItem>
                            <SelectItem value="inactive">Inactive</SelectItem>
                            <SelectItem value="scrapped">Scrapped</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>
            </CardContent>
        </Card>

        <Card v-if="definitions.length > 0">
            <CardHeader>
                <CardTitle>Specifications</CardTitle>
            </CardHeader>
            <CardContent class="space-y-3">
                <div v-for="def in definitions" :key="def.id">
                    <label class="text-sm font-medium">
                        {{ def.name }}
                        <span v-if="def.unit" class="text-muted-foreground">({{ def.unit }})</span>
                        <span v-if="def.is_required" class="text-red-500">*</span>
                    </label>
                    <Input v-model="form.specifications[def.id]" :placeholder="def.name" />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-between">
            <Button variant="destructive" @click="destroy">Delete</Button>
            <div class="flex gap-2">
                <Link :href="`/assets/${asset.id}`">
                    <Button variant="outline">Cancel</Button>
                </Link>
                <Button @click="submit" :disabled="form.processing">Save</Button>
            </div>
        </div>
    </div>
</template>
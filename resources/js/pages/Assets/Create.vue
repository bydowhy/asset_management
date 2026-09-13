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
    assetTypes: any[];
}>();

const form = useForm({
    asset_code: '',
    asset_type_id: '',
    manufacturer: '',
    model: '',
    serial_number: '',
    status: 'active',
    description: '',
    specifications: {} as Record<string, string>,
});

// Definitions dari asset type yang dipilih
const selectedType = computed(() =>
    props.assetTypes.find((t) => t.id === form.asset_type_id)
);

const definitions = computed(() => selectedType.value?.definitions ?? []);

// Reset spec ketika asset type berubah
function onTypeChange(value: any) {
    form.asset_type_id = value;
    form.specifications = {};
}

function submit() {
    form.post('/assets');
}
</script>

<template>
    <Head title="Create Asset" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>New Asset</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Asset Code *</label>
                    <Input v-model="form.asset_code" placeholder="PMP-0002" />
                    <p v-if="form.errors.asset_code" class="text-xs text-red-500">
                        {{ form.errors.asset_code }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Asset Type *</label>
                    <Select :model-value="form.asset_type_id" @update:model-value="onTypeChange">
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih tipe asset" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="t in assetTypes" :key="t.id" :value="t.id">
                                {{ t.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.asset_type_id" class="text-xs text-red-500">
                        {{ form.errors.asset_type_id }}
                    </p>
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

        <!-- Dynamic Specifications -->
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
                    <Input
                        v-model="form.specifications[def.id]"
                        :placeholder="def.name"
                    />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link href="/assets">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Save</Button>
        </div>
    </div>
</template>
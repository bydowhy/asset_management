<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
    assetType: any;
}>();

/* ==================== Form 1: Asset Type Info ==================== */
const typeForm = useForm({
    name: props.assetType.name,
    code: props.assetType.code,
    description: props.assetType.description ?? '',
});

function submitType() {
    typeForm.put(`/asset-types/${props.assetType.id}`);
}

function destroyType() {
    if (!confirm(`Hapus asset type "${props.assetType.name}"?`)) return;
    router.delete(`/asset-types/${props.assetType.id}`);
}

/* ==================== Form 2: Definitions ==================== */
type DefinitionRow = {
    id: string | null;
    name: string;
    code: string;
    data_type: string;
    unit: string;
    is_required: boolean;
    sort_order: number;
};

const initialDefs: DefinitionRow[] = (props.assetType.definitions ?? []).map((d: any) => ({
    id: d.id,
    name: d.name,
    code: d.code,
    data_type: d.data_type,
    unit: d.unit ?? '',
    is_required: !!d.is_required,
    sort_order: d.sort_order,
}));

const defForm = useForm({
    definitions: initialDefs as DefinitionRow[],
});

const dataTypes = [
    { value: 'decimal', label: 'Decimal' },
    { value: 'integer', label: 'Integer' },
    { value: 'varchar', label: 'Text (Varchar)' },
    { value: 'boolean', label: 'Boolean' },
    { value: 'text', label: 'Long Text' },
];

function addRow() {
    const nextOrder = defForm.definitions.length > 0
        ? Math.max(...defForm.definitions.map((d) => d.sort_order)) + 1
        : 1;

    defForm.definitions = [
        ...defForm.definitions,
        {
            id: null,
            name: '',
            code: '',
            data_type: 'decimal',
            unit: '',
            is_required: false,
            sort_order: nextOrder,
        },
    ];
}

function removeRow(index: number) {
    if (!confirm('Hapus baris ini?')) return;
    const copy = [...defForm.definitions];
    copy.splice(index, 1);
    defForm.definitions = copy;
}

function submitDefinitions() {
    defForm.put(`/asset-types/${props.assetType.id}/definitions`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="`Edit ${assetType.name}`" />
    <div class="mx-auto max-w-5xl space-y-6 p-4">
        <!-- ============ Card 1: Asset Type Info ============ -->
        <Card>
            <CardHeader>
                <CardTitle>Asset Type</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Code *</label>
                    <Input v-model="typeForm.code" />
                    <p v-if="typeForm.errors.code" class="text-xs text-red-500">{{ typeForm.errors.code }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="typeForm.name" />
                    <p v-if="typeForm.errors.name" class="text-xs text-red-500">{{ typeForm.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="typeForm.description" />
                </div>

                <div class="flex justify-between pt-2">
                    <Button variant="destructive" @click="destroyType">Delete</Button>
                    <div class="flex gap-2">
                        <Link href="/asset-types">
                            <Button variant="outline">Back</Button>
                        </Link>
                        <Button @click="submitType" :disabled="typeForm.processing">Save Type</Button>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- ============ Card 2: Definitions ============ -->
        <Card>
            <CardHeader class="flex flex-row items-center justify-between">
                <div>
                    <CardTitle>Specifications (Definitions)</CardTitle>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Field dinamis yang akan muncul saat mengisi spesifikasi asset bertipe ini.
                    </p>
                </div>
                <Button size="sm" @click="addRow">+ Add Row</Button>
            </CardHeader>
            <CardContent>
                <div
                    v-if="defForm.errors.definitions"
                    class="mb-3 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"
                >
                    {{ defForm.errors.definitions }}
                </div>

                <div class="rounded-md border overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-12">#</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Code</TableHead>
                                <TableHead>Data Type</TableHead>
                                <TableHead>Unit</TableHead>
                                <TableHead class="text-center">Required</TableHead>
                                <TableHead class="w-20 text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="(def, index) in defForm.definitions"
                                :key="def.id ?? `new-${index}`"
                            >
                                <TableCell class="text-muted-foreground">
                                    {{ def.sort_order }}
                                </TableCell>

                                <TableCell>
                                    <Input v-model="def.name" placeholder="Power" />
                                </TableCell>

                                <TableCell>
                                    <Input v-model="def.code" placeholder="power" class="font-mono text-sm" />
                                    <p
                                        v-if="defForm.errors[`definitions.${index}.code`]"
                                        class="text-xs text-red-500"
                                    >
                                        {{ defForm.errors[`definitions.${index}.code`] }}
                                    </p>
                                </TableCell>

                                <TableCell>
                                    <Select v-model="def.data_type">
                                        <SelectTrigger><SelectValue /></SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="dt in dataTypes"
                                                :key="dt.value"
                                                :value="dt.value"
                                            >
                                                {{ dt.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </TableCell>

                                <TableCell>
                                    <Input v-model="def.unit" placeholder="kW" />
                                </TableCell>

                                <TableCell class="text-center">
                                    <input
                                        type="checkbox"
                                        v-model="def.is_required"
                                        class="h-4 w-4 rounded border-gray-300"
                                    />
                                </TableCell>

                                <TableCell class="text-right">
                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="removeRow(index)"
                                    >
                                        Hapus
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="defForm.definitions.length === 0">
                                <TableCell colspan="7" class="h-24 text-center text-muted-foreground">
                                    Belum ada specifications. Klik <strong>+ Add Row</strong> untuk menambahkan.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <div class="mt-4 flex justify-end">
                    <Button @click="submitDefinitions" :disabled="defForm.processing">
                        Save Specifications
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
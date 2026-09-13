<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';

const props = defineProps<{
    location: any;
    parents: { id: string; label: string }[];
}>();

const form = useForm({
    parent_id: props.location.parent_id ?? 'none',
    name: props.location.name,
    code: props.location.code,
    description: props.location.description ?? '',
});

function submit() {
    form.transform((data) => ({
        ...data,
        parent_id: data.parent_id === 'none' ? null : data.parent_id,
    })).put(`/locations/${props.location.id}`);
}
</script>

<template>
    <Head :title="`Edit ${location.name}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>Edit Location</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Parent Location</label>
                    <Select v-model="form.parent_id">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="none">— Tidak ada (root) —</SelectItem>
                            <SelectItem v-for="p in parents" :key="p.id" :value="p.id">
                                {{ p.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.parent_id" class="text-xs text-red-500">
                        {{ form.errors.parent_id }}
                    </p>
                    <p class="text-xs text-muted-foreground mt-1">
                        Location ini dan turunannya tidak muncul di daftar parent untuk menghindari circular.
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Code *</label>
                    <Input v-model="form.code" />
                    <p v-if="form.errors.code" class="text-xs text-red-500">
                        {{ form.errors.code }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link href="/locations">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Save</Button>
        </div>
    </div>
</template>
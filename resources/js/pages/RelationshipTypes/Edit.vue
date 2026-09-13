<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    type: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Relationship Types', href: '/relationship-types' },
    { title: props.type.name, href: `/relationship-types/${props.type.id}/edit` },
];

const form = useForm({
    name: props.type.name,
    code: props.type.code,
    description: props.type.description ?? '',
});

function submit() {
    form.put(`/relationship-types/${props.type.id}`);
}

function destroy() {
    if (!confirm(`Hapus relationship type "${props.type.name}"?`)) return;
    router.delete(`/relationship-types/${props.type.id}`);
}
</script>

<template>
    <Head :title="`Edit ${type.name}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>Edit Relationship Type</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Code *</label>
                    <Input v-model="form.code" />
                    <p v-if="form.errors.code" class="text-xs text-red-500">{{ form.errors.code }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-between">
            <Button variant="destructive" @click="destroy">Delete</Button>
            <div class="flex gap-2">
                <Link href="/relationship-types">
                    <Button variant="outline">Cancel</Button>
                </Link>
                <Button @click="submit" :disabled="form.processing">Save</Button>
            </div>
        </div>
    </div>
</template>
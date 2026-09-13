<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    assets: any[];
    preselectedAssetId?: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Failures', href: '/failures' },
    { title: 'Create', href: '/failures/create' },
];

const form = useForm({
    asset_id: props.preselectedAssetId ?? '',
    failure_date: new Date().toISOString().slice(0, 16),
    failure_type: '',
    symptom: '',
    root_cause: '',
    action_taken: '',
    downtime_hours: '',
    description: '',
});

function submit() {
    form.post('/failures');
}
</script>

<template>
    <Head title="Record Failure" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-3xl space-y-4 p-4">
            <Card>
                <CardHeader><CardTitle>Record Failure</CardTitle></CardHeader>
                <CardContent class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Asset *</label>
                        <Select v-model="form.asset_id">
                            <SelectTrigger><SelectValue placeholder="Pilih asset" /></SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="a in assets" :key="a.id" :value="a.id">{{ a.asset_code }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Failure Date *</label>
                        <Input type="datetime-local" v-model="form.failure_date" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Failure Type *</label>
                        <Input v-model="form.failure_type" placeholder="Bearing Failure" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Symptom</label>
                        <Textarea v-model="form.symptom" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Root Cause</label>
                        <Textarea v-model="form.root_cause" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Action Taken</label>
                        <Textarea v-model="form.action_taken" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Downtime (hours)</label>
                        <Input type="number" step="0.1" v-model="form.downtime_hours" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Description</label>
                        <Textarea v-model="form.description" />
                    </div>
                </CardContent>
            </Card>

            <div class="flex justify-end gap-2">
                <Button variant="outline" @click="$inertia.visit('/failures')">Cancel</Button>
                <Button @click="submit" :disabled="form.processing">Save</Button>
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import { computed } from 'vue';

const props = defineProps<{
    assets: any[];
    preselectedAssetId?: string;
}>();

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

const hasErrors = computed(() => Object.keys(form.errors).length > 0);

function submit() {
    form.post('/failures');
}
</script>

<template>
    <Head title="Record Failure" />
    <div class="max-w-3xl space-y-4 p-4">
        <!-- Banner error global -->
        <div
            v-if="hasErrors"
            class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"
        >
            <p class="font-medium">Form tidak lengkap</p>
            <p class="text-xs">Periksa field yang ditandai merah di bawah.</p>
        </div>

        <Card>
            <CardHeader><CardTitle>Record Failure</CardTitle></CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Asset *</label>
                    <Select v-model="form.asset_id">
                        <SelectTrigger :class="{ 'border-red-500': form.errors.asset_id }">
                            <SelectValue placeholder="Pilih asset" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="a in assets" :key="a.id" :value="a.id">
                                {{ a.asset_code }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.asset_id" class="mt-1 text-xs text-red-500">
                        {{ form.errors.asset_id }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Failure Date *</label>
                    <Input
                        type="datetime-local"
                        v-model="form.failure_date"
                        :class="{ 'border-red-500': form.errors.failure_date }"
                    />
                    <p v-if="form.errors.failure_date" class="mt-1 text-xs text-red-500">
                        {{ form.errors.failure_date }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Failure Type *</label>
                    <Input
                        v-model="form.failure_type"
                        placeholder="Bearing Failure"
                        :class="{ 'border-red-500': form.errors.failure_type }"
                    />
                    <p v-if="form.errors.failure_type" class="mt-1 text-xs text-red-500">
                        {{ form.errors.failure_type }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Symptom</label>
                    <Textarea v-model="form.symptom" />
                    <p v-if="form.errors.symptom" class="mt-1 text-xs text-red-500">
                        {{ form.errors.symptom }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Root Cause</label>
                    <Textarea v-model="form.root_cause" />
                    <p v-if="form.errors.root_cause" class="mt-1 text-xs text-red-500">
                        {{ form.errors.root_cause }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Action Taken</label>
                    <Textarea v-model="form.action_taken" />
                    <p v-if="form.errors.action_taken" class="mt-1 text-xs text-red-500">
                        {{ form.errors.action_taken }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Downtime (hours)</label>
                    <Input
                        type="number"
                        step="0.1"
                        v-model="form.downtime_hours"
                        :class="{ 'border-red-500': form.errors.downtime_hours }"
                    />
                    <p v-if="form.errors.downtime_hours" class="mt-1 text-xs text-red-500">
                        {{ form.errors.downtime_hours }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                    <p v-if="form.errors.description" class="mt-1 text-xs text-red-500">
                        {{ form.errors.description }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Button variant="outline" @click="$inertia.visit('/failures')">Cancel</Button>
            <Button @click="submit" :disabled="form.processing">Save</Button>
        </div>
    </div>
</template>
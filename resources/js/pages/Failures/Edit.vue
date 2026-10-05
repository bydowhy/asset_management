<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

const props = defineProps<{
    failure: any;
}>();

const form = useForm({
    failure_type: props.failure.failure_type,
    symptom: props.failure.symptom ?? '',
    root_cause: props.failure.root_cause ?? '',
    action_taken: props.failure.action_taken ?? '',
    downtime_hours: props.failure.downtime_hours ?? '',
    description: props.failure.description ?? '',
});

function submit() {
    form.put(`/failures/${props.failure.id}`);
}
</script>

<template>
    <Head :title="`Edit Failure - ${failure.failure_type}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <!-- Info banner: field yang tidak bisa diubah -->
        <div class="rounded-md border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800">
            <strong>Catatan:</strong> Asset dan tanggal failure tidak dapat diubah setelah
            laporan dibuat. Hubungi admin jika perlu koreksi.
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Edit Failure</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <!-- Info statis (read-only) -->
                <div class="grid grid-cols-2 gap-4 rounded-md bg-gray-50 p-3 text-sm">
                    <div>
                        <span class="font-medium text-muted-foreground">Asset:</span>
                        <span class="ml-2 font-mono text-gray-700">{{ failure.asset?.asset_code }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-muted-foreground">Failure Date:</span>
                        <span class="ml-2 text-gray-700">
                            {{ new Date(failure.failure_date).toLocaleString() }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium">Failure Type *</label>
                    <Input v-model="form.failure_type" placeholder="Bearing Failure" />
                    <p v-if="form.errors.failure_type" class="text-xs text-red-500">
                        {{ form.errors.failure_type }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Symptom</label>
                    <Textarea v-model="form.symptom" placeholder="High vibration on DE side" />
                    <p v-if="form.errors.symptom" class="text-xs text-red-500">
                        {{ form.errors.symptom }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Root Cause</label>
                    <Textarea v-model="form.root_cause" placeholder="Bearing degradation..." />
                    <p v-if="form.errors.root_cause" class="text-xs text-red-500">
                        {{ form.errors.root_cause }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Action Taken</label>
                    <Textarea v-model="form.action_taken" placeholder="Motor replaced with..." />
                    <p v-if="form.errors.action_taken" class="text-xs text-red-500">
                        {{ form.errors.action_taken }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Downtime (hours)</label>
                    <Input type="number" step="0.1" v-model="form.downtime_hours" />
                    <p v-if="form.errors.downtime_hours" class="text-xs text-red-500">
                        {{ form.errors.downtime_hours }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                    <p v-if="form.errors.description" class="text-xs text-red-500">
                        {{ form.errors.description }}
                    </p>
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link :href="`/failures/${failure.id}`">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Save Changes</Button>
        </div>
    </div>
</template>
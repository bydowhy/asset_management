<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

const props = defineProps<{ failure: any }>();
</script>

<template>
    <Head title="Failure Detail" />
    <div class="max-w-3xl space-y-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">{{ failure.failure_type }}</h1>
            <Link :href="`/failures/${failure.id}/edit`">
                <Button size="sm" variant="outline">Edit</Button>
            </Link>
        </div>

        <Card>
            <CardHeader><CardTitle>Detail</CardTitle></CardHeader>
            <CardContent class="space-y-3 text-sm">
                <div><span class="font-medium">Asset:</span>
                    <Link :href="`/assets/${failure.asset_id}`" class="text-blue-600 hover:underline">
                        {{ failure.asset?.asset_code }}
                    </Link>
                </div>
                <div><span class="font-medium">Failure Date:</span> {{ new Date(failure.failure_date).toLocaleString() }}</div>
                <div><span class="font-medium">Downtime:</span> {{ failure.downtime_hours ?? '-' }} h</div>
                <div><span class="font-medium">Reported By:</span> {{ failure.created_by?.name ?? '-' }}</div>
                <div><span class="font-medium">Symptom:</span><p class="text-muted-foreground">{{ failure.symptom ?? '-' }}</p></div>
                <div><span class="font-medium">Root Cause:</span><p class="text-muted-foreground">{{ failure.root_cause ?? '-' }}</p></div>
                <div><span class="font-medium">Action Taken:</span><p class="text-muted-foreground">{{ failure.action_taken ?? '-' }}</p></div>
            </CardContent>
        </Card>
    </div>
</template>
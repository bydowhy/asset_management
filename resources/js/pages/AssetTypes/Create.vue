<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';

const form = useForm({
    name: '',
    code: '',
    description: '',
});

function submit() {
    form.post('/asset-types');
}
</script>

<template>
    <Head title="Create Asset Type" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>New Asset Type</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Code *</label>
                    <Input v-model="form.code" placeholder="MOTOR" />
                    <p class="mt-1 text-xs text-muted-foreground">
                        Huruf kapital, unik. Dipakai sebagai identifier di database.
                    </p>
                    <p v-if="form.errors.code" class="text-xs text-red-500">{{ form.errors.code }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="form.name" placeholder="Electric Motor" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link href="/asset-types">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Save</Button>
        </div>
    </div>
</template>
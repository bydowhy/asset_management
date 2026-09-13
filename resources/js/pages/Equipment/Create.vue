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
    locations: { id: string; name: string; code: string }[];
}>();

const form = useForm({
    location_id: '',
    tag: '',
    name: '',
    description: '',
    equipment_type: '',
});

function submit() {
    form.post('/equipment');
}
</script>

<template>
    <Head title="Create Equipment" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>New Equipment</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Location *</label>
                    <Select v-model="form.location_id">
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih lokasi" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="loc in locations" :key="loc.id" :value="loc.id">
                                {{ loc.name }} ({{ loc.code }})
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.location_id" class="text-xs text-red-500">
                        {{ form.errors.location_id }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Tag *</label>
                    <Input v-model="form.tag" placeholder="P-201" />
                    <p v-if="form.errors.tag" class="text-xs text-red-500">{{ form.errors.tag }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="form.name" placeholder="Fan Pump P-201" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Type</label>
                    <Input v-model="form.equipment_type" placeholder="Pump / Valve / Tank" />
                </div>

                <div>
                    <label class="text-sm font-medium">Description</label>
                    <Textarea v-model="form.description" />
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-end gap-2">
            <Link href="/equipment">
                <Button variant="outline">Cancel</Button>
            </Link>
            <Button @click="submit" :disabled="form.processing">Save</Button>
        </div>
    </div>
</template>
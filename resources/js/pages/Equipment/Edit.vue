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
    equipment: any;
    locations: { id: string; name: string; code: string }[];
}>();

const form = useForm({
    location_id: props.equipment.location_id,
    tag: props.equipment.tag,
    name: props.equipment.name,
    description: props.equipment.description ?? '',
    equipment_type: props.equipment.equipment_type ?? '',
});

function submit() {
    form.put(`/equipment/${props.equipment.id}`);
}

function destroy() {
    if (!confirm('Hapus equipment ini?')) return;
    useForm({}).delete(`/equipment/${props.equipment.id}`);
}
</script>

<template>
    <Head :title="`Edit ${equipment.tag}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader>
                <CardTitle>Edit Equipment</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Location *</label>
                    <Select v-model="form.location_id">
                        <SelectTrigger><SelectValue /></SelectTrigger>
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
                    <Input v-model="form.tag" />
                    <p v-if="form.errors.tag" class="text-xs text-red-500">{{ form.errors.tag }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Name *</label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Type</label>
                    <Input v-model="form.equipment_type" />
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
                <Link :href="`/equipment/${equipment.id}`">
                    <Button variant="outline">Cancel</Button>
                </Link>
                <Button @click="submit" :disabled="form.processing">Save</Button>
            </div>
        </div>
    </div>
</template>
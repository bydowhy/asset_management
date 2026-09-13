<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ref, watch } from 'vue';

const props = defineProps<{
    equipment: any;
    locations: any[];
    filters: {
        location_id?: string;
        search?: string;
    };
}>();

const search = ref(props.filters.search ?? '');
const locationId = ref(props.filters.location_id || 'all'); // sentinel

watch([search, locationId], () => {
    router.get('/equipment', {
        search: search.value,
        location_id: locationId.value === 'all' ? '' : locationId.value,
    }, {
        preserveState: true,
        replace: true,
    });
});
</script>

<template>
    <Head title="Equipment" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Equipment</h1>
            <!-- Tombol New Equipment bisa ditambahkan nanti -->
             <Link href="/equipment/create">
                <Button size="sm">+ New Equipment</Button>
            </Link>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-4">
            <Input
                v-model="search"
                placeholder="Search by tag or name..."
                class="max-w-xs"
            />
            <Select v-model="locationId">
                <SelectTrigger class="w-50">
                    <SelectValue placeholder="All Locations" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Locations</SelectItem>
                    <SelectItem v-for="loc in locations" :key="loc.id" :value="loc.id">
                        {{ loc.name }} ({{ loc.code }})
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Tag</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Location</TableHead>
                        <TableHead>Type</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="item in equipment.data" :key="item.id">
                        <TableCell class="font-medium">{{ item.tag }}</TableCell>
                        <TableCell>{{ item.name }}</TableCell>
                        <TableCell>{{ item.location?.name ?? '-' }}</TableCell>
                        <TableCell>{{ item.equipment_type ?? '-' }}</TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link :href="`/equipment/${item.id}`">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                                <Link :href="`/equipment/${item.id}/edit`">
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="equipment.data.length === 0">
                        <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                            No equipment found.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div v-if="equipment.links.length > 3" class="flex justify-center gap-1">
            <Link
                v-for="link in equipment.links"
                :key="link.label"
                :href="link.url ?? '#'"
                class="rounded border px-3 py-1 text-sm"
                :class="{
                    'bg-primary text-primary-foreground': link.active,
                    'pointer-events-none opacity-50': !link.url,
                }"
                v-html="link.label"
            />
        </div>
    </div>
</template>
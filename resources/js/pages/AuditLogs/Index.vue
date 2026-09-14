<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';
import { ref, watch } from 'vue';

const props = defineProps<{
    logs: any;
    users: any[];
    filters: Record<string, string | undefined>;
}>();

const userId = ref(props.filters.user_id || 'all');
const action = ref(props.filters.action || 'all');
const entityType = ref(props.filters.entity_type || 'all');
const from = ref(props.filters.from ?? '');
const to = ref(props.filters.to ?? '');

watch([userId, action, entityType, from, to], () => {
    router.get('/audit-logs', {
        user_id: userId.value === 'all' ? '' : userId.value,
        action: action.value === 'all' ? '' : action.value,
        entity_type: entityType.value === 'all' ? '' : entityType.value,
        from: from.value,
        to: to.value,
    }, { preserveState: true, replace: true });
});

const actionVariant = (a: string) => {
    if (a === 'create') return 'default';
    if (a === 'update') return 'secondary';
    if (a === 'delete') return 'destructive';
    return 'outline';
};
</script>

<template>
    <Head title="Audit Logs" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <h1 class="text-2xl font-bold">Audit Logs</h1>

        <div class="flex flex-wrap items-center gap-3">
            <Select v-model="userId">
                <SelectTrigger class="w-50"><SelectValue placeholder="All Users" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Users</SelectItem>
                    <SelectItem v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="action">
                <SelectTrigger class="w-37.5"><SelectValue placeholder="All Actions" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Actions</SelectItem>
                    <SelectItem value="create">Create</SelectItem>
                    <SelectItem value="update">Update</SelectItem>
                    <SelectItem value="delete">Delete</SelectItem>
                    <SelectItem value="login">Login</SelectItem>
                </SelectContent>
            </Select>

            <Select v-model="entityType">
                <SelectTrigger class="w-45"><SelectValue placeholder="All Entities" /></SelectTrigger>
                <SelectContent>
                    <SelectItem value="all">All Entities</SelectItem>
                    <SelectItem value="user">User</SelectItem>
                    <SelectItem value="equipment">Equipment</SelectItem>
                    <SelectItem value="asset">Asset</SelectItem>
                    <SelectItem value="failure">Failure</SelectItem>
                    <SelectItem value="document">Document</SelectItem>
                    <SelectItem value="photo">Photo</SelectItem>
                </SelectContent>
            </Select>

            <Input v-model="from" type="date" class="w-37.5" />
            <Input v-model="to" type="date" class="w-37.5" />
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Time</TableHead>
                        <TableHead>User</TableHead>
                        <TableHead>Action</TableHead>
                        <TableHead>Entity</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead>IP</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="log in logs.data" :key="log.id">
                        <TableCell class="text-sm">
                            {{ new Date(log.created_at).toLocaleString() }}
                        </TableCell>
                        <TableCell class="text-sm">{{ log.user?.name ?? '—' }}</TableCell>
                        <TableCell>
                            <Badge :variant="actionVariant(log.action)">{{ log.action }}</Badge>
                        </TableCell>
                        <TableCell class="text-sm">
                            {{ log.entity_type }}
                            <span v-if="log.entity_id" class="text-xs text-muted-foreground">
                                · {{ log.entity_id.slice(0, 8) }}
                            </span>
                        </TableCell>
                        <TableCell class="text-sm text-muted-foreground">
                            {{ log.description ?? '—' }}
                        </TableCell>
                        <TableCell class="text-xs text-muted-foreground">{{ log.ip_address ?? '—' }}</TableCell>
                    </TableRow>
                    <TableRow v-if="logs.data.length === 0">
                        <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                            No audit logs yet.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <!-- Pagination -->
        <div v-if="logs.links.length > 3" class="flex justify-center gap-1">
            <button
                v-for="link in logs.links"
                :key="link.label"
                :disabled="!link.url"
                class="rounded border px-3 py-1 text-sm"
                :class="{ 'bg-primary text-primary-foreground': link.active, 'opacity-50': !link.url }"
                @click="link.url && router.visit(link.url, { preserveState: true })"
                v-html="link.label"
            />
        </div>
    </div>
</template>
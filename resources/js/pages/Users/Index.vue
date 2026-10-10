<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Table, TableBody, TableCell, TableHead, TableHeader, TableRow,
} from '@/components/ui/table';

defineProps<{
    users: any[];
    errors?: Record<string, string>;
}>();

const page = usePage();

function destroy(user: any) {
    if (!confirm(`Hapus user "${user.username}"?`)) return;
    router.delete(`/users/${user.id}`, { preserveScroll: true });
}

function currentUser() {
    return (page.props.auth as any)?.user;
}

function canEdit(user: any): boolean {
    const me = currentUser();
    if (!me) return false;

    // Admin biasa tidak bisa edit super_admin
    if (me.role === 'admin' && user.role === 'super_admin') return false;

    return true;
}

function canDelete(user: any): boolean {
    const me = currentUser();
    if (!me) return false;

    // Tidak bisa hapus diri sendiri
    if (user.id === me.id) return false;

    // Admin biasa tidak bisa hapus super_admin
    if (me.role === 'admin' && user.role === 'super_admin') return false;

    return true;
}

function roleVariant(role: string) {
    if (role === 'super_admin') return 'default';
    if (role === 'admin') return 'secondary';
    return 'outline';
}
</script>

<template>
    <Head title="Users" />
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Users</h1>
            <Link href="/users/create">
                <Button size="sm">+ New User</Button>
            </Link>
        </div>

        <div
            v-if="errors?.delete"
            class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"
        >
            {{ errors.delete }}
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Username</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Department</TableHead>
                        <TableHead>Role</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="user in users" :key="user.id">
                        <TableCell class="font-mono text-sm">
                            {{ user.username }}
                            <Badge
                                v-if="user.id === $page.props.auth?.user?.id"
                                variant="secondary"
                                class="ml-2 text-xs">
                                You
                            </Badge>
                        </TableCell>
                        <TableCell class="font-medium">{{ user.name }}</TableCell>
                        <TableCell class="text-sm">{{ user.email }}</TableCell>
                        <TableCell class="text-sm">{{ user.department ?? '—' }}</TableCell>
                        <TableCell>
                            <Badge :variant="roleVariant(user.role)">
                                {{ user.role === 'super_admin' ? 'Super Admin' : user.role }}
                            </Badge>
                        </TableCell>
                        <TableCell class="text-right">
                            <div class="flex justify-end gap-2">
                                <Link v-if="canEdit(user)" :href="`/users/${user.id}/edit`">
                                    <Button variant="outline" size="sm">Edit</Button>
                                </Link>
                                <Button
                                    v-if="canDelete(user)"
                                    variant="destructive"
                                    size="sm"
                                    @click="destroy(user)">
                                    Delete
                                </Button>
                            </div>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="users.length === 0">
                        <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                            No users yet.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
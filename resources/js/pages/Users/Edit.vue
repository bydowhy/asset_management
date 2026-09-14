<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select, SelectContent, SelectItem, SelectTrigger, SelectValue,
} from '@/components/ui/select';

const props = defineProps<{ user: any }>();

const form = useForm({
    username: props.user.username,
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    department: props.user.department ?? '',
    role: props.user.role,
});

function submit() {
    form.put(`/users/${props.user.id}`);
}

function destroy() {
    if (!confirm(`Hapus user "${props.user.username}"?`)) return;
    router.delete(`/users/${props.user.id}`);
}
</script>

<template>
    <Head :title="`Edit ${user.username}`" />
    <div class="mx-auto max-w-3xl space-y-4 p-4">
        <Card>
            <CardHeader><CardTitle>Edit User</CardTitle></CardHeader>
            <CardContent class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Username *</label>
                    <Input v-model="form.username" />
                    <p v-if="form.errors.username" class="text-xs text-red-500">{{ form.errors.username }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Full Name *</label>
                    <Input v-model="form.name" />
                    <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Email *</label>
                    <Input type="email" v-model="form.email" />
                    <p v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">New Password</label>
                    <Input type="password" v-model="form.password" placeholder="Kosongkan jika tidak diubah" />
                    <p class="mt-1 text-xs text-muted-foreground">Minimal 8 karakter.</p>
                    <p v-if="form.errors.password" class="text-xs text-red-500">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Password Confirmation</label>
                    <Input type="password" v-model="form.password_confirmation" />
                </div>

                <div>
                    <label class="text-sm font-medium">Department</label>
                    <Input v-model="form.department" />
                </div>

                <div>
                    <label class="text-sm font-medium">Role *</label>
                    <Select v-model="form.role">
                        <SelectTrigger><SelectValue /></SelectTrigger>
                        <SelectContent>
                            <SelectItem value="user">User</SelectItem>
                            <SelectItem value="admin">Admin</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </CardContent>
        </Card>

        <div class="flex justify-between">
            <Button variant="destructive" @click="destroy">Delete</Button>
            <div class="flex gap-2">
                <Link href="/users">
                    <Button variant="outline">Cancel</Button>
                </Link>
                <Button @click="submit" :disabled="form.processing">Save</Button>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';
import { computed } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const currentPath = computed(() => (page.url as string).split('?')[0]);

function isItemActive(href: NavItem['href']): boolean {
    if (!href) return false;

    // UrlMethodPair: objek { url: string, method: string }
    const raw = typeof href === 'string' ? href : href.url;

    const target = raw.split('?')[0];
    const current = currentPath.value;

    if (target === '/') {
        return current === '/';
    }

    return current === target || current.startsWith(target + '/');
}
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <!-- <SidebarGroupLabel>Platform</SidebarGroupLabel> -->
        <SidebarMenu class="mt-4">
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="isItemActive(item.href)">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>

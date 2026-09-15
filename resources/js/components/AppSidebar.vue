<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    LayoutGrid,
    Wrench,
    Package,
    AlertTriangle,
    FileText,
    Image,
    MapPin,
    Users,
    ClipboardList,
    Boxes,
    Link2,
    FileType,
} from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();

const isAdmin = computed(() => page.props.auth?.user?.role === 'admin');

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Equipment',
            href: '/equipment',
            icon: Wrench,
        },
        {
            title: 'Assets',
            href: '/assets',
            icon: Package,
        },
        {
            title: 'Failures',
            href: '/failures',
            icon: AlertTriangle,
        },
        {
            title: 'Documents',
            href: '/documents',
            icon: FileText,
        },
        {
            title: 'Photos',
            href: '/photos',
            icon: Image,
        },
        // Master Data Section
        {
            title: 'Locations',
            href: '/locations',
            icon: MapPin,
        },
        {
            title: 'Asset Types',
            href: '/asset-types',
            icon: Boxes,
        },
        {
            title: 'Relationship Types',
            href: '/relationship-types',
            icon: Link2,
        },
        {
            title: 'Document Types',
            href: '/document-types',
            icon: FileType,
        },
    ];

    // Admin-only menu
    if (isAdmin.value) {
        items.push(
            {
                title: 'Users',
                href: '/users',
                icon: Users,
            },
            {
                title: 'Audit Logs',
                href: '/audit-logs',
                icon: ClipboardList,
            }
        );
    }

    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
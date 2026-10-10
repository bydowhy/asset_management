<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';

// Ganti `href` dengan alamat tiap aplikasi (subdomain, path, atau URL penuh).
// `color` = warna logo, `icon` = path SVG (viewBox 24x24, digambar dengan garis putih).
const apps = [
    {
        name: 'HR',
        href: 'https://hr.example.com',
        color: '#D6457A',
        icon: [
            'M10 11a3.5 3.5 0 100-7 3.5 3.5 0 000 7z',
            'M16 20v-1.5a3.5 3.5 0 00-3.5-3.5h-5A3.5 3.5 0 004 18.5V20',
            'M15.5 4.2a3.5 3.5 0 010 6.6',
            'M20 20v-1.5a3.5 3.5 0 00-2.5-3.3',
        ],
    },
    {
        name: 'Purchase',
        href: 'https://purchase.example.com',
        color: '#D9730D',
        icon: ['M5 8h14l-1 12H6L5 8z', 'M9 8V7a3 3 0 016 0v1'],
    },
    {
        name: 'Point of Sale',
        href: 'https://pos.example.com',
        color: '#1F9D63',
        icon: [
            'M6 3h12a1 1 0 011 1v16a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z',
            'M8 7h8',
            'M8.5 12h.01',
            'M12 12h.01',
            'M15.5 12h.01',
            'M8.5 16h.01',
            'M12 16h.01',
            'M15.5 16h.01',
        ],
    },
    {
        name: 'Inventory',
        href: 'https://inventory.example.com',
        color: '#2A74DB',
        icon: ['M21 8l-9-5-9 5v8l9 5 9-5V8z', 'M3 8l9 5 9-5', 'M12 13v8'],
    },
    {
        name: 'Accounting',
        href: 'https://accounting.example.com',
        color: '#12847E',
        icon: ['M4 20h16', 'M7 20v-6', 'M12 20V8', 'M17 20v-9'],
    },
    {
        name: 'CRM',
        href: 'https://crm.example.com',
        color: '#7650D0',
        icon: ['M4 5h16v10H10l-4 4v-4H4V5z', 'M8 8.5h8', 'M8 11.5h5'],
    },
    {
        name: 'Asset Management',
        href: '/login',
        color: '#C8442F',
        icon: ['M3 12V4h8l10 10-8 8L3 12z', 'M7.5 8.5h.01'],
    },
    {
        name: 'Project',
        href: 'https://project.example.com',
        color: '#B58900',
        icon: [
            'M9 6h11',
            'M9 12h11',
            'M9 18h11',
            'M3.5 6l1.2 1.2L6.8 5',
            'M3.5 12l1.2 1.2L6.8 11',
            'M3.5 18l1.2 1.2L6.8 17',
        ],
    },
    {
        name: 'Helpdesk',
        href: 'https://helpdesk.example.com',
        color: '#0E8CB3',
        icon: [
            'M12 21a9 9 0 100-18 9 9 0 000 18z',
            'M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z',
            'M5.6 5.6l3.9 3.9',
            'M14.5 14.5l3.9 3.9',
            'M18.4 5.6l-3.9 3.9',
            'M9.5 14.5l-3.9 3.9',
        ],
    },
];
</script>

<template>
    <Head title="Application Portal" />

    <div class="flex min-h-screen flex-col bg-[#e9eef5] bg-[radial-gradient(circle,rgba(22,33,43,0.1)_1px,transparent_1px)] bg-size-[22px_22px] px-5 py-6 font-['Plus_Jakarta_Sans',system-ui,sans-serif] text-[#16212b] sm:px-8 dark:bg-[#0f141d] dark:bg-[radial-gradient(circle,rgba(255,255,255,0.07)_1px,transparent_1px)] dark:text-[#edf1f5]">
        <main
            class="mx-auto flex w-full max-w-3xl flex-1 flex-col justify-center py-10">
            <div class="mb-10 text-center">
                <h1
                    class="text-[clamp(2rem,6vw,3.25rem)] leading-[1.05] font-bold tracking-[-0.02em]">
                    Application Portal
                </h1>
                <p class="mt-3 text-base text-[#55646f] dark:text-[#9aa8b5]">
                    Centralized access to all your applications in one place. Click on the app you want to use.
                </p>
            </div>

            <nav class="grid grid-cols-3 gap-3 sm:gap-5" aria-label="Application List">
                <a v-for="app in apps"
                    :key="app.name"
                    :href="app.href"
                    :style="{ '--c': app.color }"
                    class="flex flex-col items-center gap-3 rounded-2xl border border-[rgba(22,33,43,0.1)] bg-white px-2 py-5 text-center transition duration-150 hover:-translate-y-1 hover:border-(--c) hover:shadow-[0_16px_28px_-16px_color-mix(in_srgb,var(--c)_75%,transparent)] focus-visible:outline-3 focus-visible:outline-offset-4 focus-visible:outline-(--c) motion-reduce:transition-none motion-reduce:hover:translate-y-0 sm:gap-4 sm:px-4 sm:py-7 dark:border-white/10 dark:bg-[#171e2a] dark:hover:border-(--c)">
                    <span class="flex size-14 items-center justify-center rounded-2xl sm:size-16"
                        :style="{ backgroundColor: app.color }"
                        aria-hidden="true">
                        <svg class="size-7 sm:size-8"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#fff"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round">
                            <path v-for="d in app.icon" :key="d" :d="d" />
                        </svg>
                    </span>
                    <span
                        class="text-sm leading-tight font-semibold tracking-[-0.01em] sm:text-base">
                        {{ app.name }}
                    </span>
                </a>
            </nav>
        </main>
    </div>
</template>

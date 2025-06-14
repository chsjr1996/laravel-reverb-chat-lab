<script setup lang="ts">
import AppChat from '@/components/AppChat.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ChatRoom } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
    room: ChatRoom;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chat',
        href: '/chat',
    },
];
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <app-chat :room="room" />
            </div>
        </div>
    </AppLayout>
</template>

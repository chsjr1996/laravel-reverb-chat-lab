<script setup lang="ts">
import ChatMessage from '@/components/chat/ChatMessage.vue';
import ChatPlaceholder from '@/components/chat/ChatPlaceholder.vue';
import ChatRoomList from '@/components/chat/ChatRoomList.vue';
import ChatSearch from '@/components/chat/ChatSearch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ChatRoom } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    rooms: ChatRoom[];
    room?: ChatRoom;
}>();

const breadcrumbs = ref<BreadcrumbItem[]>([
    {
        title: 'Messages',
        href: '/chat/room',
    },
]);
const searchInput = ref<HTMLInputElement | null>(null);

watch(
    () => props.room,
    (newRoomValue) => {
        if (!newRoomValue) return;

        breadcrumbs.value.push({
            title: 'Chat',
            href: `/chat/room/${newRoomValue.id}`,
        });
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <Head title="Chat" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-row rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] w-[400px] rounded-l-xl border md:min-h-min">
                <chat-search @registered-input="el => searchInput = el" />
                <chat-room-list :rooms="rooms" />
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-r-xl border md:min-h-min">
                <chat-message v-if="room" :room="room" />
                <chat-placeholder v-else :search-input-ref="searchInput" />
            </div>
        </div>
    </AppLayout>
</template>

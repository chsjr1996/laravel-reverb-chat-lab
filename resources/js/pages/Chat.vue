<script setup lang="ts">
/**
 * @todo Need to render chat-user-list only when search is performed.
 */

import ChatMessage from '@/components/chat/ChatMessage.vue';
import ChatPlaceholder from '@/components/chat/ChatPlaceholder.vue';
import ChatRoomList from '@/components/chat/ChatRoomList.vue';
import ChatSearch from '@/components/chat/ChatSearch.vue';
import ChatUserList from '@/components/chat/ChatUserList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ChatRoom, type User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    rooms: ChatRoom[];
    room?: ChatRoom;
    user?: { data: Pick<User, 'id' | 'name'> };
}>();

const breadcrumbs = ref<BreadcrumbItem[]>([
    {
        title: 'Messages',
        href: '/chat/room'
    }
]);
const searchInput = ref<HTMLInputElement | null>(null);
const searchText = ref('');

watch(
    () => props.room,
    (newRoomValue) => {
        if (!newRoomValue) return;

        breadcrumbs.value.push({
            title: 'Chat',
            href: `/chat/room/${newRoomValue.id}`
        });
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <Head title="Chat" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-row rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] w-[400px] rounded-l-xl border md:min-h-min">
                <chat-search :search-text="searchText" @registered-input="(el) => (searchInput = el)" />
                <chat-room-list :rooms="rooms" />
                <chat-user-list />
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-r-xl border md:min-h-min">
                <chat-message v-if="room || user" :room="room" :user="user" />
                <chat-placeholder v-else :search-input-ref="searchInput" />
            </div>
        </div>
    </AppLayout>
</template>

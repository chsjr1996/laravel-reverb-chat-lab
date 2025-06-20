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
import { type BreadcrumbItem, type ChatRoom, type SharedData, type User } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import { onMounted, ref, watch } from 'vue';

const props = defineProps<{
    rooms: ChatRoom[];
    room?: ChatRoom;
    user?: { data: Pick<User, 'id' | 'name'> };
    selectedRoomId?: number;
}>();

const page = usePage<SharedData>();
const { id: currentUserId } = page.props.auth.user as User;
const roomObserverEcho = useEcho(`chat.room.observer.${currentUserId}`);

const breadcrumbs = ref<BreadcrumbItem[]>([
    {
        title: 'Messages',
        href: '/chat/room',
    },
]);
const searchInput = ref<HTMLInputElement | null>(null);
const searchText = ref('');
const currentRooms = ref(props.rooms);

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

onMounted(() => {
    roomObserverEcho.channel().listen('ChatRoomCreated', (response: { chatRoom: ChatRoom }) => {
        currentRooms.value.unshift(response.chatRoom);
    });
});
</script>

<template>
    <Head title="Chat" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-row rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] w-[400px] rounded-l-xl border md:min-h-min">
                <chat-search ref="searchInput" @update:model-value="(value) => (searchText = value)" @registered-input="(el) => (searchInput = el)" />
                <div class="h-[calc(100vh-148px)] overflow-y-auto [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700">
                    <chat-room-list :rooms="currentRooms" :search-text="searchText" :selected-room-id="selectedRoomId" />
                    <chat-user-list :search-text="searchText" />
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-r-xl border md:min-h-min">
                <chat-message v-if="room || user" :room="room" :user="user" />
                <chat-placeholder v-else :search-input-ref="searchInput" />
            </div>
        </div>
    </AppLayout>
</template>

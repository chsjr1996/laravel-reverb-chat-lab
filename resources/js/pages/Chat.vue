<script setup lang="ts">
import ChatMessage from '@/components/chat/ChatMessage.vue';
import ChatRoomList from '@/components/chat/ChatRoomList.vue';
import ChatSearch from '@/components/chat/ChatSearch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { getFriendData } from '@/lib/utils';
import { type BreadcrumbItem, type ChatRoom, type SharedData, type User } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useEchoPresence } from '@laravel/echo-vue';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    rooms: ChatRoom[];
    room?: ChatRoom;
}>();

const page = usePage<SharedData>();
const { id: currentUserId } = page.props.auth.user as User;
const chatPresenceEcho = useEchoPresence('chat');

const isUserOnline = ref(false);
const isRoom = !!props.room;
const roomUsers = isRoom ? props.room.users : [];

const getChatName = () => {
    if (!isRoom) {
        return 'Chat list';
    }

    if (!props.room.is_group) {
        const userName = getFriendData(roomUsers, currentUserId)?.name || 'Unknown User';

        return `Chat - with ${userName}`;
    }

    return props.room.name || 'Group Chat';
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: getChatName(),
        href: '/chat',
    },
];

onMounted(() => {
    if (isRoom && !props.room.is_group) {
        chatPresenceEcho.channel().here((users: User[]) => {
            isUserOnline.value = users.some((user) => user.id === getFriendData(roomUsers, currentUserId).id);
        });

        chatPresenceEcho.channel().joining((user: User) => {
            if (user.id === getFriendData(roomUsers, currentUserId).id) isUserOnline.value = true;
        });

        chatPresenceEcho.channel().leaving((user: User) => {
            if (user.id === getFriendData(roomUsers, currentUserId).id) isUserOnline.value = false;
        });
    }
});
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template v-if="room && !room.is_group" v-slot:badge>
            <div class="flex">
                <span :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'" class="inline-block h-3 w-3 rounded-full"></span>
            </div>
        </template>

        <div class="flex h-full flex-1 flex-row rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] w-[400px] rounded-l-xl border md:min-h-min">
                <chat-search />
                <chat-room-list :rooms="rooms" />
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-r-xl border md:min-h-min">
                <chat-message v-if="room" :room="room" />
            </div>
        </div>
    </AppLayout>
</template>

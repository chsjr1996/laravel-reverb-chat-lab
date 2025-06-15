<script setup lang="ts">
import AppChat from '@/components/AppChat.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ChatRoom, type SharedData, type User } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { useEchoPresence } from '@laravel/echo-vue';
import { onMounted, ref } from 'vue';

const page = usePage<SharedData>();
const { id: currentUserId } = page.props.auth.user as User;
const chatPresenceEcho = useEchoPresence('chat');

const props = defineProps<{
    room: ChatRoom;
}>();

const isUserOnline = ref(false);

const getFriendData = (): User => {
    return props.room.users.filter((u) => u.id !== currentUserId)[0];
};

const getChatName = () => {
    if (!props.room.is_group) {
        const userName = getFriendData()?.name || 'Unknown User';

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
    if (!props.room.is_group) {
        chatPresenceEcho.channel().here((users: User[]) => {
            isUserOnline.value = users.some((user) => user.id === getFriendData().id);
        });

        chatPresenceEcho.channel().joining((user: User) => {
            if (user.id === getFriendData().id) isUserOnline.value = true;
        });

        chatPresenceEcho.channel().leaving((user: User) => {
            if (user.id === getFriendData().id) isUserOnline.value = false;
        });
    }
});
</script>

<template>
    <Head title="Chat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template v-slot:badge>
            <div class="flex">
                <span :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'" class="inline-block h-3 w-3 rounded-full"></span>
            </div>
        </template>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <app-chat :room="room" />
            </div>
        </div>
    </AppLayout>
</template>

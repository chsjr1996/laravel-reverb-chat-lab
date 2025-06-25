<script setup lang="ts">
/**
 * @todo Need to render chat-user-list only when search is performed.
 */

import ChatActionsMenu from '@/components/chat/ChatActionsMenu.vue';
import ChatCreateGroupAction from '@/components/chat/ChatCreateGroupAction.vue';
import ChatMessage from '@/components/chat/ChatMessage.vue';
import ChatPlaceholder from '@/components/chat/ChatPlaceholder.vue';
import ChatRoomList from '@/components/chat/ChatRoomList.vue';
import ChatSearch from '@/components/chat/ChatSearch.vue';
import ChatUserList from '@/components/chat/ChatUserList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ChatActionModesType, type ChatRoom, type SharedData, type User } from '@/types';
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
const searchText = ref<string | number>('');
const currentRooms = ref(props.rooms);
const chatActionMode = ref<ChatActionModesType>('default');
const selectedUsers = ref<Record<number, boolean>>({});

const handleChatActionMode = (mode: ChatActionModesType) => {
    chatActionMode.value = mode;

    if (mode === 'create_group') {
        searchInput.value?.focus();
    }
};

// TODO: need to clear search input
const handleGroupCreated = () => {
    chatActionMode.value = 'default';
    searchText.value = '';
    selectedUsers.value = {};
};

const handleCheckedUsersUpdate = (users: Record<number, boolean>) => {
    selectedUsers.value = users;
};

watch(
    () => props.room?.id,
    (newRoomId, prevRoomId) => {
        if (!newRoomId || newRoomId === prevRoomId) return;

        breadcrumbs.value.push({
            title: 'Chat',
            href: `/chat/room/${newRoomId}`,
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
        <div class="flex h-full flex-1 flex-row rounded-xl p-0 md:p-4">
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] w-full border sm:rounded-l-xl md:min-h-min md:w-[400px]"
                :class="{ 'hidden xl:block': room || user }"
            >
                <chat-search
                    ref="searchInput"
                    :chat-action-mode="chatActionMode"
                    @change-action-mode="handleChatActionMode"
                    @update:model-value="(value) => (searchText = value)"
                    @registered-input="(el) => (searchInput = el)"
                />
                <div
                    class="h-[calc(100vh-198px)] overflow-y-auto custom-scrollbar"
                >
                    <chat-room-list
                        v-if="chatActionMode === 'default'"
                        :rooms="currentRooms"
                        :search-text="searchText"
                        :selected-room-id="selectedRoomId"
                    />
                    <chat-user-list :chat-action-mode="chatActionMode" :search-text="searchText" @update:checked-users="handleCheckedUsersUpdate" />
                </div>
                <chat-actions-menu v-if="chatActionMode === 'default'" :search-input-ref="searchInput" @change-action-mode="handleChatActionMode" />
                <chat-create-group-action
                    v-if="chatActionMode === 'create_group'"
                    :selected-users="selectedUsers"
                    @change-action-mode="handleChatActionMode"
                    @groupCreated="handleGroupCreated"
                />
            </div>
            <div
                class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 md:rounded-r-xl border md:min-h-min"
                :class="{ 'hidden xl:block': !room && !user }"
            >
                <chat-message v-if="room || user" :room="room" :user="user" />
                <chat-placeholder v-else :search-input-ref="searchInput" />
            </div>
        </div>
    </AppLayout>
</template>

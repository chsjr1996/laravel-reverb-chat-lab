<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { formatTime, getFriendData, getRoomName } from '@/lib/utils';
import type { ChatRoom, SharedData, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { rooms, searchText } = defineProps<{
    rooms?: ChatRoom[];
    searchText?: string;
}>();

const page = usePage<SharedData>();
const { id: currentUserId } = page.props.auth.user as User;
const { getInitials } = useInitials();
const currentRooms = ref(rooms);

const getRoomAvatar = (room: ChatRoom) => {
    if (room.is_group && room.avatar && room.avatar !== '') {
        return room.avatar;
    }

    const friendUser = getFriendData(room.users, currentUserId);

    if (friendUser && friendUser.avatar && friendUser.avatar !== '') {
        return friendUser.avatar;
    }

    return '';
};

watch(
    () => searchText,
    (newSearchText) => {
        if (!newSearchText || newSearchText.trim() === '') {
            currentRooms.value = rooms;
            return;
        }

        currentRooms.value = rooms?.filter((room) => {
            const roomName = getRoomName(room, currentUserId).toLowerCase();
            return roomName.includes(newSearchText.toLowerCase());
        });
    },
);
</script>

<template>
    <div>
        <div v-if="searchText?.trim() !== ''" class="mt-6 mb-2">
            <strong class="ml-4 text-xl">Chats</strong>
        </div>
        <template v-if="currentRooms?.length">
            <div v-for="room in currentRooms" :key="room.id" class="h-[80px] border-b hover:bg-neutral-100 dark:hover:bg-neutral-900">
                <Link class="flex h-full w-full cursor-pointer items-center p-4" :href="'/chat/room/' + room.id">
                    <Avatar class="h-10 w-10 overflow-hidden rounded-full">
                        <AvatarImage v-if="getRoomAvatar(room)" :src="getRoomAvatar(room)" :alt="getRoomName(room, currentUserId)" />
                        <AvatarFallback class="rounded-lg text-black dark:text-white">
                            {{ getInitials(getRoomName(room, currentUserId)) }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="ml-4 w-full">
                        <div class="flex w-full items-center">
                            <span class="flex flex-1">{{ getRoomName(room, currentUserId) }}</span>
                            <span v-if="room.messages && room.messages[0]" class="text-muted-foreground text-xs">
                                {{ formatTime(room.messages[0].created_at, false) }}
                            </span>
                        </div>
                        <span v-if="room.messages && room.messages[0]" class="text-muted-foreground text-sm">
                            {{ room.messages[0].text }}
                        </span>
                    </div>
                </Link>
            </div>
        </template>
        <div v-else-if="searchText?.trim() !== ''" class="mt-2 ml-4">
            <p>No results</p>
        </div>
    </div>
</template>

<style scoped></style>

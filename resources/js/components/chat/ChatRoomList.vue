<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { formatTime, getFriendData } from '@/lib/utils';
import type { ChatRoom, SharedData, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    rooms?: ChatRoom[];
}>();

const page = usePage<SharedData>();
const { id: currentUserId } = page.props.auth.user as User;
const { getInitials } = useInitials();

const getRoomAvatar = (room: ChatRoom) => {
    if (room.is_group && room.avatar && room.avatar !== '') {
        return room.avatar;
    }

    const friendUser = getFriendData(room.users, currentUserId);

    if (friendUser.avatar && friendUser.avatar !== '') {
        return friendUser.avatar;
    }

    return '';
};

const getRoomName = (room: ChatRoom): string => {
    if (room.is_group) {
        return room.name;
    }

    return getFriendData(room.users, currentUserId).name;
};
</script>

<template>
    <div>
        <div v-for="room in rooms" :key="room.id" class="h-[80px] border-b">
            <Link class="flex items-center h-full w-full cursor-pointer p-4" :href="'/chat/room/' + room.id">
                <Avatar class="h-10 w-10 overflow-hidden rounded-full">
                    <AvatarImage v-if="getRoomAvatar(room)" :src="getRoomAvatar(room)" :alt="getRoomName(room)" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(getRoomName(room)) }}
                    </AvatarFallback>
                </Avatar>
                <div class="ml-4 w-full">
                    <div class="flex items-center w-full">
                        <span class="flex flex-1">{{ getRoomName(room) }}</span>
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
    </div>
</template>

<style scoped></style>

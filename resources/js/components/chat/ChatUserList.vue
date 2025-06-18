<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { getUsers } from '@/services/userService';
import { type User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const users = ref<User[]>([]);

const fetchUsers = async () => {
    const [apiUsers, error] = await getUsers([
        'exclude_current_user=1',
        'exclude_friends=1'
    ]);

    if (error) {
        // TODO: show error notification
        return;
    }

    users.value = apiUsers.data;
};

onMounted(() => {
    fetchUsers();
});
</script>

<template>
    <div>
        <div class="mt-4 mb-2">
            <strong class="ml-4">Users</strong>
        </div>
        <hr />
        <div v-for="user in users" :key="user.id" class="h-[80px] border-b hover:bg-neutral-900">
            <Link class="flex h-full w-full cursor-pointer items-center p-4" :href="'/chat/room/user/' + user.id">
                <Avatar class="h-10 w-10 overflow-hidden rounded-full">
                    <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="user.name" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(user.name) }}
                    </AvatarFallback>
                </Avatar>
                <div class="ml-4 w-full">
                    <div class="flex w-full items-center">
                        <span class="flex flex-1">{{ user.name }}</span>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>

<style scoped></style>

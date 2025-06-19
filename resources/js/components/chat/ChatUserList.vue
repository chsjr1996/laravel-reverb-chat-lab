<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { getUsers } from '@/services/userService';
import { type User } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { searchText } = defineProps<{
    searchText?: string;
}>();

const users = ref<User[]>([]);
const searchTerm = ref<string>(searchText || '');
const searchLoading = ref<boolean>(false);
const searchTimeout = ref<number | null>(null);

const fetchUsers = async () => {
    const [apiUsers, error] = await getUsers(['exclude_current_user=1', 'exclude_friends=1', 'name=' + encodeURIComponent(searchTerm.value)]);

    if (error) {
        // TODO: show error notification
        return;
    }

    searchLoading.value = false;
    users.value = apiUsers.data;
};

watch(
    () => searchText,
    (newSearchText) => {
        // TODO: move partial logic here to utils.ts
        if (!newSearchText || newSearchText.trim() === '') {
            users.value = [];
            searchTerm.value = '';
            return;
        }

        if (searchTimeout?.value) {
            clearTimeout(searchTimeout.value);
        }

        searchLoading.value = true;
        searchTimeout.value = setTimeout(() => {
            searchTerm.value = newSearchText;
            fetchUsers();
        }, 500);
    },
);
</script>

<template>
    <div v-if="searchTerm">
        <div class="mt-4 mb-2">
            <strong class="ml-4">Users</strong>
        </div>
        <hr />
        <template v-if="users.length">
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
        </template>
        <div v-else class="mt-2 ml-4">
            <p>No results</p>
        </div>
        <div v-if="searchLoading" class="mt-2 ml-4">
            <p>Loading...</p>
        </div>
    </div>
</template>

<style scoped></style>

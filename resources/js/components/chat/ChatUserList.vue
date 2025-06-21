<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Checkbox } from '@/components/ui/checkbox';
import { getInitials } from '@/composables/useInitials';
import { getUsers } from '@/services/userService';
import { type ChatActionModesType, type User } from '@/types';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { chatActionMode, searchText } = defineProps<{
    chatActionMode: ChatActionModesType;
    searchText?: string;
}>();

const emit = defineEmits<{
    (e: 'update:checkedUsers', value: Record<number, boolean>): void;
}>();

const users = ref<User[]>([]);
const searchTerm = ref<string>(searchText || '');
const searchLoading = ref<boolean>(false);
const searchTimeout = ref<number | null>(null);
const checkedUsers = ref<Record<number, boolean>>({});

const fetchUsers = async () => {
    const [apiUsers, error] = await getUsers(['exclude_current_user=1', 'exclude_friends=1', 'name=' + encodeURIComponent(searchTerm.value)]);

    if (error) {
        // TODO: show error notification
        return;
    }

    searchLoading.value = false;
    users.value = apiUsers.data;
};

const handleUserClick = (userId: number) => {
    if (chatActionMode === 'default') {
        router.visit('/chat/room/user/' + userId.toString());
    }

    if (chatActionMode === 'create_group') {
        checkedUsers.value[userId] = !checkedUsers.value[userId];
    }
};

watch(
    () => searchText,
    (newSearchText) => {
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

watch(checkedUsers, (newCheckedUsers) => {
    emit('update:checkedUsers', newCheckedUsers);
}, { deep: true });
</script>

<template>
    <div v-if="searchTerm">
        <div v-if="chatActionMode === 'default'">
            <div class="mt-6 mb-2">
                <strong class="ml-4 text-xl">Users</strong>
            </div>
            <hr />
        </div>
        <template v-if="users.length">
            <div v-for="user in users" :key="user.id" class="h-[80px] border-b hover:bg-neutral-100 dark:hover:bg-neutral-900">
                <div @click="() => handleUserClick(user.id)" class="flex h-full w-full cursor-pointer items-center p-4">
                    <Checkbox v-if="chatActionMode === 'create_group'" class="mr-4" v-model="checkedUsers[user.id]" />
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
                </div>
            </div>
        </template>
        <div v-else-if="!searchLoading" class="mt-2 ml-4">
            <p>No results</p>
        </div>
        <div v-if="searchLoading" class="mt-2 ml-4">
            <p>Loading...</p>
        </div>
    </div>
</template>

<style scoped></style>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { type ChatActionModesType } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';

const form = useForm<{
    name: string;
    users: string[];
}>({
    name: '',
    users: [],
});

const { selectedUsers } = defineProps<{
    selectedUsers: Record<number, boolean>;
}>();

const emit = defineEmits<{
    (e: 'changeActionMode', value: ChatActionModesType): void;
    (e: 'groupCreated'): void;
}>();

const submit = async () => {
    const selectedUserIds = Object.keys(selectedUsers).filter((userId) => selectedUsers[Number(userId)]);
    if (selectedUserIds.length <= 0) return;

    form.users = selectedUserIds;
    form.post(route('chat.room.store'), {
        onSuccess: () => {
            emit('groupCreated');
        },
        onFinish: () => {
            form.reset('name');
            form.reset('users');
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="relative flex h-[50px] w-full items-center justify-between border-t-1">
        <Input
            class="h-[50px] w-full flex-1 rounded-none rounded-bl-xl border-0 border-t px-4 pr-13 focus-visible:ring-0 focus-visible:outline-0"
            placeholder="Group name"
            v-model="form.name"
            required
        />
        <Button type="submit" variant="ghost" class="absolute right-0 mx-2 h-[40px] w-[40px] cursor-pointer">
            <Check />
        </Button>
    </form>
</template>

<style scoped></style>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { type ChatActionModesType } from '@/types';
import { useForm } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';

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
    <form @submit.prevent="submit" class="flex h-[50px] w-full items-center justify-between border-t-1">
        <Button @click="$emit('changeActionMode', 'default')" variant="ghost" class="group-action-button mx-2">
            <X />
        </Button>
        <Input placeholder="Group name" v-model="form.name" required />
        <Button type="submit" variant="ghost" class="group-action-button mx-2">
            <Check />
        </Button>
    </form>
</template>

<style scoped>
.group-action-button {
    width: 40px;
    height: 40px;
    cursor: pointer;
}
</style>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { type ChatActionModesType } from '@/types';
import { Check, X } from 'lucide-vue-next';
import { ref } from 'vue';

const groupName = ref<string>('');

const { selectedUsers } = defineProps<{
    selectedUsers: Record<number, boolean>;
}>();

defineEmits<{
    (e: 'changeActionMode', value: ChatActionModesType): void;
}>();

const handleCreateGroup = () => {
    const selectedUserIds = Object.keys(selectedUsers).filter((userId) => selectedUsers[Number(userId)]);
    if (selectedUserIds.length <= 0) return;

    console.log('Creating group with users:', {
        name: groupName.value,
        users: selectedUsers
    });
};
</script>

<template>
    <div class="flex h-[50px] w-full items-center justify-between border-t-1">
        <Button @click="$emit('changeActionMode', 'default')" variant="ghost" class="group-action-button mx-2">
            <X />
        </Button>
        <Input placeholder="Group name" v-model="groupName" />
        <Button @click="handleCreateGroup" variant="ghost" class="group-action-button mx-2">
            <Check />
        </Button>
    </div>
</template>

<style scoped>
.group-action-button {
    width: 40px;
    height: 40px;
    cursor: pointer;
}
</style>

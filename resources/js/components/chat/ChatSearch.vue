<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { onMounted, ref } from 'vue';
import { type ChatActionModesType } from '@/types';

const { chatActionMode } = defineProps<{
    chatActionMode: ChatActionModesType;
}>();

const emit = defineEmits(['registeredInput', 'update:modelValue']);
const searchInputElement = ref<HTMLInputElement | null>(null);

const focus = () => {
    if (searchInputElement.value) {
        searchInputElement.value?.focus();
    }
};

const getInputPlaceholder = () => {
    return chatActionMode === 'create_group' ? 'Search and select users to group' : 'Search chats and users...';
};

defineExpose({ focus });

onMounted(() => {
    emit('registeredInput', searchInputElement.value);
});
</script>

<template>
    <div class="w-full">
        <Input
            ref="searchInputElement"
            @update:model-value="(value) => emit('update:modelValue', value)"
            type="text"
            :placeholder="getInputPlaceholder()"
            class="h-[50px] w-full flex-1 rounded-none rounded-tl-xl border-0 border-b px-4 focus-visible:ring-0 focus-visible:outline-0"
        />
    </div>
</template>

<style scoped></style>

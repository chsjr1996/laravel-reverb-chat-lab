<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { type ChatActionModesType } from '@/types';
import { Search, X } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

const { chatActionMode } = defineProps<{
    chatActionMode: ChatActionModesType;
}>();

const emit = defineEmits<{
    (e: 'registeredInput', inputElement: HTMLInputElement | null): void;
    (e: 'update:modelValue', value: string): void;
    (e: 'changeActionMode', value: ChatActionModesType): void;
}>();
const searchText = ref<string | number>('');
const searchInputElement = ref<HTMLInputElement | null>(null);

const focus = () => {
    if (searchInputElement.value) {
        searchInputElement.value?.focus();
    }
};

const clear = () => {
    searchText.value = '';
};

const getInputPlaceholder = () => {
    return chatActionMode === 'create_group' ? 'Search and select users to group' : 'Search chats and users...';
};

const handleClose = () => {
    emit('changeActionMode', 'default');
    emit('update:modelValue', '');
    searchText.value = '';
};

defineExpose({ focus, clear });

onMounted(() => {
    emit('registeredInput', searchInputElement.value);
});
</script>

<template>
    <div class="relative w-full">
        <Button
            v-if="chatActionMode === 'create_group' || searchText"
            @click="handleClose"
            variant="ghost"
            class="absolute top-1 left-0 mx-2 h-[40px] w-[40px] cursor-pointer"
        >
            <X />
        </Button>
        <Button v-else @click="focus" variant="ghost" class="absolute top-1 left-0 mx-2 h-[40px] w-[40px] cursor-pointer">
            <Search />
        </Button>
        <Input
            v-model="searchText"
            ref="searchInputElement"
            @update:model-value="(value) => emit('update:modelValue', value as string)"
            type="text"
            :placeholder="getInputPlaceholder()"
            class="h-[50px] w-full flex-1 rounded-none lg:rounded-tl-xl border-0 border-b px-4 pl-13 focus-visible:ring-0 focus-visible:outline-0"
        />
    </div>
</template>

<style scoped></style>

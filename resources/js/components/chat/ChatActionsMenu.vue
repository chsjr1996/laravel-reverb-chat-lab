<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ChatActionModesType } from '@/types';
import { Plus, Search } from 'lucide-vue-next';

const { searchInputRef } = defineProps<{
    searchInputRef: HTMLInputElement | null;
}>();

defineEmits<{
    (e: 'changeActionMode', mode: ChatActionModesType): void;
}>();
</script>

<template>
    <div class="flex h-[50px] w-full items-center justify-between border-t-1">
        <div class="flex w-[50px] justify-center">
            <Button @click="() => searchInputRef?.focus()" variant="ghost" class="cursor-pointer">
                <Search />
            </Button>
        </div>
        <div class="flex w-[50px] justify-center">
            <Button @click="$emit('changeActionMode', 'create_group')" variant="ghost" class="cursor-pointer">
                <Plus />
            </Button>
        </div>
        <div class="flex w-[50px] justify-center"></div>
    </div>
</template>

<style scoped></style>

<script setup lang="ts">
import { MessageCirclePlus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const { searchInputRef } = defineProps<{
    searchInputRef: HTMLInputElement | null;
}>();

const focusOnSearch = () => {
    if (searchInputRef) {
        searchInputRef.focus();
    }
};
</script>

<template>
    <div class="flex flex-col w-full h-full items-center justify-center">
        <MessageCirclePlus :size="100" />
        <strong>Your messages</strong>
        <p class="text-muted-foreground text-sm">Click in message or search for any user to start a chat.</p>
        <Button class="mt-2 cursor-pointer" @click="focusOnSearch">Search</Button>
    </div>
</template>

<style scoped></style>

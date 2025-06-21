<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { MessageCirclePlus } from 'lucide-vue-next';

const { searchInputRef } = defineProps<{
    searchInputRef: HTMLInputElement | null;
}>();
</script>

<template>
    <div class="flex h-full w-full flex-col items-center justify-center">
        <MessageCirclePlus :size="100" />
        <strong>Your messages</strong>
        <p class="text-muted-foreground text-sm">Click in message or search for any user to start a chat.</p>
        <Button class="mt-2 cursor-pointer" @click="() => searchInputRef?.focus()">Search</Button>
    </div>
</template>

<style scoped></style>

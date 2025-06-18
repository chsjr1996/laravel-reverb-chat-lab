<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { onMounted, ref } from 'vue';

const emit = defineEmits(['registeredInput', 'update:modelValue']);
const searchInputElement = ref<HTMLInputElement | null>(null);

onMounted(() => {
    emit('registeredInput', searchInputElement.value);
});

const focus = () => {
    if (searchInputElement.value) {
        searchInputElement.value?.focus();
    }
};
defineExpose({ focus });
</script>

<template>
    <div class="w-full">
        <Input
            ref="searchInputElement"
            @update:model-value="(value) => emit('update:modelValue', value)"
            type="text"
            placeholder="Search messages and users..."
            class="h-[50px] w-full flex-1 rounded-none rounded-tl-xl border-0 border-b px-4 focus-visible:ring-0 focus-visible:outline-0"
        />
    </div>
</template>

<style scoped></style>

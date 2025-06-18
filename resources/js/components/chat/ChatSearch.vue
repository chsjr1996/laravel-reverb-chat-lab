<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { type HTMLAttributes, onMounted, ref } from 'vue';
import { useVModel } from '@vueuse/core';

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
}>()

const searchInputElement = ref<HTMLInputElement | null>(null);

const emits = defineEmits<{
    searchText: (e: 'update:modelValue', payload: string | number) => void,
    registeredInput: (el: HTMLInputElement | null) => void,
}>()

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
})

onMounted(() => {
    emits.registeredInput(searchInputElement.value);
});
</script>

<template>
    <div class="w-full">
        <Input
            ref="searchInputElement"
            v-model="modelValue"
            type="text"
            placeholder="Search messages and users..."
            class="h-[50px] w-full flex-1 rounded-none rounded-tl-xl border-b border-none px-4 focus-visible:border-none focus-visible:ring-0 focus-visible:outline-0"
        />
    </div>
</template>

<style scoped></style>

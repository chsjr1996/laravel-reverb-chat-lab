<script setup lang="ts">
import { onMounted, ref } from 'vue';

const emit = defineEmits(['registeredInput']);
const inputElement = ref<HTMLInputElement | null>(null);

onMounted(() => {
    emit('registeredInput', inputElement.value);
});
</script>

<template>
    <div class="w-full">
        <input ref="inputElement" type="text" placeholder="Search messages and users..." class="h-[50px] w-full flex-1 rounded-tl-xl border-b px-2" />
    </div>
</template>

<style scoped></style>

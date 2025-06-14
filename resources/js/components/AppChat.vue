<script setup lang="ts">
/**
 * @todo missing types
 * @todo allow to detect who is someoneIsTyping
 */

import { type ChatRoom, type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import axios from 'axios';
import { nextTick, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    room: ChatRoom;
}>();

const page = usePage<SharedData>();
const currentUser = page.props.auth.user as User;

const roomEcho = useEcho(`chat.room.${props.room.id}`);

const messages = ref([]);
const newMessage = ref('');
const messagesContainer = ref(null);
const someIsTyping = ref(false);
const someIsTypingTimer = ref(null);

watch(
    messages,
    () => {
        nextTick(() => {
            messagesContainer.value.scrollTo({
                top: messagesContainer.value.scrollHeight,
                behavior: 'smooth',
            });
        });
    },
    { deep: true },
);

const sendMessage = () => {
    if (newMessage.value.trim() !== '') {
        axios
            .post(`/chat/room/${props.room.id}/message`, {
                text: newMessage.value,
            })
            .then((response) => {
                newMessage.value = '';
            });
    }
};

const sendTypingEvent = () => {
    roomEcho.channel().whisper('typing', {
        userID: currentUser.id,
    });
};

onMounted(() => {
    axios.get(`/chat/room/${props.room.id}/message`).then((response) => {
        messages.value = response.data;
    });

    roomEcho
        .channel()
        .listen('MessageSent', (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper('typing', (response) => {
            someIsTyping.value = true;

            if (someIsTypingTimer.value) {
                clearTimeout(someIsTypingTimer.value);
            }

            someIsTypingTimer.value = setTimeout(() => {
                someIsTyping.value = false;
            }, 1000);
        });
});
</script>

<template>
    <div class="h-full">
        <div class="flex h-[calc(100%-60px)] flex-col justify-end">
            <div ref="messagesContainer" class="max-h-fit overflow-y-auto p-4">
                <div v-for="message in messages" :key="message.id" class="mb-2 flex items-center">
                    <div v-if="message.user_id === currentUser.id" class="ml-auto rounded-lg bg-blue-500 p-2 text-white">
                        {{ message.text }}
                    </div>
                    <div v-else class="mr-auto rounded-lg bg-gray-200 p-2 text-black">
                        {{ message.text }}
                    </div>
                </div>
            </div>
        </div>
        <small v-if="someIsTyping" class="text-gray-700"> Someone is typing... </small>
        <div class="absolute bottom-0 flex w-full items-center">
            <input
                type="text"
                v-model="newMessage"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="h-[50px] flex-1 rounded-bl-lg border px-2"
            />
            <button @click="sendMessage" class="h-[50px] rounded-br-lg bg-blue-500 px-4 text-white">Send</button>
        </div>
    </div>
</template>

<style scoped></style>

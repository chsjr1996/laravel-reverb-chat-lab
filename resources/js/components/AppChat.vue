<script setup lang="ts">
/**
 * @todo allow to detect multiple users typing at the same time
 */

import { type ChatRoom, type Message, type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import axios from 'axios';
import { nextTick, onMounted, ref, watch } from 'vue';

type whisperTypingResponse = {
    userID: number;
    userName: string;
};

const props = defineProps<{
    room: ChatRoom;
}>();

const page = usePage<SharedData>();
const currentUser = page.props.auth.user as User;

const roomEcho = useEcho(`chat.room.${props.room.id}`);

const messages = ref<Message[]>([]);
const newMessage = ref('');
const messagesContainer = ref<HTMLDivElement | null>(null);
const someIsTyping = ref(false);
const someIsTypingTimer = ref<number | null>(null);
const isTypingUser = ref<Pick<User, 'id' | 'name'> | null>(null);

watch(
    messages,
    () => {
        nextTick(() => {
            if (!messagesContainer.value) return;

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
            .then(() => {
                newMessage.value = '';
            });
    }
};

const sendTypingEvent = () => {
    roomEcho.channel().whisper('typing', {
        userID: currentUser.id,
        userName: currentUser.name,
    });
};

onMounted(() => {
    axios.get(`/chat/room/${props.room.id}/message`).then((response) => {
        messages.value = response.data;
    });

    roomEcho
        .channel()
        .listen('MessageSent', (response: { message: Message }) => {
            messages.value.push(response.message);
        })
        .listenForWhisper('typing', (response: whisperTypingResponse) => {
            someIsTyping.value = true;
            isTypingUser.value = {
                id: response.userID,
                name: response.userName,
            };

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
        <small v-if="someIsTyping" class="text-gray-700"> {{ isTypingUser?.name }} is typing... </small>
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

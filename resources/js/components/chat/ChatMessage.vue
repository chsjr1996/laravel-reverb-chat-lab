<script setup lang="ts">
/**
 * @todo allow to detect multiple users typing at the same time
 */

import { getMessages, saveMessage } from '@/services/chatMessageService';
import { type ChatRoom, type Message, type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import { nextTick, onMounted, ref, watch } from 'vue';
import { formatTime } from '@/lib/utils';

type whisperTypingResponse = {
    userID: number;
    userName: string;
};

const props = defineProps<{
    room: ChatRoom;
}>();

const roomEcho = useEcho(`chat.room.${props.room.id}`);
const page = usePage<SharedData>();
const currentUser = page.props.auth.user as User;

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

const sendMessage = async () => {
    if (newMessage.value.trim() === '') {
        return;
    }

    await saveMessage(props.room.id, newMessage.value);
    newMessage.value = '';
};

const sendTypingEvent = () => {
    roomEcho.channel().whisper('typing', {
        userID: currentUser.id,
        userName: currentUser.name,
    });
};

const fetchMessagesFromApi = async () => {
    const [apiMessages, error] = await getMessages(props.room.id);

    if (error) {
        // TODO: show error notification
        return;
    }

    messages.value = apiMessages;
};

onMounted(() => {
    fetchMessagesFromApi();

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
                    <div v-if="message.user_id === currentUser.id" class="ml-auto rounded-lg bg-blue-500 px-4 py-2 text-white">
                        <p>{{ message.text }}</p>
                        <span class="text-[9px] block text-right">{{ formatTime(message.created_at) }}</span>
                    </div>
                    <div v-else class="mr-auto rounded-lg bg-gray-200 px-4 py-2 text-black">
                        <strong v-if="room.is_group">{{ message.user!.name }}</strong>
                        <p>{{ message.text }}</p>
                        <span class="text-[9px] block text-right">{{ formatTime(message.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <small v-if="someIsTyping" class="absolute bottom-[55px] left-[10px] text-gray-500"> {{ isTypingUser?.name }} is typing... </small>
        <div class="absolute bottom-0 flex w-full items-center">
            <input
                type="text"
                v-model="newMessage"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="h-[50px] flex-1 border-t px-2"
            />
            <button @click="sendMessage" class="h-[50px] rounded-br-lg bg-blue-500 px-4 text-white">Send</button>
        </div>
    </div>
</template>

<style scoped></style>

<script setup lang="ts">
/**
 * @todo missing types
 */

import { type SharedData, type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import axios from 'axios';
import { nextTick, onMounted, ref, watch } from 'vue';

const props = defineProps<{
    friend: User;
}>();

const page = usePage<SharedData>();
const currentUser = page.props.auth.user as User;

// TODO: Use unified channel here, fix auth and typing event problems!
const fromEcho = useEcho(`chat.${currentUser.id}`);
const toEcho = useEcho(`chat.${props.friend.id}`);

const messages = ref([]);
const newMessage = ref('');
const messagesContainer = ref(null);
const isFriendTyping = ref(false);
const isFriendTypingTimer = ref(null);

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
            .post(`/chat/messages/${props.friend.id}`, {
                text: newMessage.value,
            })
            .then((response) => {
                messages.value.push(response.data);
                newMessage.value = '';
            });
    }
};

const sendTypingEvent = () => {
    fromEcho.channel().whisper('typing', {
        userID: currentUser.id,
    });
};

onMounted(() => {
    axios.get(`/chat/messages/${props.friend.id}`).then((response) => {
        messages.value = response.data;
    });

    toEcho.channel()
        .listen('MessageSent', (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper('typing', (response) => {
            console.log('???');
            isFriendTyping.value = response.userID === props.friend.id;

            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }

            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });
});
</script>

<template>
    <div>
        <div class="flex h-80 flex-col justify-end">
            <div ref="messagesContainer" class="max-h-fit overflow-y-auto p-4">
                <div v-for="message in messages" :key="message.id" class="mb-2 flex items-center">
                    <div v-if="message.sender_id === currentUser.id" class="ml-auto rounded-lg bg-blue-500 p-2 text-white">
                        {{ message.text }}
                    </div>
                    <div v-else class="mr-auto rounded-lg bg-gray-200 p-2">
                        {{ message.text }}
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <input
                type="text"
                v-model="newMessage"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="flex-1 rounded-lg border px-2 py-1"
            />
            <button @click="sendMessage" class="ml-2 rounded-lg bg-blue-500 px-4 py-1 text-white">Send</button>
        </div>
        <small v-if="isFriendTyping" class="text-gray-700"> {{ friend.name }} is typing... </small>
    </div>
</template>

<style scoped></style>

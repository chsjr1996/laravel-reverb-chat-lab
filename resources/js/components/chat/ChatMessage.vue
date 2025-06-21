<script setup lang="ts">
/**
 * @todo allow to detect multiple users typing at the same time
 */

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { formatTime, getFriendData, getRoomName } from '@/lib/utils';
import { getMessages, saveMessage } from '@/services/chatMessageService';
import { type ChatRoom, type Message, type SharedData, type User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { useEcho, useEchoPresence } from '@laravel/echo-vue';
import { ChevronLeft } from 'lucide-vue-next';
import { nextTick, onMounted, ref, watch } from 'vue';

type whisperTypingResponse = {
    userID: number;
    userName: string;
};

const props = defineProps<{
    room?: ChatRoom;
    user?: { data: Pick<User, 'id' | 'name'> };
}>();

const roomEcho = props.room ? useEcho(`chat.room.${props.room.id}`) : null;
const chatPresenceEcho = useEchoPresence('chat');
const page = usePage<SharedData>();
const currentUser = page.props.auth.user as User;
const isRoom = !!props.room;
const roomUsers = isRoom ? props.room.users : [];

const messages = ref<Message[]>([]);
const newMessage = ref('');
const messagesContainer = ref<HTMLDivElement | null>(null);
const someIsTyping = ref(false);
const someIsTypingTimer = ref<number | null>(null);
const isUserOnline = ref(false);
const isTypingUser = ref<Pick<User, 'id' | 'name'> | null>(null);
const inputMessage = ref<HTMLInputElement | null>(null);

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
    if (newMessage.value.trim() === '') return;

    const [messageData, error] = await saveMessage(newMessage.value, props.room?.id, props.user?.data.id);

    if (error) {
        // TODO: show error notification
        return;
    }

    newMessage.value = '';

    if (messageData.data.new_chat && messageData.data.chat_room_id) {
        window.location.href = `/chat/room/${messageData.data.chat_room_id}`;
    }
    // TODO: improve this, we should not reload the page because it re-run all queries
    router.reload();
};

const sendTypingEvent = () => {
    if (!roomEcho) return;

    roomEcho.channel().whisper('typing', {
        userID: currentUser.id,
        userName: currentUser.name,
    });
};

const fetchMessagesFromApi = async () => {
    if (!props.room) return;

    const [apiMessages, error] = await getMessages(props.room.id);

    if (error) {
        // TODO: show error notification
        return;
    }

    messages.value = apiMessages;
};

const getChatName = () => {
    if (isRoom) {
        return getRoomName(props.room, currentUser.id);
    }

    if (props.user) {
        return props.user.data.name;
    }

    return 'Unknown';
};

const listenEchoEvents = () => {
    if (!roomEcho) return;

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
};

const startPresenceEcho = () => {
    if (isRoom && props.room.is_group) return;

    const friendUserId = isRoom ? getFriendData(roomUsers, currentUser.id)!.id : props.user!.data.id;

    chatPresenceEcho.channel().here((users: User[]) => {
        isUserOnline.value = users.some((user) => user.id === friendUserId);
    });

    chatPresenceEcho.channel().joining((user: User) => {
        if (user.id === friendUserId) isUserOnline.value = true;
    });

    chatPresenceEcho.channel().leaving((user: User) => {
        if (user.id === friendUserId) isUserOnline.value = false;
    });
};

onMounted(() => {
    fetchMessagesFromApi();
    listenEchoEvents();
    startPresenceEcho();

    if (inputMessage.value) {
        inputMessage.value.focus();
    }
});
</script>

<template>
    <div class="h-[calc(100vh-98px)]">
        <div id="chatMessageHeader" class="flex h-[50px] w-full items-center border-b">
            <Link href="/chat/room">
                <Button class="ml-4 h-[30px] w-[30px] cursor-pointer" variant="ghost">
                    <ChevronLeft />
                </Button>
            </Link>
            <span class="text-foreground ml-4 font-normal">{{ getChatName() }}</span>
            <div v-if="!isRoom || !room?.is_group" class="ml-4 flex">
                <span :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'" class="inline-block h-3 w-3 rounded-full"></span>
            </div>
        </div>
        <div class="flex h-[calc(100%-100px)] flex-col justify-end">
            <div
                ref="messagesContainer"
                class="max-h-fit overflow-y-auto px-4 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 [&::-webkit-scrollbar-track]:bg-gray-100 dark:[&::-webkit-scrollbar-track]:bg-neutral-700"
            >
                <div v-for="message in messages" :key="message.id" class="mt-2 mb-2 flex items-center">
                    <div v-if="message.user_id === currentUser.id" class="max-w-[220px] ml-auto rounded-lg bg-blue-500 px-4 py-2 text-white">
                        <p>{{ message.text }}</p>
                        <span class="block text-right text-[9px]">{{ formatTime(message.created_at) }}</span>
                    </div>
                    <div v-else class="max-w-[220px] mr-auto rounded-lg bg-gray-200 px-4 py-2 text-black">
                        <strong v-if="room && room.is_group">{{ message.user!.name }}</strong>
                        <p>{{ message.text }}</p>
                        <span class="block text-right text-[9px]">{{ formatTime(message.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <small v-if="someIsTyping" class="absolute bottom-[55px] left-[10px] text-gray-500"> {{ isTypingUser?.name }} is typing... </small>
        <div class="absolute bottom-0 flex w-full items-center">
            <Input
                ref="inputMessage"
                type="text"
                v-model="newMessage"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
                placeholder="Type a message..."
                class="h-[50px] flex-1 rounded-none border-0 border-t px-4 focus-visible:ring-0 focus-visible:outline-0"
            />
            <Button @click="sendMessage" class="h-[50px] cursor-pointer rounded-none rounded-br-lg bg-blue-500 px-4 text-white hover:bg-blue-600">
                Send
            </Button>
        </div>
    </div>
</template>

<style scoped></style>

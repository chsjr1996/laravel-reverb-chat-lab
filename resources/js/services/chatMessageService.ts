import { api } from './api';

export const getMessages = async (chatRoomId: number) => {
    try {
        const { data } = await api.get(`/chat/message/${chatRoomId}`);

        return [data, false];
    } catch (error) {
        console.error('Error fetching messages:', error);

        return [null, true];
    }
};

export const saveMessage = async (chatRoomId: number, text: string) => {
    try {
        const { data } = await api.post(`/chat/message/${chatRoomId}`, { text });

        return [data, false];
    } catch (error) {
        console.error('Error saving message:', error);

        return [null, true];
    }
};

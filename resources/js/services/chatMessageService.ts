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

export const saveMessage = async (text: string, chatRoomId?: number, friendId?: number) => {
    try {
        const chatRoomIdToUse = chatRoomId || 0;
        const requestData: { text: string; friend_id?: number } = {
            text,
        };

        if (friendId) requestData['friend_id'] = friendId;

        const { data } = await api.post(`/chat/message/${chatRoomIdToUse}`, requestData);

        return [data, false];
    } catch (error) {
        console.error('Error saving message:', error);

        return [null, true];
    }
};

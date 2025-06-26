import type { ChatRoom, User } from '@/types';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const getAppName = (): string => {
    return import.meta.env.VITE_APP_NAME || 'My Application';
};

export const getFriendData = (users: User[], currentUserId: number): User | undefined => {
    return users.find((user) => user.id !== currentUserId);
};

export const getRoomName = (room: ChatRoom, currentUserId: number) => {
    if (room.is_group) {
        return room.name;
    }

    return getFriendData(room.users, currentUserId)!.name;
};

export const formatTime = (datetime: string, onlyTime = true): string => {
    const options: Intl.DateTimeFormatOptions = onlyTime
        ? { hour: '2-digit', minute: '2-digit' }
        : { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };

    return new Date(datetime).toLocaleTimeString([], options);
};

export const formatDate = (datetime: string): string => new Date(datetime).toLocaleDateString();

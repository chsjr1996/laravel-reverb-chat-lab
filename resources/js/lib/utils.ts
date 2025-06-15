import type { User } from '@/types';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const getAppName = (): string => {
    return import.meta.env.VITE_APP_NAME || 'My Application';
};

export const getFriendData = (users: User[], currentUserId: number): User => {
    return users.filter((user) => user.id !== currentUserId)[0];
};

export const formatTime = (datetime: string, onlyTime = true): string => {
    const options = onlyTime
        ? { hour: '2-digit', minute: '2-digit' }
        : { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };

    return new Date(datetime).toLocaleTimeString([], options);
};

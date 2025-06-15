import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const getAppName = (): string => {
    return import.meta.env.VITE_APP_NAME || 'My Application';
}

export const formatTime = (datetime: string): string => {
    return new Date(datetime).toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit',
    });
};

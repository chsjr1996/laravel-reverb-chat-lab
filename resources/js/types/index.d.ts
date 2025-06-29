import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    screen?: string,
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Message {
    id: number,
    chat_room_id: number,
    user_id: number,
    text: string,
    created_at: string,
    updated_at: string,
    deleted_at?: string | null,
    user?: Pick<User, 'id' | 'name' | 'avatar'>,
    new_chat?: boolean;
}

export interface ChatRoom {
    id: number;
    name: string;
    avatar?: string;
    is_group?: boolean;
    created_at: string;
    updated_at: string;
    users: User[];
    messages?: Message[];
}

export type BreadcrumbItemType = BreadcrumbItem;

export type ChatActionModesType = 'default' | 'create_group';

import { api } from './api';

export const getUsers = async (queryParams: string[] = []) => {
    try {
        const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
        const { data } = await api.get('/users' + queryString);

        return [data, false];
    } catch (error) {
        console.error('Error fetching users:', error);

        return [null, true];
    }
};

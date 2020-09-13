import axios from 'axios';

const prefix = '/api';

export default {
    async getGroups() {
        const { data } = await axios.get(`${prefix}/groups`);
        return data;
    },

    async changeDone(id, isDone) {
        const { data } = await axios.patch(`${prefix}/tasks/${id}`, { is_done: isDone });
        return data;
    },

    async removeTask(id) {
        const { status } = await axios.delete(`${prefix}/tasks/${id}`);
        return 200 === status;
    },

    async updateTaskText(id, text) {
        const { data } = await axios.patch(`${prefix}/tasks/${id}`, { text });
        return data;
    },

    async addTask(groupId, text) {
        const { data } = await axios.post(`${prefix}/tasks/`, { text, group_id: groupId });
        return data;
    },
}

import axios from 'axios';

const prefix = '/api';

export default {
    async getGroups() {
        const { data } = await axios.get(`${prefix}/groups`);

        return data;
    },

    async markAsDone(id) {
        const { data } = await axios.patch(`${prefix}/tasks/${id}`, { is_done: true });
        console.log(data);

        return data;
    },
}

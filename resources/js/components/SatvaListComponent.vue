<template>
    <div class="row d-flex justify-content-center container">
        <div class="col-md-8">
            <div class="card-hover-shadow-2x mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal"><i class="fa fa-tasks"></i>&nbsp;Satva tasks</div>
                </div>
                <div class="scroll-area-sm">
                    <div style="position: static;" class="ps ps--active-y">
                        <div class="ps-content">
                            <ul v-for="(group, groupIndex) in groups" :key="group.id" class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-light group-section">
                                    <span>{{ group.name }}</span>
                                    <label v-if="isTaskBeingCreated === group.id" class="create-task-label">
                                        <input @keyup.enter="addTask(group.id, $event)" v-on:blur="onCreateTaskBlur()" type="text" autofocus />
                                    </label>
                                    <button v-else type="button" v-on:click="createTask(group.id)">+</button>
                                </li>
                                <draggable v-model="group.tasks" group="people" @end="taskGroupChanged($event)" :data-group-id="group.id" :data-group-index="groupIndex">
                                    <li v-for="task in group.tasks" :key="task.id" class="list-group-item">
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-2">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" v-bind:checked="task.is_done" @click="changeDone(group.id, task.id)" class="custom-control-input" :id="'taskCheck' + task.id">
                                                        <label class="custom-control-label" :for="'taskCheck' + task.id">&nbsp;</label>
                                                    </div>
                                                </div>
                                                <div class="widget-content-left">
                                                    <div class="widget-heading">
                                                        <div v-if="isTaskBeingEdited !== task.id" v-on:click="editTask(task.id)">{{ task.text }}</div>
                                                        <label v-else class="edit-task-label">
                                                            <input @keyup.enter="updateTask(group.id, task.id, $event)" v-on:blur="onTaskBlur()" type="text" :value=task.text autofocus />
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="widget-content-right">
                                                    <button class="btn-secondary" type="button" v-on:click="removeTask(group.id, task.id)">X</button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </draggable>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import draggable from 'vuedraggable'
    import http from '../services/http';

    export default {
        components: {
            draggable,
        },
        data: () => ({
            checkedTaskMeta: null,
            isTaskBeingCreated: null,
            isTaskBeingEdited: null,
            groups: null
        }),
        async beforeCreate() {
            this.groups = await http.getGroups();
        },
        methods: {
            updateTask: async function (groupId, taskId, e) {
                const groupIndex = this.groups.findIndex(group => groupId === group.id);
                const taskIndex = this.groups[groupIndex].tasks.findIndex(task => taskId === task.id);

                this.groups[groupIndex].tasks[taskIndex].text = (await http.updateTaskText(groupId, e.target.value)).text;

                this.isTaskBeingEdited = null;
            },
            taskGroupChanged: function (e) {
                const { from, to, newIndex } = e;

                const fromGroupId = from.dataset.groupId;
                const toGroupId = to.dataset.groupId;

                if (toGroupId !== fromGroupId) {
                    const toGroupIndex = to.dataset.groupIndex;
                    const movedTask = this.groups[toGroupIndex].tasks[newIndex];

                    http.updateTaskGroup(movedTask.id, toGroupId);
                }

            },
            createTask: async function (groupId) {
                this.isTaskBeingCreated = groupId;
            },
            addTask: async function (groupId, e) {
                const groupIndex = this.groups.findIndex(group => groupId === group.id);
                const addedTask = await http.addTask(groupId, e.target.value);

                this.groups[groupIndex].tasks.push(addedTask);
                this.isTaskBeingCreated = null;
            },
            onCreateTaskBlur: async function () {
                this.isTaskBeingCreated = null;
            },
            onTaskBlur: async function () {
                this.isTaskBeingEdited = null;
            },
            editTask: function (taskId) {
                this.isTaskBeingEdited = taskId;
            },
            changeDone: async function (groupId, taskId) {
                const groupIndex = this.groups.findIndex(group => groupId === group.id);
                const taskIndex = this.groups[groupIndex].tasks.findIndex(task => taskId === task.id);

                const isDone = this.groups[groupIndex].tasks[taskIndex].is_done;
                const updatedTask = await http.changeDone(taskId, !isDone);

                this.groups[groupIndex].tasks[taskIndex].is_done = updatedTask.is_done;
            },
            removeTask: async function (groupId, taskId) {
                const groupIndex = this.groups.findIndex(group => groupId === group.id);
                const taskIndex = this.groups[groupIndex].tasks.findIndex(task => taskId === task.id);

                await http.removeTask(taskId);
                this.$delete(this.groups[groupIndex].tasks, taskIndex);
            },
        }
    }
</script>

<style scoped lang="scss">
    button {
        outline: none;
        border-radius: 8px;
    }

    .widget {
        &-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    }

    .group-section {
        min-height: 50px;
        display: flex;
        justify-content: space-between;
    }

    .edit-task-label, .create-task-label {
        margin: 0;

        input {
            min-width: 300px;
            outline: none;
            border: 1px solid #818182;
            border-radius: 8px;
            padding-left: 10px;
            color: #495057;
        }
    }
</style>

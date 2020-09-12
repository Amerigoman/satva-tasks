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
                            <ul v-for="group in groups" :key="group.id" class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-light">{{ group.name }}</li>
                                <li v-for="task in group.tasks" :key="task.id" class="list-group-item">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left mr-2">
                                                <div class="custom-checkbox custom-control">
                                                    <input class="custom-control-input" :id="'taskCheck' + task.id" type="checkbox"
                                                           v-model="checkedTaskMeta" :true-value="{ groupId: group.id, taskId: task.id }" :false-value="null">
                                                    <label class="custom-control-label" :for="'taskCheck' + task.id">&nbsp;</label>
                                                </div>
                                            </div>
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    {{ task.text }}
                                                    <div v-if="task.is_done" class="badge badge-pill badge-info ml-2">Done</div>
                                                </div>
                                            </div>
                                            <div class="widget-content-right"> <button class="border-0 btn-transition btn btn-outline-success"> <i class="fa fa-check"></i></button> <button class="border-0 btn-transition btn btn-outline-danger"> <i class="fa fa-trash"></i> </button> </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="d-block text-right card-footer">
                    <button class="mr-2 btn btn-link btn-sm" v-bind:class="{ disabled: !checkedTaskMeta }" v-on:click="markAsDone()">Mark as Done</button>
                    <button class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import http from '../services/http';
    export default {
        data: () => ({
            checkedTaskMeta: null,
            groups: null
        }),
        async beforeCreate() {
            this.groups = await http.getGroups();
            console.log(this.groups)
        },
        methods: {
            markAsDone: async function () {
                const { groupId, taskId } = this.$data.checkedTaskMeta;

                console.log(this.$data.groups);
                const { is_done } = await http.markAsDone(taskId);
                this.$data.groups[groupId].tasks[taskId].is_done = is_done;
                console.log(this.$data.groups);
            }
        }
    }
</script>

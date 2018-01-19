<template>
    <ul class="list-group todolist">
        <li class="list-group-item">
            <input type="text" placeholder="Type and hit enter" class="form-control todo-input" v-model="newTodo"
                   @keyup.enter="saveTodo" v-if="showAdd">
            <input type="text" placeholder="Type and hit enter" class="form-control todo-input is-valid"
                   @keyup.enter="saveTodo" v-if="showUpdate" v-model="update[updateIndex].task">
        </li>
        <li class="list-group-item todo-info">
            <span class="task-info-text">
                {{ taskLeftCount }} task Left , {{ taskDoneCount }} task done
            </span>
            <div class="btn-group float-right">
                <button class="todo-action-btn" @click="showAllTask">All</button>
                <button class="todo-action-btn" @click="showActiveTask">Active</button>
                <button class="todo-action-btn" @click="showDoneTask">Completed</button>
            </div>
        </li>

        <div class="todos-task-wrapper">
            <li class="list-group-item single-todo" :class="{'done' : todo.done}" :id="'todo-id-' + index"
                v-for="(todo,index) in todosTasks">
                {{ todo.task }}
                <div class="btn-group float-right todo-action-btn-group">
                    <button class="btn btn-success btn-sm" v-if="!todo.done" @click="makeDone(todo , index)"><i
                            class="fa fa-check"></i></button>
                    <button class="btn btn-warning btn-sm" v-if="todo.done" @click="makeUnDone(todo , index)"><i
                            class="fa fa-undo"></i></button>
                    <button class="btn btn-info btn-sm" @click="updateTodo(todo , index)"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger btn-sm" @click="deleteTodo(todo , index)"><i class="fa fa-trash-o"></i>
                    </button>
                </div>
            </li>
        </div>
        <modal name="update-task" height="160">
            <div style="padding: 15px;">
                <!-- @inputfield task -->
                <div class="form-group">
                    <label for="task">Edit Task</label>
                    <input type="text" class="form-control" id="task" v-model="todos[updateIndex].task" placeholder="Edit Task">
                </div>
                <!-- @inputfield Form Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">save</button>
                </div>
            </div>
        </modal>
    </ul>
</template>
<script>
    export default {
        data() {
            return {
                todos: [],
                todosTmp: [],
                newTodo: '',
                showAdd: true,
                showUpdate: false,
                updateIndex: 1
            }
        },
        computed: {
            todosTasks() {
                return this.todosTmp;
            },
            taskLeftCount() {
                var leftTask = 0;
                this.todos.forEach(task => {
                    if (!task.done) leftTask++;
                });
                return leftTask;
            },
            taskDoneCount() {
                var doneTask = 0;
                this.todos.forEach(task => {
                    if (task.done) doneTask++;
                });
                return doneTask;
            }
        },
        created() {
            axios.get('todos')
                .then(response => {
                    this.todos =  response.data;
                    this.todosTmp =  response.data;
                });
        },
        methods: {
            saveTodo() {

                axios.post('todos', {task: this.newTodo})
                    .then(response => {
                        this.todos.unshift({task: this.newTodo, done: 0});
                        this.newTodo = '';
                    });
            },
            makeDone(todo , index) {
                axios.put(`/todos/makedone/${todo.id}`)
                    .then(response => {
                        this.todosTmp[index].done = 1;
                    });
            },
            makeUnDone(todo , index) {
                axios.put(`todos/makeundone/${todo.id}`)
                    .then(response => {
                        this.todosTmp[index].done = 0;
                    });
            },
            deleteTodo(todo , index) {
                axios.delete(`/todos/${todo.id}`)
                    .then(response => {
                        this.todosTmp.splice(index, 1);
                        this.todos.splice(index, 1);
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            updateTodo(todo , index)
            {
//                this.showAdd = false;
//                this.showUpdate = true;
//                this.updateIndex = index;
//                axios.put(`/todos/${todo.id}` , this.todos[index])
//                    .then(response => {
//                        console.log(response);
//                        this.todosTmp.splice(index, 1);
//                        this.todos.splice(index, 1);
//                    })
//                    .catch(err => {
//                        console.log(err);
//                    });
                this.updateIndex = index;
                this.$modal.show('update-task');

            },
            showAllTask()
            {
                this.todosTmp = this.todos;
            },
            showActiveTask()
            {
                this.todosTmp = this.todos.filter( x => x.done == 0 );
            },
            showDoneTask()
            {
                this.todosTmp = this.todos.filter( x => x.done == 1 );
            }
        }
    }
</script>
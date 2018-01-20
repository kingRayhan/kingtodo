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
            <draggable :list="todosTasks" :options="{animation:200, handle:'.drag-handle'}" @change="updateOrder">
                <li class="list-group-item single-todo"
                    :class="{'done' : todo.done , 'editing-todo' : editingTodo == todo}" :id="'todo-id-' + index"
                    v-for="(todo,index) in todosTasks" :key="index">
                <span class="float-left drag-handle" style="padding-right: 15px;color: #1b1e21;cursor:pointer"
                      v-if="!showEdit">
                    <i class="fa fa-arrows"></i>
                </span>
                    <span v-text="todo.task" class="todo-text" @dblclick="editTodo(todo)"></span>
                    <input type="text" class="edit-todo-input"
                    v-model="todo.task"
                    @keyup.enter="doneEdit(todo)"
                    @keyup.esc="doneEdit(todo)"
                    @blur="doneEdit(todo)"
                    autofocus
                    >
                    <div class="btn-group float-right todo-action-btn-group">
                        <button class="btn btn-success btn-sm" v-if="!todo.done" @click="makeDone(todo , index)"><i
                                class="fa fa-check"></i></button>
                        <button class="btn btn-warning btn-sm" v-if="todo.done" @click="makeUnDone(todo , index)"><i
                                class="fa fa-undo"></i></button>
                        <button class="btn btn-info btn-sm" @click="editTodo(todo)"><i
                                class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-sm" @click="deleteTodo(todo , index)"><i
                                class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </li>
            </draggable>
        </div>
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
                updateIndex: 1,
                newSortedTodos: [],
                showEdit: false,
                editingTodo: null
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
                    this.todos = response.data;
                    this.todosTmp = response.data;
                });
        },
        methods: {
            saveTodo() {
                axios.post('todos', {task: this.newTodo})
                    .then(response => {
                        this.todos.unshift({task: this.newTodo, done: 0});
                        this.newTodo = '';
                    }).catch(error => {
                    swal({
                        title: error.response.data.errors.task[0],
                        icon: 'error'
                    });
                });
            },
            makeDone(todo, index) {
                axios.put(`/todos/makedone/${todo.id}`)
                    .then(response => {
                        this.todosTmp[index].done = 1;
                    });
            },
            makeUnDone(todo, index) {
                axios.put(`todos/makeundone/${todo.id}`)
                    .then(response => {
                        this.todosTmp[index].done = 0;
                    });
            },
            deleteTodo(todo, index) {
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this!!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            axios.delete(`/todos/${todo.id}`)
                                .then(response => {
                                    this.todosTmp.splice(index, 1);
                                })
                                .catch(err => {
                                    console.log(err);
                                });
                            swal("Poof! Your imaginary file has been deleted!", {
                                icon: "success",
                            });
                        } else {
                            swal("Cancelled", "Your todo is safe :)", "error");
                        }
                    });
            },
            editTodo(todo) {
                this.editingTodo = todo;
            },
            doneEdit(todo){
                if(!this.editingTodo) return;
                this.editingTodo = null;

                // Delete , if user make it empty
//                if(!todo.title)
//                {
//                    alert('empty');
//                }

                // Update This Todo
                axios.put(`todos/${todo.id}` , todo)
                    .then(response => {
                        console.log(response);
                    });
            },
            showAllTask() {
                axios.get('todos')
                    .then(response => {
                        this.todos = response.data;
                        this.todosTmp = response.data;
                    });
            },
            showActiveTask() {
                this.todosTmp = this.todos.filter(x => x.done == 0);
            },
            showDoneTask() {
                this.todosTmp = this.todos.filter(x => x.done == 1);
            },
            updateOrder() {
                axios.post('/todos/updateorder', this.todosTasks).then(r => {
                });
            }
        }
    }
</script>
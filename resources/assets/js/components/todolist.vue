<template>
    <ul class="list-group todolist">
        <li class="list-group-item">
            <input type="text" placeholder="Type and hit enter" class="form-control todo-input" v-model="newTodo"
                   @keyup.enter="saveTodo">
        </li>
        <li class="list-group-item todo-info">
            <span class="task-info-text">
                {{ taskLeftCount }} task Left , {{ taskDoneCount }} task done
            </span>
            <div class="btn-group float-right">
                <button class="todo-action-btn" @click="visibility = 'all'">All</button>
                <button class="todo-action-btn" @click="visibility = 'active'">Active</button>
                <button class="todo-action-btn" @click="visibility = 'completed'">Completed</button>
                <button class="todo-action-btn" @click="clearCompleted">Clear Completed</button>
            </div>
        </li>

        <div class="todos-task-wrapper">
            <draggable :list="todosTasks" :options="{animation:200, handle:'.drag-handle'}" @change="updateOrder">
                <li class="list-group-item single-todo"
                    :class="{'done' : todo.done , 'editing-todo' : editingTodo == todo}" :id="'todo-id-' + index"
                    v-for="(todo,index) in todosTasks" :key="index">
                <span class="float-left drag-handle">
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
                        <button class="btn btn-success btn-sm" v-if="!todo.done" @click="makeDone(todo , index)">
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-warning btn-sm" v-if="todo.done" @click="makeUnDone(todo , index)">
                            <i class="fa fa-undo"></i>
                        </button>
                        <button class="btn btn-info btn-sm" @click="editTodo(todo)">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" @click="deleteTodo(todo , index)">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </li>
            </draggable>
        </div>
    </ul>
</template>
<style scoped="">
    .drag-handle{
        padding-right: 15px;color: #1b1e21;cursor:pointer
    }
</style>
<script>
    export default {
        filters:{

        },
        data() {
            return {
                todos: [],
                todosTmp: [],
                newTodo: '',
                newSortedTodos: [],
                editingTodo: null,
                visibility: 'all'
            }
        },
        computed: {
            todosTasks() {
                if(this.visibility == 'all')
                    return this.todos;
                else if (this.visibility == 'active')
                {
                    return this.todos.filter( todo => {
                        return !todo.done
                    } );
                }
                else if(this.visibility = 'completed'){
                    return this.todos.filter( todo => {
                        return todo.done
                    } );
                }
                return this.todos;
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
                this.todos[index].done = 1;
                axios.put(`/todos/makedone/${todo.id}`)
                    .then(response => {

                    });
            },
            makeUnDone(todo, index) {
                this.todos[index].done = 0;
                axios.put(`todos/makeundone/${todo.id}`)
                    .then(response => {

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
                                this.todos.splice(index, 1);
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
            clearCompleted()
            {
                this.todos = this.todos.filter( todo => {
                    return !todo.done;
                } );
                axios.post('todos/clearcompleted');
            },
            editTodo(todo) {
                this.editingTodo = todo;
            },
            doneEdit(todo){
                if(!this.editingTodo) return;
                this.editingTodo = null;
                axios.put(`todos/${todo.id}` , todo)
                    .then(response => {
                        console.log(response);
                    });
            },
            updateOrder() {
                axios.post('/todos/updateorder', this.todosTasks).then(r => {});
            }
        }
    }
</script>
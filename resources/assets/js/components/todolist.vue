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
            <draggable  :list="todosTasks" :options="{animation:200, handle:'.drag-handle'}" @change="updateOrder">
            <li class="list-group-item single-todo" :class="{'done' : todo.done}" :id="'todo-id-' + index"
                v-for="(todo,index) in todosTasks" :key="index" @dblclick="showEdit = true">
                <span class="float-left drag-handle" style="padding-right: 15px;color: #1b1e21;cursor:pointer" v-if="!showEdit">
                    <i class="fa fa-arrows"></i>
                </span>
                <span v-text="todo.task" v-if="!showEdit"></span>
                <input type="text" :value="todo.task" class="edit-todo" v-if="showEdit" @blur="showEdit = false" autofocus>
                <div v-if="!showEdit" class="btn-group float-right todo-action-btn-group">
                    <button class="btn btn-success btn-sm" v-if="!todo.done" @click="makeDone(todo , index)"><i
                            class="fa fa-check"></i></button>
                    <button class="btn btn-warning btn-sm" v-if="todo.done" @click="makeUnDone(todo , index)"><i
                            class="fa fa-undo"></i></button>
                    <button class="btn btn-info btn-sm" @click="updateTodo(todo , index)"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger btn-sm" @click="deleteTodo(todo , index)"><i class="fa fa-trash-o"></i>
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
                showEdit: false
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
                        }else{
                            swal("Cancelled", "Your todo is safe :)", "error");
                        }
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
                axios.get('todos')
                    .then(response => {
                        this.todos =  response.data;
                        this.todosTmp =  response.data;
                    });
            },
            showActiveTask()
            {
                this.todosTmp = this.todos.filter( x => x.done == 0 );
            },
            showDoneTask()
            {
                this.todosTmp = this.todos.filter( x => x.done == 1 );
            },
            updateOrder()
            {
                axios.post('/todos/updateorder' , this.todosTasks)
                    .then(r =>{
                        console.log(r);
                    });
            }
        }
    }
</script>
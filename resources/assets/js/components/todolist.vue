<template>
    <div>
    <ul class="list-group todolist">
        <li class="list-group-item todo-input-wrap">
            <input type="text" placeholder="Type and hit enter" class="form-control todo-input" v-model="newTodo"
                   @keyup.enter="saveTodo">
        </li>
        <li class="list-group-item todo-info strong" v-if="todos.length">
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
            <draggable :list="todosTasks" :options="{animation:200, handle:'.shorter'}" @change="updateOrder">
                <li class="list-group-item single-todo"
                    :class="{'done' : todo.done , 'editing-todo' : editingTodo == todo}" :id="'todo-id-' + index"
                    v-for="(todo,index) in todosTasks" :key="index">
                    <div class="king_checkbox_wrapper">
                        <button class="king_checkbox_incomplete" v-if="!todo.done" @click="makeDone(todo , index)"></button>
                        <button class="king_checkbox_complete" v-if="todo.done" @click="makeUnDone(todo , index)"></button>
                    </div>
                    <span v-text="todo.task" class="todo-text" @dblclick="editTodo(todo)"></span>
                    <input type="text" class="edit-todo-input"
                    v-model="todo.task"
                    @keyup.enter="doneEdit(todo)"
                    @keyup.esc="doneEdit(todo)"
                    @blur="doneEdit(todo)"
                    autofocus
                    >
                    <div class=" trash_button_wrapper todo-action-btn-group">
                        
                        <button class="king_delete_btn" @click="deleteTodo(todo , index)"></button>
                        <div class="shorter">
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#000000" d="M9,3H11V5H9V3M13,3H15V5H13V3M9,7H11V9H9V7M13,7H15V9H13V7M9,11H11V13H9V11M13,11H15V13H13V11M9,15H11V17H9V15M13,15H15V17H13V15M9,19H11V21H9V19M13,19H15V21H13V19Z" />
                            </svg>
                        </div>
                    </div>
                </li>
            </draggable>
        </div>
    </ul>
    <footerCreedit></footerCreedit>
    </div>
</template>
<script>
    import footerCreedit from './footer-credit.vue';
    export default {
        components:{
            footerCreedit
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
                if(!todo.task.length) 
                {
                    swal({
                        title: "Do you want to delete this?",
                        text: "Once deleted, you will not be able to recover this!!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            axios.delete(`/todos/${todo.id}`)
                                .then(response => {
                                    this.todos.splice(this.todos.indexOf(todo), 1);
                                })
                                .catch(err => {
                                    console.log(err);
                                });
                            swal("Poof! Your imaginary file has been deleted!", {
                                icon: "success",
                            });
                        } else {
                             var i = this.todos.indexOf(todo);
                             this.todos[i] = this.todosTmp[i];
                            swal("Cancelled", "Your todo is safe :)", "error");
                        }
                    });
                }
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
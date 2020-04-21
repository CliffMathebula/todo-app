<template>
  <div>
    <notifications group="task" />

    <kanban-board :stages="stages" :blocks="tasks" @update-block="move">
      <div v-for="stage in stages" :slot="stage">
        <h2>{{ stage }}</h2>
      </div>
      <div v-for="singleTask in tasks" :slot="singleTask.id" :key="singleTask.id" :class="singleTask.dueSoon && singleTask.status !== 'Done' ? 'danger' : ''">
        <div class="card-body">
          <div class='card-title-block'>
            <h4 class="card-title text-truncate" :title="singleTask.title">{{ singleTask.title }}</h4>
          </div>

          <button type="button" aria-label="Close" class="close" @click="deleteTask(singleTask.id)">
            <span aria-hidden="true">×</span>
          </button>

          <p class="card-text">{{ singleTask.description }}</p>
          <a href="#" class="btn btn-primary" @click="showEditTaskDialog(singleTask.id)">update</a>
        </div>
      </div>
    </kanban-board>

    <!-- Modals -->

    <!-- Add new task modal -->
    <div class="modal fade" id="new-task" tabindex="-1" role="dialog" aria-labelledby="newTaskLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newTaskLabel">New Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label class="col-form-label">Title</label>
                <input type="text" :class="errors.title.length > 0 ? 'form-control is-invalid' : 'form-control'" v-model="task.title">

                <template v-if="errors.title.length > 0" v-for="error in errors.title">
                  <span class="invalid-feedback" role="alert">{{ error }}</span>
                </template>
              </div>
              <div class="form-group">
                <label class="col-form-label">Description</label>
                <textarea :class="errors.description.length > 0 ? 'form-control is-invalid' : 'form-control'" v-model="task.description"></textarea>

                <template v-if="errors.description.length > 0" v-for="error in errors.description">
                  <span class="invalid-feedback" role="alert">{{ error }}</span>
                </template>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="add">Add</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Edit existing task modal -->
    <div class="modal fade" id="edit-task" tabindex="-1" role="dialog" aria-labelledby="newTaskLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newTaskLabel">New Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="title" class="col-form-label">Title</label>
                <input type="text" :class="errors.title.length > 0 ? 'form-control is-invalid' : 'form-control'" v-model="modal.edit.form.title">

                <template v-if="errors.title.length > 0" v-for="error in errors.title">
                  <span class="invalid-feedback" role="alert">{{ error }}</span>
                </template>
              </div>
              <div class="form-group">
                <label for="description" class="col-form-label">Description</label>
                <textarea :class="errors.description.length > 0 ? 'form-control is-invalid' : 'form-control'" v-model="modal.edit.form.description"></textarea>

                <template v-if="errors.description.length > 0" v-for="error in errors.description">
                  <span class="invalid-feedback" role="alert">{{ error }}</span>
                </template>
              </div>
              <div class="form-group">
                <label for="due" class="col-form-label">Due Date</label>
                <datepicker :bootstrap-styling="true" v-model="modal.edit.form.due"></datepicker>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveChanges">Update</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';

  export default {
    components: {
      Datepicker
    },
    props: [
      'tasks'
    ],
    mounted() {
      console.log('Component mounted.')

      /**
       * Tag tasks that are expiring the following day
       */
      $('li.drag-item').has('div.danger').addClass('alert danger');
    },
    data() {
      return {
        refresh: false,
        modal: {
          new: false,
          edit: {
            visible: false,
            form: {
              id: null,
              title: null,
              description: null,
              due: null
            }
          }
        },
        stages: ['Todo', 'In Progress', 'Done'],
        task: {
          title: '',
          description: ''
        },
        errors: {
          title: [],
          description: [],
          due: []
        }
      }
    },
    watch: {
      /**
       * Watch the edit visible task property
       *
       * @param {bool} value
       */
      'modal.edit.visible': (value) => {
        $('#edit-task').modal(value ? 'show' : 'hide');
      },
      /**
       * Refresh window if value is true
       *
       * @param {bool} value
       */
      refresh: (value) => {
        value ? window.location.reload() : null;
      }
    },
    methods: {
      /**
       * Update task status
       *
       * @param {number} id
       * @param {string} status
       * @param {number} position
       * @return {void}
       */
      move(id, status, position) {
        let task = this.tasks.find(b => b.id === Number(id));

        task.status = status;

        if (task.dueSoon && task !== 'Done') {
          $('li.drag-item').has('div.danger').addClass('alert danger');
        } else if (task.dueSoon && task == 'Done') {
          $('li.drag-item').has('div.danger').removeClass('alert danger');
        }

        axios.post('/task/move', {
                id: id,
                status: status,
                priority: position
              });
      },
      /**
       * Add a new task
       *
       * @return {void}
       */
      add() {
        let self = this;

        axios
          .post('/task/add', this.task)
          .then((response, error) => {
            self.$notify({
              group: 'task',
              title: 'Success',
              text: 'Successfully added a new task!'
            });

            $('#new-task').modal('hide');

            /**
             * Didn't get time to look into this bug
             *
             * self.tasks.push(response.task);
             */

            self.refresh = true;

            self.clearErrors();
          })
          .catch((error) => {
            self.assignAndFixErrors(error);
          });
      },
      /**
       * Delete task
       *
       * @param {number} id
       * @return {void}
       */
      deleteTask(id) {
        /**
         * kanban-board is not reactive, so i can't remove
         * a task from the tasks variable.
         *
         * this.tasks =_.remove(this.tasks, (task) => task.id !== id);
         */

        let self = this;

        axios
          .post('/task/delete', { id: id })
          .then((response) => {
            self.refresh = true;
          })
          .catch((error) => {
            self.$notify({
              group: 'task',
              type: 'warn',
              title: 'Failed',
              text: 'Could not remove task :('
            });
          });
      },
      /**
       * Edit a task
       *
       * @param {number} id
       * @return {void}
       */
      showEditTaskDialog(id) {
        /**
         * Find the task
         */
        let task = this.tasks.find(b => b.id === Number(id));

        this.modal.edit.form = {
          id: id,
          title: task.title,
          description: task.description,
          due: new Date(task.due)
        };

        $('#edit-task').modal('show');
      },
      /**
       * Save changes
       *
       * @return {void}
       */
      saveChanges() {
        let self = this;

        axios
          .post('/task/edit/', this.modal.edit.form)
          .then((response) => {
            self.$notify({
              group: 'task',
              title: 'Success',
              text: 'Successfully updated task!'
            });

            self.refresh = true;
          })
          .catch((error) => {
            self.$notify({
              group: 'task',
              type: 'warn',
              title: 'Failed',
              text: 'Could not update task :('
            });

            self.assignAndFixErrors(error);
          });
      },
      /**
       * Assign errors and set default values
       *
       * @param {JSON} error
       * @return {void}
       */
      assignAndFixErrors(error) {
        if (error.response.data == undefined) {
          return;
        }

        this.errors = error.response.data.errors;

        this.errors.title       = this.errors.title == undefined ? [] : this.errors.title;
        this.errors.description = this.errors.description == undefined ? [] : this.errors.description;
        this.errors.due         = this.errors.due == undefined ? [] : this.errors.due;
      },
      /**
       * Clear errors
       *
       * @return {void}
       */
      clearErrors() {
        this.errors = {
          title: [],
          description: [],
          due: []
        };
      },
      /**
       * Reset form values to null
       *
       * @return {void}
       */
      clearForm() {
        this.modal.edit.form = {
          id: null,
          title: null,
          description: null,
          due: null
        };
      },
    }
  }

</script>


<style lang="scss">
  @import 'node_modules/vue-kanban/src/assets/kanban.scss';

  .danger {
    color: #761b18!important;
    background-color: #f9d6d5!important;
  }
</style>

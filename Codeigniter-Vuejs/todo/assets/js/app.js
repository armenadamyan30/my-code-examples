new Vue({
	el: '#app',
	data: {
		url: '/',
		tasks: [],
		search: {task_name: ''},
		formValidate: null,
		typingTimer: {},
		loading: false,
		duplicateTask: null,
		duplicateTaskText: null,
		actionType: 'add',
		addEditText: 'Add',
		actionSuccessMessage: '',
		chooseTask: {},
	},
	created() {
		this.showAll();
	},
	methods: {
		refresh() {
			this.actionType = 'add';
			this.addEditText = 'Add';
			this.actionSuccessMessage = '';
			this.chooseTask = {};
			this.duplicateTask = null;
			this.duplicateTaskText = null;
			this.loading = false;
			this.typingTimer = {};
			this.formValidate = null;
			this.search.task_name = '';
		},
		keyupSearchName(e) {
			this.loading = true;
			this.formValidate = null;
			this.duplicateTask = null;
			this.actionSuccessMessage = '';
			clearTimeout(this.typingTimer);
			this.typingTimer = setTimeout(() => {
				if (this.search.task_name === '') {
					this.formValidate = "Task Name required";
					this.loading = false;
					return false;
				}
				this.searchTask();
			}, 800)
		},
		keydownSearchName() {
			clearTimeout(this.typingTimer);
		},
		showAll() {
			axios.get(this.url + "tasks/showAll").then((response) => {
				if (response.data.tasks !== null) {
					this.tasks = response.data.tasks;
				}
				this.loading = false;
			});
		},
		searchTask() {
			this.duplicateTask = null;
			this.duplicateTaskText = '';
			const data = {};
			data.task_name = this.search.task_name;
			if (this.chooseTask) {
				data.id = this.chooseTask.id;
			}
			const formData = this.formData(data);
			axios.post(this.url + "tasks/searchTask", formData).then((response) => {
				this.duplicateTask = !(!response.data.tasks || response.data.tasks.length === 0);

				if (this.duplicateTask === true) {
					this.duplicateTaskText = 'There are duplicate tasks, please change name';
				} else if (response.data.tasks && response.data.tasks.length > 0) {
					if (this.actionType === 'add') {
						this.duplicateTaskText = 'You can add new task';
					} else if (this.actionType === 'edit') {
						this.duplicateTaskText = 'You can edit the task';
					}
				} else if (this.duplicateTask === false) {
					if (this.actionType === 'add') {
						this.duplicateTaskText = 'You can add this task by pressing on "Add" button';
					} else if (this.actionType === 'edit') {
						this.duplicateTaskText = 'You can update this task by pressing on "Edit" button';
					}
				}

				this.loading = false
			})
		},
		addEditTask() {
			if (this.actionType === 'add') {
				this.addTask();
			} else if (this.actionType === 'edit') {
				this.updateTask();
			}
		},
		addTask() {
			this.loading = true;
			this.actionSuccessMessage = '';
			const formData = this.formData(this.search);
			axios.post(this.url + "tasks/addTask", formData).then((response) => {
				if (response.data.error) {
					if (response.data.msg) {
						if (response.data.msg.name) {
							this.formValidate = response.data.msg.name;
						}
					}
				} else {
					this.addSuccessMessage(response.data.msg, 'add');
					this.showAll();
				}

				this.loading = false;
				this.duplicateTask = null;
			})
		},
		updateTask() {
			this.chooseTask.task_name = this.search.task_name;
			const formData = this.formData(this.chooseTask);
			axios.post(this.url + "tasks/updateTask", formData).then((response) => {
				if (response.data.error) {
					if (response.data.msg) {
						if (response.data.msg.name) {
							this.formValidate = response.data.msg.name;
						}
					}
				} else {
					this.addSuccessMessage(response.data.msg, 'edit');
					this.showAll();
				}
				this.loading = false;
				this.duplicateTask = null;
			})
		},
		deleteTask(task) {
			this.search.task_name = '';
			this.chooseTask = task;
			this.actionType = 'delete';
			this.$refs.removeTaskModalRef.show();

		},
		hideRemoveTaskModal() {
			this.refresh();
			this.$refs.removeTaskModalRef.hide();
		},
		handleOkRemoveTask() {
			const formData = this.formData(this.chooseTask);
			axios.post(this.url + "tasks/deleteTask", formData).then((response) => {
				if (!response.data.error) {
					this.addSuccessMessage(response.data.msg, 'delete');
					this.showAll();

				}
				this.loading = false;
				this.duplicateTask = null;
			})
		},
		formData(obj) {
			const formData = new FormData();
			for (const key in obj) {
				formData.append(key, obj[key]);
			}
			return formData;
		},

		selectEditTask(task) {
			this.refresh();
			this.chooseTask = task;
			this.actionType = 'edit';
			this.addEditText = 'Edit';
			this.search.task_name = this.chooseTask.name;
		},
		addSuccessMessage(msg, actionType) {
			this.actionSuccessMessage = msg;
			setTimeout(() => {
				if (actionType === 'delete') {
					this.$refs.removeTaskModalRef.hide();
				}
				this.refresh();
			}, 1000);
		}
	}
});

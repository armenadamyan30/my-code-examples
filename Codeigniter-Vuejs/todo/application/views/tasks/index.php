<div id="app">
	<div class="container">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				<div class="row mt-5 alert-messages">
					<div class="col-md-10">
						<b-alert show variant="primary" v-if="loading === true">Loading ...</b-alert>
						<b-alert show variant="danger" v-if="loading !== true && duplicateTask === true && duplicateTaskText">{{duplicateTaskText}}</b-alert>
						<b-alert show variant="info" v-if="loading !== true && duplicateTask === false && duplicateTaskText">{{duplicateTaskText}}</b-alert>
						<b-alert show variant="success" v-if="actionSuccessMessage">{{actionSuccessMessage}}</b-alert>
						<b-alert show variant="danger" v-if="formValidate" v-html="formValidate"></b-alert>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-md-10">
						<div class="row">
							<div class="col-md-10">
								<input placeholder="task name..."
									   class="form-control"
									   v-model="search.task_name"
									   @keydown="keydownSearchName"
									   @keyup="keyupSearchName" />
							</div>
							<div class="col-md-2 text-right">
								<b-button variant="primary" size="md" @click="addEditTask" :disabled="duplicateTask === true || duplicateTask === null">{{addEditText}}</b-button>
							</div>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-10">
						<div v-for="task in tasks" class="border px-2 py-2 mb-2">
							<div class="row justify-content-center">
								<div class="col-md-8"><span class="d-block mt-1">{{task.name}}</span></div>
								<div class="col-md-4 text-right">
									<b-button variant="primary" size="sm" @click="selectEditTask(task)" class="rounded-circle">E</b-button>
									<b-button variant="danger" size="sm"  @click="deleteTask(task)" class="rounded-circle">D</b-button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<b-modal ref="removeTaskModalRef" hide-footer centered title="Remove task">
		<div class="d-block text-center">
			Are you sure that you want to remove the <strong v-if="chooseTask">{{chooseTask.name}}</strong> task?
		</div>
		<div class="mt-3 text-center">
			<b-btn variant="default" @click="hideRemoveTaskModal">Close Me</b-btn>
			<b-btn variant="danger" @click="handleOkRemoveTask">Remove</b-btn>
		</div>

	</b-modal>
</div>


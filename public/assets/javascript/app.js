const taskLists = document.querySelectorAll('.task-list')
const backlogTasks = document.querySelector('#backlog .task-list')
const runningTasks = document.querySelector('#running .task-list')
const evaluatingTasks = document.querySelector('#evaluating .task-list')
const inProgressTasks = document.querySelector('#in-progress .task-list')
const liveTasks = document.querySelector('#live .task-list')
const titleInput = document.querySelector('#title')
const descriptionInput = document.querySelector('#description')
const submitButton = document.querySelector('#submit-button')
const errorContainer = document.querySelector('.error-container')
const projectSelect = document.querySelector('#project-select')
let tasks = []


async function fillTasks() {
    const listTasks = await fetch('http://127.0.0.1:8000/api/v1/tasks')
        .then(response => response.json())
    .then(data => data)
    .catch(error => console.log(error));

    listTasks.forEach((task) => {
        tasks.push({
            id: task.id,
            title: task.title,
            description: task.description,
            status_id: task.status_id,
            is_granted: task.is_granted,
            status: task.status
        })
    });


}

fillTasks().then(r => addTasks());


function clearTasks() {
    backlogTasks.innerHTML = ''
    runningTasks.innerHTML = ''
    evaluatingTasks.innerHTML = ''
    inProgressTasks.innerHTML = ''
    liveTasks.innerHTML = ''
}


projectSelect.addEventListener('change', async function () {
    const projectId = projectSelect.value
    const listTasks = await fetch(`http://127.0.0.1:8000/api/v1/tasks/${projectId}`)
        .then(response => response.json())
        .then(data => data)
        .catch(error => console.log(error));

    tasks = []
    clearTasks()
    listTasks.forEach((task) => {
        tasks.push({
            id: task.id,
            title: task.title,
            description: task.description,
            status: task.status,
            is_granted: task.is_granted,
            status_id: task.status_id
        })
    });

    addTasks()
});

taskLists.forEach((taskList) => {
    taskList.addEventListener('dragover', dragOver)
    taskList.addEventListener('drop', dragDrop)
})

function createTask(taskId, title, description, column, statusId, isGranted) {
    const taskCard = document.createElement('div')
    const taskHeader = document.createElement('div')
    const taskTitle = document.createElement('p')
    const taskDescriptionContainer = document.createElement('div')
    const taskDescription = document.createElement('p')
    taskHeader.append(taskTitle)

    if(isGranted){
        const deleteIcon = document.createElement('p')
        deleteIcon.innerHTML = '<img width="15px" src="https://cdn-icons-png.flaticon.com/512/1828/1828665.png" alt="delete-icon">'
        deleteIcon.addEventListener('click', deleteTask)
        taskHeader.append(deleteIcon)
    }


    taskCard.classList.add('task-container')
    taskHeader.classList.add('task-header')
    taskDescriptionContainer.classList.add('task-description-container')

    taskTitle.textContent = title
    taskDescription.textContent = description

    taskCard.setAttribute('draggable', true)
    taskCard.setAttribute('task-id', taskId)
    taskCard.setAttribute('status-id', statusId)

    taskHeader.style.backgroundColor = addColor(column.toLowerCase())

    taskCard.addEventListener('dragstart', dragStart)

    taskDescriptionContainer.append(taskDescription)
    taskCard.append(taskHeader, taskDescriptionContainer)
    if(column.toLowerCase()==='backlog'){
        backlogTasks.append(taskCard)
    }
    if(column.toLowerCase()==='running'){
        runningTasks.append(taskCard)
    }
    if(column.toLowerCase()==='evaluating'){
        evaluatingTasks.append(taskCard)
    }
    if(column.toLowerCase()==='in progress'){
        inProgressTasks.append(taskCard)
    }
    if(column.toLowerCase()==='live'){
        liveTasks.append(taskCard)
    }

}

function addColor(column) {
    let color
    switch (column.toLowerCase()) {
        case 'backlog':
            color = 'rgb(96, 96, 192)'
            break
        case 'running':
            color = 'rgb(83, 156, 174)'
            break
        case 'evaluating':
            color = 'rgb(224, 165, 116)'
            break
        case 'in progress':
            color = 'rgb(222, 208, 130)'
            break
        case 'live':
            color = 'rgb(81, 23, 165)'
            break
        default:
            color = 'rgb(232, 232, 232)'
    }
    return color
}

function addTasks() {
    // advanced: you can pass through the whole task object if you wish
    tasks.forEach((task) => createTask(task.id, task.title, task.description, task.status, task.status_id, task.is_granted))
}

addTasks()

let elementBeingDragged

function dragStart() {
    elementBeingDragged = this
}

function dragOver(e) {
    e.preventDefault()
}

function dragDrop() {
    let columnId = this.parentNode.id
    if(columnId === 'in-progress'){
        columnId = 'in progress';
    }

    elementBeingDragged.firstChild.style.backgroundColor = addColor(columnId)
    updateTaskStatus(elementBeingDragged.getAttribute('task-id'), this.getAttribute('status-id'))
    console.log(this.getAttribute('status-id'))
    this.append(elementBeingDragged)
}

async function updateTaskStatus(taskId, taskStatus) {
    await fetch(`http://127.0.0.1:8000/api/v1/tasks/${taskId}/status/${taskStatus}`);
    console.log(taskStatus)
}

async function deleteTaskById(taskId) {
    await fetch(`http://127.0.0.1:8000/api/v1/tasks/delete/${taskId}`);
}

function deleteTask() {
    const headerTitle = this.parentNode.firstChild.textContent

    const filteredTasks = tasks.filter((task) => {
        return task.title === headerTitle
    })



    tasks = tasks.filter((task) => {
        return task !== filteredTasks[0]
    })

    deleteTaskById(filteredTasks[0].id).then(r => console.log(r))


    this.parentNode.parentNode.remove()

}

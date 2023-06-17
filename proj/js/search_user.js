const csrf = document.getElementById('csrf').value;
const searchUserAdminInput = document.querySelector('#search-user-admin');
const searchUserAgentInput = document.querySelector('#search-user-agent');
let currentSearchAdminString = '';
let currentSearchAgentString = '';
let selectedUsersAdmin = [];
let selectedUsersAgent = [];
let selectedDepartments = [];
let selectedDepartmentsIds = [];

window.addEventListener('load', function() {
  updateDepartmentList();
});


document.getElementById('add-department-button').addEventListener('click', addDepartment);
document.getElementById('upgrade-users-to-admin-button').addEventListener('click', upgradeSelectedUsersToAdmin);
document.getElementById('upgrade-users-to-agent-button').addEventListener('click', assignSelectedUsersToDepartments);
const deleteButtons = document.getElementsByClassName('delete-suggested-faqs');
const submitButtons = document.getElementsByClassName('submit-suggested-faqs');

const tableHeaders = document.querySelectorAll('#agent-stats th');

tableHeaders.forEach(header => {
  header.addEventListener('click', () => {
    tableHeaders.forEach(h => h.querySelector('.sort-icon').style.display = 'none');
    header.querySelector('.sort-icon').style.display = 'inline';
    const sortBy = header.dataset.sortBy;
    console.log(sortBy);
    updateAgentStats(sortBy);
  });
});

for (let i = 0; i < deleteButtons.length; i++) {
  deleteButtons[i].addEventListener('click', deleteFAQ);
}

for (let i = 0; i < submitButtons.length; i++) {
  submitButtons[i].addEventListener('click', updateFAQS);
}

const sectionTitles = document.querySelectorAll('.section-title');

sectionTitles.forEach(sectionTitle => {
  const section = sectionTitle.nextElementSibling;
  section.style.display = 'none'; 

  sectionTitle.addEventListener('click', () => {
    section.style.display = (section.style.display === 'block') ? 'none' : 'block';
  });
});


if (searchUserAdminInput) {
  searchUserAdminInput.addEventListener('input', function() {
    currentSearchAdminString = this.value;
    updateUserAdminSearch();
  });
}

if (searchUserAgentInput) {
  searchUserAgentInput.addEventListener('input', function() {
    currentSearchAgentString = this.value;
    updateUserAgentSearch();
  });
}

async function updateUserAdminSearch() {

  const searchUserAdminInput = document.querySelector('#search-user-admin');
  const currentSearchAdminString = searchUserAdminInput.value.trim();

  if (currentSearchAdminString === '') {
    displayUsers([], true);
    return;
  }

  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_users.php?' + encodeForAjax({action : 'getUsersByUsername', username : currentSearchAdminString, isAdminSearch : true, csrf : csrf}), true);  
  request.onload = function() {
    if (request.status === 200) {
      const users = JSON.parse(request.responseText);
      displayUsers(users, true);
    } else {
      console.log('Request failed. Returned status of ' + request.status);
    }
  };
  request.send();
}

async function updateUserAgentSearch() {
  const searchUserAgentInput = document.querySelector('#search-user-agent');
  const currentSearchAgentString = searchUserAgentInput.value.trim();

  if (currentSearchAgentString === '') {
    displayUsers([], false);
    return;
  }
  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_users.php?' + encodeForAjax({action : 'getUsersByUsername', username : currentSearchAgentString, isAdminSearch : false, csrf : csrf }), true);

  request.onload = function() {
    if (request.status === 200) {
      const users = JSON.parse(request.responseText);
      displayUsers(users, false);
    } else {
      console.log('Request failed. Returned status of ' + request.status);
    }
  };

  request.send();
}

async function updateDepartmentList() {
  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_departments.php?' + encodeForAjax({action : 'getAllDepartments'}),true);

  request.onload = function() {
    if (request.status === 200) {
      const departments = JSON.parse(request.responseText);
      console.log(departments);
      displayDepartments(departments);
    } else {
      console.log('Request failed. Returned status of ' + request.status);
    }
  };

  request.send();
}

function displayUsers(users, isAdminSearch) {
  let userList;
  if (!isAdminSearch) {
    userList = document.querySelector('#user-list-agent');
  } else {
    userList = document.querySelector('#user-list-admin');
  }
  
  userList.innerHTML = '';

  for (const user of users) {
    const listItem = document.createElement('li');

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.addEventListener('change', function() {
      
      if (isAdminSearch){
        toggleUserAdminSelection(user.username);
        updateSelectedUsersAdminText();
      }
      else {
        toggleUserAgentSelection(user.username);
        updateSelectedUsersAgentText();
      }
    });

    if (isAdminSearch){
      if (selectedUsersAdmin.includes(user.username)) {
        checkbox.checked = true;
      }
    } else {
      if (selectedUsersAgent.includes(user.username)) {
        checkbox.checked = true;
      }
    }

    const label = document.createElement('label');
    label.textContent = user.username;
    label.addEventListener('click', function() {
      checkbox.checked = !checkbox.checked;
      if (isAdminSearch){
        toggleUserAdminSelection(user.username);
        updateSelectedUsersAdminText();  
      }
      else {
        toggleUserAgentSelection(user.username);
        updateSelectedUsersAgentText();   
      }
    });

    label.appendChild(checkbox);

    listItem.appendChild(checkbox);
    listItem.appendChild(label);

    userList.appendChild(listItem);
  }
}

function displayDepartments(departments) {
  const departmentList = document.querySelector('#department-list');
  departmentList.innerHTML = '';

  for (const department of departments) {
    const listItem = document.createElement('li');

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.id = department.name;

    checkbox.addEventListener('change', function() {
      toggleDepartmentSelection(department);
      updateSelectedDepartmentsText();
    });

    if (selectedDepartments.includes(department.name)) {
      checkbox.checked = true;
    }

    const label = document.createElement('label');
    label.textContent = department.name;
    label.htmlFor = department.name;

    label.addEventListener('click', function() {
      checkbox.checked = !checkbox.checked;
      toggleDepartmentSelection(department);
      updateSelectedDepartmentsText();
    });

    label.appendChild(checkbox);

    listItem.appendChild(checkbox);
    listItem.appendChild(label);

    departmentList.appendChild(listItem);
  }
}


function toggleUserAdminSelection(username) {
  const index = selectedUsersAdmin.indexOf(username);
  if (index === -1) {
    selectedUsersAdmin.push(username);
  } else {
    selectedUsersAdmin.splice(index, 1);
  }
}

function toggleUserAgentSelection(username) {
  const index = selectedUsersAgent.indexOf(username);
  if (index === -1) {
    selectedUsersAgent.push(username);
  } else {
    selectedUsersAgent.splice(index, 1);
  }
}

function toggleDepartmentSelection(department) {
  const index = selectedDepartments.indexOf(department.name);
  if (index === -1) {
    selectedDepartments.push(department.name);
    selectedDepartmentsIds.push(department.department_id);
    
  } else {
    selectedDepartments.splice(index, 1);
    selectedDepartmentsIds.splice(index, 1);
  }
  console.log(selectedDepartmentsIds);
}

function updateSelectedDepartmentsText() {
  const selectedDepartmentsText = document.querySelector('#selected-departments-text');
  const selectedDepartmentsDisplay = selectedDepartments.map(department => {
    return `<span class="selected-department">${department}</span>`;
  }).join(', ');
  selectedDepartmentsText.innerHTML = `Selected Departments: ${selectedDepartmentsDisplay}`;
}

function updateSelectedUsersAdminText() {
  const selectedUsersAdminText = document.querySelector('#selected-users-text-admin');
  const selectedUsersDisplay = selectedUsersAdmin.map(user => {
    return `<span class="selected-user">${user}</span>`;
  }).join(', ');
  selectedUsersAdminText.innerHTML = `Selected Users: ${selectedUsersDisplay}`;
}
function updateSelectedUsersAgentText() {
  const selectedUsersAgentText = document.querySelector('#selected-users-text-agent');
  const selectedUsersDisplay = selectedUsersAgent.map(user => {
    return `<span class="selected-user">${user}</span>`;
  }).join(', ');
  selectedUsersAgentText.innerHTML = `Selected Users: ${selectedUsersDisplay}`;
}

function upgradeSelectedUsersToAdmin() {
  for (const username of selectedUsersAdmin) {
    const request = new XMLHttpRequest();
    request.open('POST', '../api/api_users.php?'+encodeForAjax({ action : 'updateUserRole', csrf : csrf}), true);
    request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    

    const data = new FormData();
    data.append('username', username);
    data.append('role', 'admin');

    request.onload = function() {
      if (request.status === 200) {
        console.log(`User ${username} has been upgraded to Admin.`);
      } else {
        console.log(`Failed to upgrade user ${username} to Admin. Error: ${request.responseText}`);
      }
    };

    request.send(encodeForAjax({  username : username, role :'admin'}));
  }
  resetFiltersAdmin();
  console.log('Upgrade the following users to Admin:', selectedUsersAdmin);
}

function assignSelectedUsersToDepartments() {
  for (const username of selectedUsersAgent) {
    const request = new XMLHttpRequest();
    request.open('GET', '../api/api_users.php?action=assignUserToDepartment' +
    '&username=' + username + '&departments=' + selectedDepartmentsIds +
    '&csrf=' + csrf);

    request.onload = function() {
      if (request.status === 200) {
        console.log(`User ${username} has been assigned to the following departments: ${selectedDepartments.join(', ')}.`);
      } else {
        console.log(`Failed to assign user ${username} to departments. Error: ${request.responseText}`);
      }
    };

    request.send();
  }
  console.log('Assign the following users to departments:', selectedUsersAgent, selectedDepartments);
  resetFiltersAgent();
  updateAgentStats();
}

function addDepartment() {
  const departmentNameInput = document.getElementById('department-name');
  const departmentName = departmentNameInput.value;
  console.log(departmentName);
  

  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_users.php?' + encodeForAjax({action : 'addDepartment',departmentName : departmentName, csrf : csrf }), true);

  request.onload = function() {
    let alertString = '';

    if (request.status === 200) {
      const response = JSON.parse(request.responseText);
      console.log('Department added successfully');
      departmentNameInput.value = '';
      alertString = response.addDepartmentStatus;
    } else {
      console.log('Failed to add department');
      alertString = 'Failed to add department';
    }
    alert(alertString);
  };

  request.send();
  updateDepartmentList();
}

function resetFiltersAgent() {
  searchUserAgentInput.value = '';

  selectedUsersAgent = [];

  selectedDepartments = [];
  selectedDepartmentsIds = [];

  updateSelectedUsersAgentText();
  updateSelectedDepartmentsText();

  const agentCheckboxes = document.querySelectorAll('#user-list-agent input[type="checkbox"]');
  agentCheckboxes.forEach(checkbox => {
    checkbox.checked = false;
  });

  const departmentCheckboxes = document.querySelectorAll('#department-list input[type="checkbox"]');
  departmentCheckboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
}

function resetFiltersAdmin() {
  searchUserAdminInput.value = '';

  selectedUsersAdmin = [];

  updateSelectedUsersAdminText();

  const adminCheckboxes = document.querySelectorAll('#user-list-admin input[type="checkbox"]');
  adminCheckboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
}

const agentStatsSectionTitle = document.getElementById('agent-stats-title');

agentStatsSectionTitle.addEventListener('click', function() {
  updateAgentStats();
});


async function updateAgentStats(sortBy) {
  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_users.php?' + encodeForAjax({action : 'getAgentStats',sortBy : sortBy, csrf : csrf}));

  request.onload = function() {
    if (request.status === 200) {
      const agentStats = JSON.parse(request.responseText);
      console.log(agentStats)
      displayAgentStats(agentStats);
    } else {
      console.log('Request failed. Returned status of ' + request.status);
    }
  };
  request.send();
}

function displayAgentStats(agentStats) {
  const agentStatsBody = document.querySelector('#agent-stats-body');
  agentStatsBody.innerHTML = '';

  agentStats.forEach(agent => {
    const agentRow = document.createElement('tr');

    const agentCell = document.createElement('td');

    const agentImage = document.createElement('img');
    agentImage.classList.add('agent-image');
    if (agent.profile_picture === "") {
      agentImage.src = "../images/default-user-image.png";
    } else {
      agentImage.src = agent.profile_picture;
    }
    agentCell.appendChild(agentImage);

    const agentName = document.createElement('span');
    agentName.classList.add('agent-name');
    agentName.textContent = agent.agent_username;
    agentCell.appendChild(agentName);

    agentRow.appendChild(agentCell);

    const closedTicketsCell = document.createElement('td');
    closedTicketsCell.textContent = agent.num_closed_tickets;
    agentRow.appendChild(closedTicketsCell);

    const ongoingTicketsCell = document.createElement('td');
    ongoingTicketsCell.textContent = agent.num_ongoing_tickets;
    agentRow.appendChild(ongoingTicketsCell);

    agentStatsBody.appendChild(agentRow);
  });
}

function updateFAQS() {
    const questionId = event.target.getAttribute('data-question-id');
    const faq=document.querySelector('#title'+questionId);
    const des=document.querySelector('.description'+questionId);
    const txt=des.querySelector('textarea');
    const request = new XMLHttpRequest();
    request.open('GET', '../api/api_users.php?' + encodeForAjax({suggestedfaq : questionId , answer :  txt.value, action : 'updateFAQS', csrf : csrf  }), true);

    request.onload = function() {
      if (request.status === 200) {
        console.log('Faq has been submited');
      } else {
        console.log('Failed to submit FAQ ');
      }
    };

    request.send();
    const request2 = new XMLHttpRequest();
    request2.open('GET', '../api/api_users.php?' + encodeForAjax({suggestedfaq :  questionId, action : 'deleteFAQS', csrf : csrf}), true);

     request2.onload = function() {
      if (request2.status === 200) {
        console.log('FAQ has been deleted from suggested FAQS');
      } else {
        console.log('Failed to delete FAQ from suggested FAQS ');
      }
    };
    request2.send();

  faq.style.display='none';
  des.style.display='none';  
}



async function deleteFAQ(){
  const questionId = event.target.getAttribute('data-question-id');
  const faq=document.querySelector('#title'+questionId);
  const des=document.querySelector('.description'+questionId);
  const txt=des.querySelector('textarea');
  const request = new XMLHttpRequest();
  request.open('GET', '../api/api_users.php?' + encodeForAjax({suggestedfaq : questionId , action : 'deleteFAQS', csrf : csrf }) ,true);

  request.onload = function() {
    if (request.status === 200) {
      console.log('FAQ has been deleted from suggested FAQS');
    } else {
      console.log('Failed to delete FAQ from suggested FAQS ');
    }
  };
  request.send();

faq.style.display='none';
des.style.display='none';  
}
  
function encodeForAjax(data) {
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&')
}

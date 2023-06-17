  window.addEventListener('load', updateTickets);

  const sortSelect = document.querySelector('#sort-select');
  const statusCheckboxes = document.querySelectorAll('.status-filter');
  const priorityCheckboxes = document.querySelectorAll('.priority-filter');
  const departmentCheckboxes = document.querySelectorAll('.department-filter');
  const assignedCheckboxes = document.querySelectorAll('.assigned-filter');

  let selectedValue = sortSelect.value;


  sortSelect.addEventListener('change', updateTickets);

  statusCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', updateTickets);
  });

  priorityCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', updateTickets);
  });

  departmentCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', updateTickets);
  });

  assignedCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', updateTickets);
  });

  function getStatusFilter(){
    const res = [];
    for (const checkbox of statusCheckboxes){
      if (checkbox.checked){
        res.push(checkbox.value);
      }
    }
    return res.join(',');
  }


  function getPriorityFilter(){
    const res = [];
    for (const checkbox of priorityCheckboxes){
      if (checkbox.checked){
        res.push(checkbox.value);
      }
    }
    return res.join(',');
  }

  function getDepartmentsFilter(){
    const res = [];
    for (const checkbox of departmentCheckboxes){
      if (checkbox.checked){
        res.push(checkbox.value);
      }
    }
    return res.join(',');
  }

  function getAssignedFilter() {
    const res = [];
    for (const checkbox of assignedCheckboxes) {
      if (checkbox.checked) {
        res.push(checkbox.value);
      }
    }
    return res.join(',');
  }


  async function updateTickets(){

    selectedValue = sortSelect.value;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../api/api_tickets.php?action=getTicketsFromAgentDepartments' +
    '&status=' + getStatusFilter() + '&priority=' + getPriorityFilter() + 
    '&departments=' + getDepartmentsFilter() +
    '&assigned=' + getAssignedFilter()
    );

    xhr.onload = function() {
    if (xhr.status === 200) {
      const result = JSON.parse(xhr.responseText);
      console.log("RESULT:");
      console.log(result);

      const ticketData = result.map(function(data) {
        return {
          ticket: data.ticket,
          department_name: data.department_name,
          user_name: data.user_name
        };
      });
      
      if (selectedValue === 'date-asc') {
        ticketData.sort((a, b) => {
          const dateA = convertDateString(a.ticket.created_at);
          const dateB = convertDateString(b.ticket.created_at);
          const dateComparison = dateA - dateB;
          if (dateComparison === 0) {
            return a.ticket.ticket_id - b.ticket.ticket_id;
          }
          return dateComparison;
        });
      } else if (selectedValue === 'date-desc') {
        ticketData.sort((a, b) => {
          const dateA = convertDateString(a.ticket.created_at);
          const dateB = convertDateString(b.ticket.created_at);
          const dateComparison = dateB - dateA;
          if (dateComparison === 0) {
            return b.ticket.ticket_id - a.ticket.ticket_id;
          }
          return dateComparison;
        });
      }
      
      const ticketsContainer = document.getElementById("department_tickets");

      ticketsContainer.innerHTML = '';

      ticketData.forEach(function(data) {
        const ticketLink = document.createElement('a');
        ticketLink.href = '../pages/ticket.php?ticket_id=' + data.ticket.ticket_id;

        const ticketContainer = document.createElement('div');
        ticketContainer.className = 'ticket';

        const titleElement = document.createElement('h3');
        titleElement.innerText = data.ticket.title;

        const descriptionElement = document.createElement('div');
        descriptionElement.className = 'description';
        descriptionElement.innerText = data.ticket.description;

        const authorElement = document.createElement('div');
        authorElement.className = 'author';
        authorElement.innerText = " " + data.user_name;

        const departmentElement = document.createElement('div');
        departmentElement.className = 'department';
        departmentElement.innerText = data.department_name;

        const dateElement = document.createElement('div');
        dateElement.className = 'date';
        dateElement.innerText = " " + data.ticket.created_at;

        ticketContainer.appendChild(titleElement);
        ticketContainer.appendChild(descriptionElement);
        ticketContainer.appendChild(authorElement);
        ticketContainer.appendChild(departmentElement);
        ticketContainer.appendChild(dateElement);

        ticketLink.appendChild(ticketContainer);

        ticketsContainer.appendChild(ticketLink);
      });
    }
    else {
      console.log('Request failed. Returned status of ' + xhr.status);
    }
  };
  xhr.send();
}

function convertDateString(dateString) {
  const [day, month, year] = dateString.split('-');
  const isoDateString = `${year}-${month}-${day}`;
  return new Date(isoDateString);
}
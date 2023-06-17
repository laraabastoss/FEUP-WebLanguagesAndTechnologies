const entityMap = {
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': '&quot;',
  "'": '&#39;',
  "/": '&#x2F;'
};
function escapeHtml(string) {
  return String(string).replace(/[&<>"'\/]/g, function (s) {
    return entityMap[s];
  });
}

db=fetch('/../database/connection.php?action=getDatabaseConnection')
const searchTickets = document.querySelector('#search')
if (searchTickets) {
  searchTickets.addEventListener('focus', async function() {

    const hiddenElements = document.querySelectorAll('.frequently_asked , .carousel, .tickets, .department_name');
    for (var i = 0; i < hiddenElements.length; i++) {

        hiddenElements[i].style.display = 'none';

    }
  });
  
  
  searchTickets.addEventListener('input', async function() {
    const section = document.querySelector('#results')
    section.innerHTML = ''
    const urlParams = new URLSearchParams(window.location.search);
    let department = urlParams.get(escapeHtml('department_id'));
    if(department==null) department=0;
    response=await fetch('/../api/api_search.php?search=' + this.value +'&department=' + department)
    tickets=await response.json()
    if(department!==0){
      res=await fetch('/../api/api_get_department.php?department=' + department)
      name_=await res.json()
      n= '<h1>' + name_+'</h1>'
      section.innerHTML+=n;
    }
    for (const ticket of tickets) {
      response_=await fetch('/../api/api_user_name.php?user=' + ticket.user_id)
      user=await response_.json()
       const html =  '<a href="../pages/ticket.php?ticket_id=' + ticket.ticket_id+ '">' +
       '<div class="ticket">'+ 
            '<h3>'+ ticket.title +'</h3>'+  
            '<div class="description">' +ticket.description+ '</div>'+
            '<div class="author">' + user.username+'</div>'+
            '<div class="date">' + ticket.created_at + '</div>'+
            '</div>' + '</a>';
      section.innerHTML+=html
    }});
  } 

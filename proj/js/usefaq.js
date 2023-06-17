const faq = document.querySelectorAll('.faq');
const usefaqbutton = document.querySelector('.usefaqbutton');



faq.forEach((faqTitle, index) => {
  faqTitle.onclick = function() {
    console.log(faqTitle);
    const faqcontent=faqTitle.querySelectorAll('span.answer');
    goToComment(faqcontent[0].textContent.trim());
  };
});

function goToComment(description) {
  const commentForm = document.getElementById("addcommentform");
  const input = document.getElementById("newComment");
  input.value=description + "(answered using faq)";
  commentForm.submit();
}

if (usefaqbutton!=null){
  usefaqbutton.addEventListener('click', async function() {
    const dropdown = document.getElementById("dropdown");
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    event.preventDefault();
  })
}


  
 
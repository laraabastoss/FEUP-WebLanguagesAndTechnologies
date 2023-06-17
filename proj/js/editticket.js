const questions = document.querySelectorAll('.fa-pencil');

questions.forEach(question => {
  const answer = question.nextElementSibling;
  answer.style.display = 'none'; 

  question.addEventListener('click', () => {
    answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
  });
});




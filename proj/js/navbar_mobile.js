const hamburger = document.getElementById('hamburger');

hamburger.addEventListener('change', function() {
  const body = document.body;
  
  if (hamburger.checked) {
    body.style.overflow = 'hidden';
  } else {
    body.style.overflow = 'auto';
  }
  
  document.addEventListener('click', function(event) {
    const sideSection = document.getElementById('sideSection');
    
    if (!sideSection.contains(event.target) && event.target !== hamburger) {
      hamburger.click();
    }
  });
});

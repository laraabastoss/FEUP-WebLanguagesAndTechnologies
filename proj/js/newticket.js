document.addEventListener('DOMContentLoaded', function() {
    const popupDiv = document.getElementById('popup');
    const popupValue = popupDiv.getAttribute('data-popup');
  
    if (popupValue === 'true') {
      popupDiv.classList.add('open-popup');
    }
  });
  
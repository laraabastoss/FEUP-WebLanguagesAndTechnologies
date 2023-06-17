const ul = document.querySelector('ul');
const input = ul.querySelector('input');
const hashtagInput = document.querySelector('#hashtags');
const hashtagList = document.querySelector('#hashtagList');
;
let tags = [];

function createTag() {
  ul.querySelectorAll('li').forEach(li => li.remove());
  tags.forEach(tag => {
    let liTag = `<li>${tag}<p class="remove-tag">x</p></li>`;
    ul.insertAdjacentHTML("afterbegin", liTag);
  });
  document.getElementById('hashtags-store').value = JSON.stringify(tags);
}
function addTag(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    e.stopPropagation();
    let tag = e.target.value.replace(/\s+/g, ' ');
    if (tag.length > 1 && !tags.includes(tag)) {
      tag.split(',').forEach(tag => {
        tags.push(tag);
        createTag();
      });
    }
    e.target.value = "";
  }
}


ul.addEventListener('click', function(event) {
  if (event.target.classList.contains('remove-tag')) {
    const tag = event.target.parentNode.textContent.trim();
    const index = tags.indexOf(tag);
    tags.splice(index, 1);
    event.target.parentNode.remove();
    document.getElementById('hashtags-store').value = JSON.stringify(tags);

  }
});


function remove(element, tag) {
  let index = tags.indexOf(tag);
  tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
  element.parentElement.remove();
  document.getElementById('hashtags-store').value = JSON.stringify(tags);

}

document.addEventListener('keydown', function(event) {
  if (event.keyCode === 13 && !event.target.classList.contains('hashtags-input')) {
    event.preventDefault();
  }
});





hashtagInput.addEventListener('input', (event) => {
  const searchTerm = event.target.value;
  fetch(`/../api/api_gethashtags.php?term=${searchTerm}`)
    .then(response => response.json())
    .then(data => {
      hashtagList.innerHTML = '';
      data.forEach(hashtag => {
        const option = document.createElement('option');
        option.value = hashtag.hashtag;
        hashtagList.appendChild(option);
      });
    })
    .catch(error => console.error(error));
});






input.addEventListener("keydown", addTag);


 
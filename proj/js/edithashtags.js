const newHashtagInput = document.querySelector('#hashtagsinput');

newHashtagInput.addEventListener('keydown', function(event) {

  const hashtagElement = this.value;
  const ticketId = this.dataset.ticketId;
  const currUser = this.dataset.currUser;
  addHashtag(event,ticketId,currUser);
});

const removeButtons = document.getElementsByClassName("remove-hashtag");



for (let i = 0; i < removeButtons.length; i++) {
  removeButtons[i].addEventListener('click', function(event) {
    const hashtagElement = this.dataset.hashtag;
    const ticketId = this.dataset.ticketId;
    const currUser = this.dataset.currUser;
    removeExistingHashtag(hashtagElement, ticketId, currUser);
  });
}

function remove(element, tag) {
    let index = tags.indexOf(tag);
    tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
    element.parentElement.remove();
    document.getElementById('hashtags-store').value = JSON.stringify(tags);
  
  }

function removeExistingHashtag(hashtagElement, ticketId,curr_user) {
    const hashtagList = document.querySelector('#hashtagList');
    const listItems = hashtagList.getElementsByTagName('li');
  
    for (let i = 0; i < listItems.length; i++) {
      const listItem = listItems[i];
      const hashtagName = listItem.textContent.trim().slice(0,-3);
      if (hashtagName === hashtagElement) {
        listItem.remove();
        break;
      }
    }
    const inputField = document.getElementById("hashtagsinput");
    const inputValue = inputField.value.replace(hashtagElement, "");
    inputField.value = inputValue;
  
    const request = new XMLHttpRequest();
    request.open("GET", "../actions/action_removehashtag.php?" + encodeForAjax({ticket_id : ticketId, hashtag : hashtagElement, curruser : curr_user}), true);
    request.send();
  }
  
  
  
  
  function addHashtag(event, ticket_id, user_id) {
    if (event.keyCode === 13) { 
      event.preventDefault(); 
      const hashtagInput = document.getElementById('hashtagsinput');
      const hashtagList = document.getElementById('hashtagList');
      const hashtag = hashtagInput.value;
      
      if (hashtag.trim() !== '') {
        const ticketId = ticket_id;
        const userId = user_id;
  
        const request = new XMLHttpRequest();
        request.open('POST', "../actions/action_addhashtag.php?", true);
        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        request.onreadystatechange = function() {
          if (request.readyState === 4 && request.status === 200) {
            const li = document.createElement('li');
            li.className = 'one_hashtag';
            li.innerText = hashtag;
            const removeButton = document.createElement('button');
            removeButton.className = 'remove-hashtag';
            removeButton.innerText = 'x';
            removeButton.onclick =  function() {
              removeExistingHashtag(hashtag, ticketId, userId);
              li.remove();
            };
            li.appendChild(removeButton);
      
            hashtagList.prepend(li);
            hashtagList.insertBefore(li, hashtagList.firstChild);

            hashtagInput.value = '';
          }
        };
        request.send(encodeForAjax({ticket_id : ticketId , hashtag : hashtag}));
      }
    }
  }

  function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}
  
  
  
  
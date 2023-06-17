const controls = document.querySelectorAll('.button');

let currentItem = 0;
const items = document.querySelectorAll('.department');
const maxItems = items.length;
let flag=true;
controls.forEach(control =>{
    control.addEventListener('click', ()=>{
        const isLeft = control.classList.contains('prev');
        if (isLeft){
            currentItem -=1;
            if (currentItem<0){
                currentItem = 0;
                flag=false;
            }
            else{
                flag=true;
            }
        }
        else{
            currentItem +=1;
            if (currentItem>=maxItems-5){
                currentItem = maxItems-5;
                flag=false;
            }
            else{
                flag=true;
            }
            
        } 
        items.forEach(item => {
            item.classList.remove('current-department');
          
          });    
        let firstItem = currentItem;
        let lastItem = (currentItem + 4) % maxItems;
        let tempItem = currentItem;
        for (let i=0;i<5;i++){
            if (tempItem >= maxItems) {
                tempItem = 0;
            }
            items[tempItem].classList.add('current-department');
            tempItem += 1;
        }   
        console.log("clicked", isLeft, currentItem, maxItems,flag);
    })
});

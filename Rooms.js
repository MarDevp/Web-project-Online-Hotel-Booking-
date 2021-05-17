var index=1;
var room_id;
var hide=document.body.addEventListener('click',hide);
function slide(id,n){
var carousel = document.getElementsByClassName("room"+id);
    index +=n;
    Array.from(carousel).forEach(element =>
        element.classList.remove("active"));
        if(index > carousel.length) { index=1;}
        if(index < 1) { index=carousel.length; }
        carousel[index-1].classList.add("active");
        console.log(id+" carousel "+carousel);
}

function book(e){
    document.getElementById("show-book").style.display="block";
    document.getElementById('bground').classList.add("bground");
    document.getElementById('date1').value= sessionStorage.getItem("date1");
    document.getElementById('date2').value= sessionStorage.getItem("date2");
     room_id=e.id;
}


var form = document.getElementById("form");
      form.addEventListener("submit",submit);
function submit(e){
    e.preventDefault();
    var FM= new FormData(form);
    FM.append("submit","Submit");
    FM.append("room_id",room_id);
    var xhr =new XMLHttpRequest();
    xhr.open('POST','book.php',true);
    xhr.onload=function(){
        if(this.responseText=='wrong date'){
            document.getElementById('invalid_date').innerText='THIS ROOM IS BOOKED TRY ANOTHER DATE';
        }else{
            document.getElementById('invalid_date').remove();
            e.submitter.value=this.responseText;   
            setTimeout(function(){ location.reload(); }, 2000);
        }
        
    }
    xhr.send(FM);
}
function hide(e){
    var outside=true;
    Array.from(e.composedPath()).forEach(element =>  {
       if(element.id == "show-book"){ 
           outside=false;
           return;
            } 
        });
    if(outside && e.target.tagName != "BUTTON"){
    document.getElementById("show-book").style.display="none";
    document.getElementById('bground').classList.remove("bground");
    }
}
var date1 = document.getElementById('checkin');
var date2 = document.getElementById('checkout');

function search_rooms() {
    if (typeof (Storage) !== "undefined") {
        // Store
        sessionStorage.setItem("date1", date1.value);
        sessionStorage.setItem("date2", date2.value);
    }
}

var date1 = document.getElementById('date1');
var date2 = document.getElementById('date2');

function search_rooms() {
    if (typeof (Storage) !== "undefined") {
        // Store
        sessionStorage.setItem("date1", date1.value);
        sessionStorage.setItem("date2", date2.value);
    }
}

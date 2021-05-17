<?php include "header.html"; ?>

<?php
// connecting to database
$conn = mysqli_connect('localhost', 'root', '2001', 'hotel');
if (!$conn) {
  echo "connection error " . mysqli_connect_error();
}
// getting available rooms from database 
$result = "SELECT id FROM available_rooms WHERE is_available=1";
$result = mysqli_query($conn, $result);
if ($result) {
  $rooms_left = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
}
// getting non available rooms
$result = "SELECT id FROM available_rooms WHERE is_available=0";
$result = mysqli_query($conn, $result);
if ($result) {
  $booked_rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
}

if (isset($_POST['search'])) {
  // dates entered by the user 
  $date1 = mysqli_real_escape_string($conn, $_POST['date1']);
  $date2 = mysqli_real_escape_string($conn, $_POST['date2']);
  // checking if a room will be free during this date
  foreach ($booked_rooms as $key) {
    $id = $key['id'];
    $sql = "SELECT StartDate,EndDate,room_id FROM rooms WHERE room_id=$id ORDER BY StartDate";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
      if (current($rooms)['StartDate'] > $date2 || $date1 > end($rooms)['EndDate']) {
        array_push($rooms_left, array("id" => current($rooms)['room_id']));
      }
      for ($i = 0; $i < count($rooms) - 1; $i++) {
        if ($rooms[$i + 1]['StartDate'] > $date2 && $date1 > $rooms[$i]['EndDate']) {
          array_push($rooms_left, array("id" => $rooms[$i]['room_id']));
          break;
        }
      }
    }
  }
  print_r($rooms_left);
  mysqli_free_result($result);
  mysqli_close($conn);
} ?>


<?php foreach ($rooms_left as $room) {
    //displaying the rooms
  if (file_exists("images/firstView_room" . $room['id'] . ".jpg")) { ?>
    <div id="carousel<?php echo $room['id'] ?>" class="container my-5 position-relative">
      <div id="carouselExampleInterval" class="carousel slide " data-bs-ride="carousel">
        <div class="carousel-inner items">
          <div class="carousel-item room<?php echo $room['id'] ?> active ">
            <img src="images/firstView_room<?php echo $room['id'] ?>.jpg" class="room-image">
          </div>
          <div class="carousel-item room<?php echo $room['id'] ?> ">
            <img src="images/secondView_room<?php echo $room['id'] ?>.jpg" class="room-image">
          </div>
          <div class="carousel-item room<?php echo $room['id'] ?> ">
            <img src="images/thirdView_room<?php echo $room['id'] ?>.jpg" class="room-image">
          </div>
        </div>
        <button class="carousel-control-prev " onclick="slide(<?php echo $room['id'] ?>,-1)" type="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" onclick="slide(<?php echo $room['id'] ?>,1)" type="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <div class="row room-info font position-absolute bottom-0 start-0 text-white ">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
          <h1>Luxrious room</h1>
          <p>starting from <span class="fw-bold">500$</span></p>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 align-self-end">
          <button id=<?php echo $room['id'] ?> class="btn btn-light px-5 font fs-3 " onclick="book(this)">Book</button>
        </div>
      </div>
    </div>
<?php }
}  ?>
<?php include "footer.html"; ?>
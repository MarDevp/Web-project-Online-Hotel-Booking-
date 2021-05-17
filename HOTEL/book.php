<?php
// connecting to the database
$conn = mysqli_connect('localhost', 'root', '2001', 'hotel');
if (!$conn) {
    echo "connection error " . mysqli_connect_error();
}

if (isset($_POST['submit'])) {
            $date1 = mysqli_real_escape_string($conn, $_POST['date1']);
            $date2 = mysqli_real_escape_string($conn, $_POST['date2']);
            $FullName = mysqli_real_escape_string($conn, $_POST['Full_Name']);
            $Email = mysqli_real_escape_string($conn, $_POST['Email']);
            $cardNumber = mysqli_real_escape_string($conn, $_POST['cardNumber']);
            $ExpirationDate = mysqli_real_escape_string($conn, $_POST['ExpirationDate']);
            $securitycode = mysqli_real_escape_string($conn, $_POST['securitycode']);
            $room_id = mysqli_real_escape_string($conn, $_POST['room_id']);
            $valid_date=false;
            // getting available rooms from database
            $result = "SELECT id FROM available_rooms WHERE is_available=0";
            $result = mysqli_query($conn, $result);
            $booked_rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            //check if the date entered is valid
            if(in_array($room_id,$booked_rooms)){
                $valid_date=true;
            }else{
                $sql = "SELECT StartDate,EndDate,room_id FROM rooms WHERE room_id=$room_id ORDER BY StartDate";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0) {
                $rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
                if(current($rooms)['StartDate'] > $date2 || $date1 > end($rooms)['EndDate']){
                    $valid_date=true;
                }else{
                for($i=0;$i<count($rooms)-1;$i++){
                    if ($rooms[$i+1]['StartDate'] > $date2 && $date1 > $rooms[$i]['EndDate']) {
                        $valid_date=true;
                        break;
                    }
                    }
                }
                }
            }
            // inserting data to database
    if($valid_date){
        $SQL = "INSERT INTO rooms(Full_Name,Email,StartDate,EndDate,room_id) VALUES ('$FullName','$Email','$date1','$date2','$room_id')";
        if (mysqli_query($conn, $SQL)) {

            $SQL = "INSERT INTO users_info(Card_Number,Expiration_Date,Security_Code) VALUES('$cardNumber','$ExpirationDate','$securitycode')";
            if (mysqli_query($conn, $SQL)){
            $SQL = "UPDATE available_rooms SET is_available=0 WHERE id=$room_id";
            if (mysqli_query($conn, $SQL)){
               echo "your order has been registered";
            }else {
                echo "TRY AGAIN";
            }
        }
    }


    }else{
        echo"wrong date";
    }

   }
      mysqli_close($conn);
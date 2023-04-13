<?php
$servername = "localhost";
$username = "root";
$password = null;
$dbname = "haaletussusteem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstname = $_POST['first_name'];
$lastname = $_POST['last_name'];
$vote = isset($_POST['vote']) ? $_POST['vote'] : 0;

// Check if voter exists in the database
$sql = "SELECT * FROM HAALETUS WHERE eesnimi='$firstname' AND perenimi='$lastname'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Voter exists in the database
    $row = $result->fetch_assoc();
    $voter_id = $row['id'];
    $previous_vote_time = $row['hääletuse_aeg'];
    $previous_vote = $row['otsus'];

    // Check if the voter has already voted
     if (is_null($previous_vote)) {
         // Voter has not yet voted
         $sql = "UPDATE HAALETUS SET otsus=$vote, hääletuse_aeg=CURRENT_TIMESTAMP WHERE id=$voter_id";
         if ($conn->query($sql) === TRUE) {
             echo "Vote registered successfully!";
         } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
         }
     } else {
         // Voter has already voted
         $now = time();
         $vote_time = strtotime($previous_vote_time);
         $minutes_since_last_vote = ($now - $vote_time) / 60;


         if ($minutes_since_last_vote >= 5) {
             // 5 minutes have passed, voter can't change vote
             echo "Sorry, you cannot change your vote as 5 minutes have passed since your last vote.";
         } else {
             // Voter can change vote
             $sql = "UPDATE HAALETUS SET otsus=$vote, hääletuse_aeg=NOW() WHERE id=$voter_id";
             if ($conn->query($sql) === TRUE) {
                 echo "Vote changed successfully!";
             } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
             }
         }
     }
} else {
    // Voter does not exist in the database
    echo "Sorry, you are not registered to vote.";
}

$conn->close();
?>
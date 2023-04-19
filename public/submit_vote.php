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

    // Check if voting session is open
    $voting_session_sql = "SELECT * FROM voting_session WHERE status='open'";
    $voting_session_result = $conn->query($voting_session_sql);

    if ($voting_session_result->num_rows > 0) {
        // Voting session is open
        $sql = "UPDATE HAALETUS SET otsus=$vote, hääletuse_aeg=CURRENT_TIMESTAMP WHERE id=$voter_id";
        if ($conn->query($sql) === TRUE) {
            echo "Vote registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Voting session is closed
        echo "Sorry, the voting session is closed.";
    }
} else {
    // Voter does not exist in the database
    echo "Sorry, you are not registered to vote.";
}

$conn->close();
?>

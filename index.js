const mysql = require('mysql');
const express = require('express');
const app = express();

const port = 4000;

// Serve static files
app.use(express.static('public'));

app.get('/', (req, res) => {
    res.sendFile(__dirname + '/public/index.html');
});


// Connect to MySQL
const connection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: null,
    database: 'haaletussusteem'
});


connection.connect((err) => {
    if (err) throw err;
    console.log('Connected to MySQL Server!');
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
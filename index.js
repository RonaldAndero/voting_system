const mysql = require('mysql');
const express = require('express');
const app = express();

const port = 4000;

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

connection.query('SELECT * FROM haaletus', (err, rows) => {
    if (err) throw err;

    console.log('Data received from Db:');
    console.log(rows);
});

connection.end((err) => {
    if (err) throw err;
    console.log('Connection closed');
});
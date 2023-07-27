<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "raintree_db";

// Connect to the database
$conn = mysqli_connect($hostname, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create the database if it doesn't exist
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $database";
if (mysqli_query($conn, $createDatabaseQuery)) {
    echo "Database created successfully" . PHP_EOL;
} else {
    die("Error creating database: " . mysqli_error($conn) . PHP_EOL);
}

// Select the database
mysqli_select_db($conn, $database);

// Create the patient table
$createPatientTableQuery = "
CREATE TABLE IF NOT EXISTS patient (
    _id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    pn VARCHAR(11) DEFAULT NULL,
    first VARCHAR(15) DEFAULT NULL,
    last VARCHAR(25) DEFAULT NULL,
    dob DATE DEFAULT NULL,
    PRIMARY KEY (_id)
)";
if (mysqli_query($conn, $createPatientTableQuery)) {
    echo "Patient table created successfully" . PHP_EOL;
} else {
    die("Error creating patient table: " . mysqli_error($conn) . PHP_EOL);
}

// Create the insurance table
$createInsuranceTableQuery = "
CREATE TABLE IF NOT EXISTS insurance (
    _id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT(10) UNSIGNED NOT NULL,
    iname VARCHAR(40) DEFAULT NULL,
    from_date DATE DEFAULT NULL,
    to_date DATE DEFAULT NULL,
    PRIMARY KEY (_id),
    FOREIGN KEY (patient_id) REFERENCES patient(_id)
)";
if (mysqli_query($conn, $createInsuranceTableQuery)) {
    echo "Insurance table created successfully" . PHP_EOL;
} else {
    die("Error creating insurance table: " . mysqli_error($conn) . PHP_EOL);
}

// Insert default data into patient table
$insertPatientDataQuery = "INSERT INTO patient (_id, pn, first, last, dob) VALUES
    (1, 'PN001', 'John', 'Doe', '1990-08-15'),
    (2, 'PN002', 'Jane', 'Smith', '1985-05-20'),
    (3, 'PN003', 'Michael', 'Johnson', '1978-12-10'),
    (4, 'PN004', 'Emily', 'Brown', '1995-02-25'),
    (5, 'PN005', 'William', 'Lee', '1982-06-30')";

if (mysqli_query($conn, $insertPatientDataQuery)) {
    echo "Default data inserted into patient table successfully" . PHP_EOL;
} else {
    die("Error inserting default data into patient table: " . mysqli_error($conn) . PHP_EOL);
}

// Insert default data into insurance table
$insertInsuranceDataQuery = "INSERT INTO insurance (patient_id, iname, from_date, to_date) VALUES
    (1, 'Red Cross', '2023-07-01', '2024-06-30'),
    (1, 'Medicalm', '2023-05-01', '2024-04-30'),
    (2, 'Blue Shield', '2023-07-01', '2024-06-30'),
    (2, 'Medicare', '2023-02-01', '2024-01-30'),
    (3, 'Green Cross', '2023-07-01', '2024-06-30'),
    (3, 'Medicaid', '2023-08-01', '2024-07-30'),
    (4, 'Red Cross', '2021-07-01', '2022-06-30'),
    (4, 'Medicare', '2017-06-01', '2019-05-30'),
    (5, 'Blue Shield', '2023-07-01', '2024-06-30'),
    (5, 'Medicaid', '2023-05-01', '2024-04-30')";

if (mysqli_query($conn, $insertInsuranceDataQuery)) {
    echo "Default data inserted into insurance table successfully" . PHP_EOL;
} else {
    die("Error inserting default data into insurance table: " . mysqli_error($conn) . PHP_EOL);
}

// Close the connection
mysqli_close($conn);
echo "Connection closed successfully" . PHP_EOL;
?>

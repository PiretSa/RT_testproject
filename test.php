<?php
// Database connection details (replace with your actual credentials)
$hostname = "localhost";
$username = "root";
$password = "";
$database = "raintree_db";

// Create a new MySQLi database connection
$db = new mysqli($hostname, $username, $password, $database);

// Check if the connection was successful
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Include the modified classes and interfaces defined above.
// Note that the order of inclusion matters.
include_once "PatientRecord.php";
include_once "Insurance.php";
include_once "Patient.php";

// Fetch patients and their insurance records from the database
$patients = [];
$result = $db->query("SELECT * FROM patient");
while ($row = $result->fetch_assoc()) {
    $patientId = $row['_id'];

    // Fetch insurance records for the patient from the database
    $insuranceRecords = [];
    $insResult = $db->query("SELECT * FROM insurance WHERE patient_id = $patientId");
    while ($insRow = $insResult->fetch_assoc()) {
        $insuranceRecords[] = new Insurance($db, $insRow['_id'], $insRow['patient_id'], $insRow['iname'], $insRow['from_date'], $insRow['to_date']);
    }
    $insResult->close();

    $patients[] = new Patient($db, $row['pn'], $row['first'], $row['last'], $row['dob'], $insuranceRecords);
}
$result->close();

// Sort patients based on patient number ascending
usort($patients, function ($a, $b) {
    return $a->getPatientNumber() <=> $b->getPatientNumber();
});

// Print out the patients and their insurance records
echo "Patient Number, First Last, Insurance name, Is Valid\n";
foreach ($patients as $patient) {
    $insurances = $patient->getInsuranceRecords();
    foreach ($insurances as $insurance) {
        $isValid = $insurance->isValidDate(date("Y-m-d")) ? 'Yes' : 'No';
        echo "{$patient->getPatientNumber()}, {$patient->getFullName()}, {$insurance->getName()}, $isValid\n";
    }
}

// Close the database connection when done
$db->close();
?>

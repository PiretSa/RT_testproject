<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "raintree_db";

// Connect to the database
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to retrieve patient and insurance data
$query = "
    SELECT
        p.pn AS 'Patient Number',
        p.last AS 'Patient Last Name',
        p.first AS 'Patient First Name',
        i.iname AS 'Insurance Name',
        DATE_FORMAT(i.from_date, '%m-%d-%y') AS 'Insurance From Date',
        DATE_FORMAT(i.to_date, '%m-%d-%y') AS 'Insurance To Date'
    FROM patient p
    INNER JOIN insurance i ON p._id = i.patient_id
    ORDER BY (
        SELECT MIN(from_date)
        FROM insurance i2
        WHERE i2.patient_id = p._id
    ),
    p.last,
    i.from_date;
";

// Execute the query
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Store patient data in an array
$patientData = array();
while ($row = $result->fetch_assoc()) {
    $patientData[] = sprintf(
        "%s, %s, %s, %s, %s, %s",
        $row['Patient Number'],
        $row['Patient Last Name'],
        $row['Patient First Name'],
        $row['Insurance Name'],
        $row['Insurance From Date'],
        $row['Insurance To Date']
    );
}

// SQL query to count all letters occurrences in patients' first and last names
$statisticsQuery = "
    SELECT CONCAT_WS(' ', p.first, p.last) AS 'Patient Name'
    FROM patient p
    INNER JOIN insurance i ON p._id = i.patient_id
    GROUP BY CONCAT_WS(' ', p.first, p.last);
";

// Execute the statistics query
$statisticsResult = mysqli_query($conn, $statisticsQuery);

// Check for errors
if (!$statisticsResult) {
    die("Error: " . mysqli_error($conn));
}

// Store statistics data in an array
$letterOccurrencesData = array();
while ($row = $statisticsResult->fetch_assoc()) {
    $name = strtoupper($row['Patient Name']);
    foreach (str_split($name) as $letter) {
        if (ctype_alpha($letter)) {
            if (isset($letterOccurrencesData[$letter])) {
                $letterOccurrencesData[$letter]++;
            } else {
                $letterOccurrencesData[$letter] = 1;
            }
        }
    }
}

// Close the connection
mysqli_close($conn);

// Calculate the total number of letters occurred
$totalLetters = array_sum($letterOccurrencesData);

// Display the patient data
echo "Patient Data:\n";
foreach ($patientData as $patientEntry) {
    echo $patientEntry . "\n";
}

// Display the letter occurrences data sorted alphabetically
ksort($letterOccurrencesData);
echo "\nLetter Occurrences in Patients' Names:\n";

// Check if $totalLetters is greater than zero before calculating percentages
if ($totalLetters > 0) {
    foreach ($letterOccurrencesData as $letter => $count) {
        $percentage = ($count / $totalLetters) * 100;
        echo "$letter\t$count\t" . number_format($percentage, 2) . "%\n";
    }
}

?>

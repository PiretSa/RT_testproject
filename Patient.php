<?php
class Patient implements PatientRecord {
    private $_id;
    private $pn;
    private $firstName;
    private $lastName;
    private $dob;
    private $insuranceRecords;

    // Add a database connection property
    private $db;

    public function __construct($db, $pn, $firstName, $lastName, $dob, $insuranceRecords = []) {
        $this->db = $db;
        $this->pn = $pn;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dob = $dob;
        $this->insuranceRecords = $insuranceRecords;
    }

    public function getRecordId() {
        return $this->_id;
    }

    public function getPatientNumber() {
        return $this->pn;
    }

    public function getFullName() {
        return $this->firstName . " " . $this->lastName;
    }

    public function getInsuranceRecords() {
        return $this->insuranceRecords;
    }

    public function printInsuranceTable() {
        echo "Patient Number, First Last, Insurance name, Is Valid\n";
        $today = date("Y-m-d");
        foreach ($this->insuranceRecords as $insurance) {
            $isValid = $insurance->isValidDate($today) ? 'Yes' : 'No';
            echo "{$this->pn}, {$this->getFullName()}, {$insurance->getName()}, $isValid\n";
        }
    }

    // Save patient data to the database
    public function saveToDatabase() {
        // Assuming that you are using prepared statements for security
        $stmt = $this->db->prepare("INSERT INTO patient (pn, first, last, dob) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->pn, $this->firstName, $this->lastName, $this->dob);
        $stmt->execute();
        $stmt->close();
    }
}
?>

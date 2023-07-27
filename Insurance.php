<?php

class Insurance implements PatientRecord
{
    private $_id;
    private $pn;
    private $name;
    private $fromDate;
    private $toDate;

    // Add a database connection property
    private $db;

    public function __construct($db, $_id, $pn, $name, $fromDate, $toDate)
    {
        $this->db = $db;
        $this->_id = $_id;
        $this->pn = $pn;
        $this->name = $name;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    // Implement the getRecordId() method
    public function getRecordId()
    {
        return $this->_id;
    }

    // Implement the getPatientNumber() method
    public function getPatientNumber()
    {
        return $this->pn;
    }

    // Implement the getName() method
    public function getName()
    {
        return $this->name;
    }

    // Implement the isValidDate() method
    public function isValidDate($date) : bool
    {
        $compareDate = DateTime::createFromFormat('Y-m-d', $date);
        $fromDate = DateTime::createFromFormat('Y-m-d', $this->fromDate);
        if ($this->toDate !== null) {
            $toDate = DateTime::createFromFormat('Y-m-d', $this->toDate);
            return $compareDate >= $fromDate && $compareDate <= $toDate;
        } else {
            return $compareDate >= $fromDate;
        }
    }

    // Save insurance data to the database
    public function saveToDatabase($patientId)
    {
        // Assuming that you are using prepared statements for security
        $stmt = $this->db->prepare("INSERT INTO insurance (patient_id, iname, from_date, to_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $patientId, $this->name, $this->fromDate, $this->toDate);
        $stmt->execute();
        $stmt->close();
    }
}
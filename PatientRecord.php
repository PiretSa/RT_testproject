<?php

interface PatientRecord
{
    // Declare a method for returning the implementing record's "_id" property
    public function getRecordId();

    // Declare a method for returning the implementing record's associated patient number
    public function getPatientNumber();
}
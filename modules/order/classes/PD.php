<?php defined('SYSPATH') OR die('No direct access allowed.');

class PD {
    public function __construct($id_pep) {
        $this->id_pep = $id_pep;
    }

    public function checkSignature($id_pep) {
        $filename = DOCROOT . 'uploads/signatures/' . $id_pep . '.jpg';
        if (file_exists($filename)) {
            return $filename;
        }
        return false;
    }
}
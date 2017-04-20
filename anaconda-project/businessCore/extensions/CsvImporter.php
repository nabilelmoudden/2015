<?php

class CsvImporter {

    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;
    private $count;

    //--------------------------------------------------------------------
    function __construct($file_name, $parse_header = false, $delimiter = "\t", $length = 8000) {
        $this->fp = fopen($file_name, "r");
        $this->parse_header = $parse_header;
        $this->delimiter = $delimiter;
        $this->length = $length;
        $this->count = 0;

        if ($this->parse_header) {
            $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }
    }

    //--------------------------------------------------------------------
    function __destruct() {
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    //--------------------------------------------------------------------
    function get($max_lines = 0) {
        //if $max_lines is set to 0, then get all the data

        $data = array();

        if ($max_lines > 0)
            $line_count = 0;
        else
            $line_count = -1; // so loop limit is ignored

        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE) {
            $this->count++;
            if ($this->parse_header) {
                $data[] = $this->getRow($this->header,$row);
            } else {
                $data[] = $row;
            }

            if ($max_lines > 0)
                $line_count++;
        }
        return $data;
    }

    private function getRow($header, $row) {
        $row_new = array();
        foreach ($header as $i => $heading_i) {
            $row_new[$heading_i] = $row[$i];
        }
        return $row_new;
    }
    
    public function rowCount() {
        return $this->count;
    }

    //--------------------------------------------------------------------
}

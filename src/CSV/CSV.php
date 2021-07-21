<?php

namespace Converter\CSV;

/**
 * 
 */
class CSV
{
	private const SEPARATOR = ',';
	private const ROW_LENGTH = 1000;

	private $csv_file;
	
	function __construct($csv_file)
	{
		if (file_exists($csv_file)) {
			$this->csv_file = $csv_file;
        } else {
            throw new Exception("Файл ".$csv_file." не найден"); 
        }

        ini_set("auto_detect_line_endings", true);
	}

	public function setCSV(array $csv)
	{
        $handle = fopen($this->csv_file, "w"); 
 
        foreach ($csv as $i => $row) {
            $result = fputcsv($handle, $row, self::SEPARATOR);

            if ($result === FALSE) throw new Exception("Ошибка записи в csv файл. Строка ".$i+1."");
        }
        fclose($handle);

        return (bool)$result;
    }
 
    /**
     * @return array;
     */
    public function getCSV()
    { 
        $csv_array = [];

        $handle = fopen($this->csv_file, "r");
        while (($row = fgetcsv($handle, self::ROW_LENGTH, self::SEPARATOR)) !== FALSE) { 
            $csv_array[] = $row;
        }
        fclose($handle);

        return $csv_array;
    }
}
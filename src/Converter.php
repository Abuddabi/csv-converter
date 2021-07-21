<?php

namespace Converter;

use Converter\CSV\CSV;
use Converter\Exchanger\Exchanger;

/**
 * 
 */
class Converter
{
	private const INITIAL_CURRENCY = 'RUB';
	private const FINAL_CURRENCY   = 'USD';

	private $csv = null;
	private $exchanger = null;
	
	function __construct()
	{	
		$this->exchanger = new Exchanger(self::FINAL_CURRENCY, self::INITIAL_CURRENCY);
	}

	public function convert($csv_file)
	{
		try {
			$this->csv = new CSV($csv_file);

			$csv_array = $this->csv->getCSV();
			$csv_array = $this->addNewColumn($csv_array);

			$result = $this->csv->setCSV($csv_array);
		} catch (Exception $e) {
            echo "Ошибка: " . $e->getMessage(); 
        }        

        return $result;
	}

	private function addNewColumn(array $csv_array)
	{
		$initial_currency_column = null;
		$currency_rate = $this->exchanger->getLatestRate();

		foreach ($csv_array as $i => &$row) {
			if (is_null($initial_currency_column)) {
				$initial_currency_column = $this->getInitialCurrency($row);
			}

			if ($i === 0) { //heading
				$row[] = self::FINAL_CURRENCY;
			} else {
				$initial_currency_value = $row[$initial_currency_column];
				$row[] = round($initial_currency_value / $currency_rate, 2);
			}			
		}

		return $csv_array;
	}

	private function getInitialCurrency(array $row)
	{
		$initial_currency_column = null;

		foreach ($row as $i => $field) {
			if ($field === self::INITIAL_CURRENCY) {
				$initial_currency_column = $i;
				break;
			}
		}

		return $initial_currency_column;
	}
}
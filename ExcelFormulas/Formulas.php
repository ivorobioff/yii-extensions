<?php
namespace Extensions\ExcelFormulas;

use Extensions\ExcelFormulas\Trend;
use Extensions\ExcelFormulas\BestFit\Exponential;

class Formulas
{
	public function calcGrowth(array $y_values, array $x_values = array(), array $new_values = array())
	{
		$n_y = count($y_values);
		$n_x = count($x_values);

		if ($n_x == 0)
		{
			$x_values = range(1, $n_y);
			$n_x = $n_y;
		}
		elseif ($n_y != $n_x)
		{
			trigger_error("trend(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);
		}

		$best_fit = new Exponential($y_values, $x_values, true);

		if (empty($new_values))
		{
			$new_values = $best_fit->getXValues();
		}

		$return_array = array();
		foreach($new_values as $x_value)
		{
			$return_array[] = $best_fit->getValueOfYForX($x_value);
		}

		return $return_array;
	}
}
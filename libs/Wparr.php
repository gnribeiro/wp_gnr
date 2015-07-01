<?php 

class Wparr {


	public static $delimiter = '.';

	public static function is_assoc(array $array)
	{
		
		$keys = array_keys($array);
		return array_keys($keys) !== $keys;
	}

	public static function is_array($value)
	{
		if (is_array($value))
		{
			return TRUE;
		}
		else
		{
			return (is_object($value) AND $value instanceof Traversable);
		}
	}

	public static function path($array, $path, $default = NULL, $delimiter = NULL)
	{
		if ( ! Wparr::is_array($array))
		{
			return $default;
		}

		if (is_array($path))
		{
			$keys = $path;
		}
		else
		{
			if (array_key_exists($path, $array))
			{
				return $array[$path];
			}

			if ($delimiter === NULL)
			{
				$delimiter = Wparr::$delimiter;
			}

			$path = ltrim($path, "{$delimiter} ");

			$path = rtrim($path, "{$delimiter} *");

			$keys = explode($delimiter, $path);
		}

		do
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{
				$key = (int) $key;
			}

			if (isset($array[$key]))
			{
				if ($keys)
				{
					if (Wparr::is_array($array[$key]))
					{
						$array = $array[$key];
					}
					else
					{
						break;
					}
				}
				else
				{
					return $array[$key];
				}
			}
			elseif ($key === '*')
			{

				$values = array();
				foreach ($array as $arr)
				{
					if ($value = Wparr::path($arr, implode('.', $keys)))
					{
						$values[] = $value;
					}
				}

				if ($values)
				{
					return $values;
				}
				else
				{
					break;
				}
			}
			else
			{
				break;
			}
		}
		while ($keys);

		return $default;
	}


	public static function set_path( & $array, $path, $value, $delimiter = NULL)
	{
		if ( ! $delimiter)
		{
			$delimiter = Wparr::$delimiter;
		}

		$keys = $path;
		if ( ! is_array($path))
		{
			// Split the keys by delimiter
			$keys = explode($delimiter, $path);
		}

		// Set current $array to inner-most array path
		while (count($keys) > 1)
		{
			$key = array_shift($keys);

			if (ctype_digit($key))
			{

				$key = (int) $key;
			}

			if ( ! isset($array[$key]))
			{
				$array[$key] = array();
			}

			$array = & $array[$key];
		}

		// Set key on inner-most array
		$array[array_shift($keys)] = $value;
	}

	/**
	 * Fill an array with a range of numbers.
	 *
	 *     // Fill an array with values 5, 10, 15, 20
	 *     $values = Arr::range(5, 20);
	 *
	 * @param   integer $step   stepping
	 * @param   integer $max    ending number
	 * @return  array
	 */
	public static function range($step = 10, $max = 100)
	{
		if ($step < 1)
			return array();

		$array = array();
		for ($i = $step; $i <= $max; $i += $step)
		{
			$array[$i] = $i;
		}

		return $array;
	}


	public static function get($array, $key, $default = NULL)
	{
		if ($array instanceof ArrayObject) {
			// This is a workaround for inconsistent implementation of isset between PHP and HHVM
			// See https://github.com/facebook/hhvm/issues/3437
			return $array->offsetExists($key) ? $array->offsetGet($key) : $default;
		} else {
			return isset($array[$key]) ? $array[$key] : $default;
		}
	}


	public static function extract($array, array $paths, $default = NULL)
	{
		$found = array();
		foreach ($paths as $path)
		{
			Wparr::set_path($found, $path, Wparr::path($array, $path, $default));
		}

		return $found;
	}


	public static function pluck($array, $key)
	{
		$values = array();

		foreach ($array as $row)
		{
			if (isset($row[$key]))
			{
				// Found a value in this row
				$values[] = $row[$key];
			}
		}

		return $values;
	}


	public static function unshift( array & $array, $key, $val)
	{
		$array = array_reverse($array, TRUE);
		$array[$key] = $val;
		$array = array_reverse($array, TRUE);

		return $array;
	}


	public static function map($callbacks, $array, $keys = NULL)
	{
		foreach ($array as $key => $val)
		{
			if (is_array($val))
			{
				$array[$key] = Wparr::map($callbacks, $array[$key]);
			}
			elseif ( ! is_array($keys) OR in_array($key, $keys))
			{
				if (is_array($callbacks))
				{
					foreach ($callbacks as $cb)
					{
						$array[$key] = call_user_func($cb, $array[$key]);
					}
				}
				else
				{
					$array[$key] = call_user_func($callbacks, $array[$key]);
				}
			}
		}

		return $array;
	}


	public static function merge($array1, $array2)
	{
		if (Wparr::is_assoc($array2))
		{
			foreach ($array2 as $key => $value)
			{
				if (is_array($value)
					AND isset($array1[$key])
					AND is_array($array1[$key])
				)
				{
					$array1[$key] = Wparr::merge($array1[$key], $value);
				}
				else
				{
					$array1[$key] = $value;
				}
			}
		}
		else
		{
			foreach ($array2 as $value)
			{
				if ( ! in_array($value, $array1, TRUE))
				{
					$array1[] = $value;
				}
			}
		}

		if (func_num_args() > 2)
		{
			foreach (array_slice(func_get_args(), 2) as $array2)
			{
				if (Wparr::is_assoc($array2))
				{
					foreach ($array2 as $key => $value)
					{
						if (is_array($value)
							AND isset($array1[$key])
							AND is_array($array1[$key])
						)
						{
							$array1[$key] = Wparr::merge($array1[$key], $value);
						}
						else
						{
							$array1[$key] = $value;
						}
					}
				}
				else
				{
					foreach ($array2 as $value)
					{
						if ( ! in_array($value, $array1, TRUE))
						{
							$array1[] = $value;
						}
					}
				}
			}
		}

		return $array1;
	}


	public static function overwrite($array1, $array2)
	{
		foreach (array_intersect_key($array2, $array1) as $key => $value)
		{
			$array1[$key] = $value;
		}

		if (func_num_args() > 2)
		{
			foreach (array_slice(func_get_args(), 2) as $array2)
			{
				foreach (array_intersect_key($array2, $array1) as $key => $value)
				{
					$array1[$key] = $value;
				}
			}
		}

		return $array1;
	}


	public static function callback($str)
	{
		// Overloaded as parts are found
		$command = $params = NULL;

		// command[param,param]
		if (preg_match('/^([^\(]*+)\((.*)\)$/', $str, $match))
		{
			// command
			$command = $match[1];

			if ($match[2] !== '')
			{
				// param,param
				$params = preg_split('/(?<!\\\\),/', $match[2]);
				$params = str_replace('\,', ',', $params);
			}
		}
		else
		{
			// command
			$command = $str;
		}

		if (strpos($command, '::') !== FALSE)
		{
			// Create a static method callable command
			$command = explode('::', $command, 2);
		}

		return array($command, $params);
	}


	public static function flatten($array)
	{
		$is_assoc = Wparr::is_assoc($array);

		$flat = array();
		foreach ($array as $key => $value)
		{
			if (is_array($value))
			{
				$flat = array_merge($flat, Wparr::flatten($value));
			}
			else
			{
				if ($is_assoc)
				{
					$flat[$key] = $value;
				}
				else
				{
					$flat[] = $value;
				}
			}
		}
		return $flat;
	}

}

<?php

Class Gwp_Messages{
    public static function message($file, $path = NULL, $default = NULL)
    {
         static $messages;
        
        if ( ! isset($messages[$file]))
        {
            // Create a new message list
            $messages[$file] = array();

            if ($files = self::load_messages($file))
            {

                $messages[$file] = array_merge($messages[$file], $files);
            }
        }
        pr( $messages);

        if ($path === NULL)
        {
            return $messages[$file];
        }
        else
        {
            return self::path($messages[$file], $path, $default);
        }
    }
    
    
    public static function load_messages($file) {
        $file = MESSAGES . $file;
        return HELPER::load_file($file);
    }
    
    
    public static function path($array, $path, $default = NULL, $delimiter = NULL)
    {
        if ( ! is_array($array))
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
                // No need to do extra processing
                return $array[$path];
            }

            if ($delimiter === NULL)
            {
                // Use the default delimiter
                $delimiter = '-';
            }

            // Remove starting delimiters and spaces
            $path = ltrim($path, "{$delimiter} ");

            // Remove ending delimiters, spaces, and wildcards
            $path = rtrim($path, "{$delimiter} *");

            // Split the keys by delimiter
            $keys = explode($delimiter, $path);
        }

        do
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                // Make the key an integer
                $key = (int) $key;
            }

            if (isset($array[$key]))
            {
                if ($keys)
                {
                    if (is_array($array[$key]))
                    {
                        // Dig down into the next part of the path
                        $array = $array[$key];
                    }
                    else
                    {
                        // Unable to dig deeper
                        break;
                    }
                }
                else
                {
                    // Found the path requested
                    return $array[$key];
                }
            }
            elseif ($key === '*')
            {
                // Handle wildcards

                $values = array();
                foreach ($array as $arr)
                {
                    if ($value = self::path($arr, implode('.', $keys)))
                    {
                        $values[] = $value;
                    }
                }

                if ($values)
                {
                    // Found the values requested
                    return $values;
                }
                else
                {
                    // Unable to dig deeper
                    break;
                }
            }
            else
            {
                // Unable to dig deeper
                break;
            }
        }
        while ($keys);

        // Unable to find the value requested
        return $default;
    }
}


//$gwpmessages = new Gwp_Messages();
<?php

namespace VBoxCLI\Console;

/**
 * Class Config
 * @author Abu Bakar Al-Idrus
 */
class Config
{
    private static $configFile;

    /**
     * Get configuration parameter value for a VM.
     */
    public static function get($uuid = null, $parameter = null)
    {
        self::init();

        // If configuration file does not exist, then 
        if (!file_exists(self::$configFile))
        {
            self::writeConfig([]);

            return null;
        }

        // Get config into array
        $config = json_decode(file_get_contents(self::$configFile), true);

        if ($uuid === null)
        {
            if (!is_array($config))
            {
                return null;
            }

            return $config;
        }

        if ($parameter === null)
        {
            if (!is_array($config) || !array_key_exists($uuid, $config) || !is_array($config[$uuid]))
            {
                return null;
            }

            return $config[$uuid];
        }

        if (!is_array($config) || !array_key_exists($uuid, $config) || !is_array($config[$uuid]) || !array_key_exists($parameter, $config[$uuid]))
        {
            return null;
        }

        return $config[$uuid][$parameter];
    }

    /**
     * Set configuration parameter value for a VM.
     */
    public static function set($uuid, $parameter, $value)
    {
        self::init();

        // get full configuration
        $config = self::get();

        // if there is no config, initialize one
        if (!is_array($config))
        {
            $config = [
                $uuid => [
                    $parameter => $value,
                ],
            ];

            return self::writeConfig($config);
        }

        if (!array_key_exists($uuid, $config) || !is_array($config[$uuid]))
        {
            $config[$uuid] = [ 
                $parameter => $value,
            ];

            return self::writeConfig($config);
        }

        $config[$uuid][$parameter] = $value;

        return self::writeConfig($config);
    }
   
    private static function init()
    {
        self::$configFile = getenv('HOME').'/.VBoxCLI.json';
    }

    /**
     * Write configuration file from array.
     */
    private static function writeConfig($config)
    {
        self::init();

        return file_put_contents(self::$configFile, json_encode((object) $config, JSON_PRETTY_PRINT));
    }
}

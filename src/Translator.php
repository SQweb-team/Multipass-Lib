<?php

namespace Multipass\Core;

class Translator
{
    /**
     *  @var    stdClass Object The translation object
     */
    private $translations;

    /**
     *  @var    array   The locales supported by the translation file
     */
    private $supported = [
        'en_US',
        'en_GB',
        'fr_FR',
    ];

    /**
     *  Creates a new Translator instance.
     *
     *  @param  string  $locale The locale
     *  @param  string  $file   The location of the translation file
     */
    public function __construct($locale = null, $file = null)
    {
        $locale = $locale && in_array($locale, $this->supported) ? $locale : 'en_US';
        $file = $file ?: __DIR__ . '/../config/translator/lang.json';

        $this->translations = json_decode(file_get_contents($file))->{$locale};
    }

    /**
     *  Retrieves a localized string from the available translations.
     *
     *  @param  string  $path   The path identifier to retrieve the localized string from. This string should follow the dot notation.
     *  @return string
     */
    public function get($path)
    {
        if (!$path) {
            throw new \Exception('The path string cannot be null.');
        }

        $props = explode('.', $path);
        $check = $this->checkProperties($this->translations, $props);

        if (is_int($check)) {
            throw new \Exception("Path identifier is unkown or not a string: '$props[$check]' in '$path'");
        }

        return $check;
    }

    /**
     *  Checks if the provided path is correct when fetching a localized string,
     *  and if a matching string can be found.
     *
     *  @param  stdClass    $obj The object to check
     *  @param  array       $props The properties to check in the object
     *  @param  int         $level The recursion level
     *  @return mixed
     */
    private function checkProperties($obj, $props, $level = 0)
    {
        if (isset($obj->{$props[$level]})) {
            if ($level + 1 === count($props)) {
                if (is_string($obj->{$props[$level]})) {
                    return $obj->{$props[$level]};
                } else {
                    return $level;
                }
            }

            return $this->checkProperties($obj->{$props[$level]}, $props, $level + 1);
        }

        return $level;
    }
}

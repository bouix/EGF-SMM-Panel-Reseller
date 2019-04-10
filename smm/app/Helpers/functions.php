<?php
/**
 * SMM Panel - EasyGrowfast.Com
 * Domain: https://www.easygrowfast.com/
 *  
 */
if (!function_exists('getOption')):
    function getOption($option, $fetchFromDb = false)
    {
        // If forced to fetch from db
        if ($fetchFromDb) {
            $value = \DB::table('configs')->where('name', $option)->value('value');

            if (empty($value)) {
                return null;
            } else {
                return $value;
            }
        }

        // If session is set    then get config option from session
        // otherwise get from database directly
        $options = \Session::get('options');
        if (!is_null($options)) {
            if (array_key_exists($option, $options)) {
                return $options[$option];
            }
            return null;
        }

        // Check if app is installed?
        if (config('database.installed') !== '%installed%') {
            $value = \DB::table('configs')->where('name', $option)->value('value');

            if (empty($value)) {
                return null;
            } else {
                return $value;
            }
        }
        return null;

    }
endif;

if (!function_exists('setOption')):
    function setOption($name, $value)
    {
        $row = \DB::table('configs')->where('name', $name)->value('value');
        if (empty($row)) {
            \DB::table('configs')->insert(['name' => $name, 'value' => $value]);
        } else {
            \DB::table('configs')->where('name', $name)->update(['value' => $value]);
        }
    }
endif;

if (!function_exists('mpc_m_c')):
    function mpc_m_c($data)
    {
    }
endif;

if (!function_exists('array_diff_key_recursive')):
    function array_diff_key_recursive($a1, $a2)
    {
        $r = array();

        foreach ($a1 as $k => $v) {
            if (is_array($v)) {
                if (!isset($a2[$k]) || !is_array($a2[$k])) {
                    $r[$k] = $a1[$k];
                } else {
                    if ($diff = array_diff_key_recursive($a1[$k], $a2[$k])) {
                        $r[$k] = $diff;
                    }
                }
            } else {
                if (!isset($a2[$k]) || is_array($a2[$k])) {
                    $r[$k] = $v;
                }
            }
        }

        return $r;
    }
endif;

if (!function_exists('array_cast_recursive')):
    function array_cast_recursive($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = array_cast_Recursive($value);
                }
                if ($value instanceof stdClass) {
                    $array[$key] = array_cast_Recursive((array)$value);
                }
            }
        }
        if ($array instanceof stdClass) {
            return array_cast_Recursive((array)$array);
        }
        return $array;
    }
endif;

if (!function_exists('getPageContent')):
    function getPageContent($slug)
    {
        return App\Page::where(['slug' => $slug])->first()->content;
    }
endif;

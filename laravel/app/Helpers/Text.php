<?php

namespace App\Helpers;

class Text
{
    public static function plain($str = '', $maxLength = 0, $preserveWord = false)
    {
        // Remove HTML tags
        $str = strip_tags($str);
        // Remove non-ASCII characters
        $str = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str);
        // Remove double whitespaces
        $str = preg_replace('/\s+/', ' ', $str);

        if ($maxLength > 0) {
            if ($preserveWord) {
                if (strlen($str) <= $maxLength) {
                    $spacePos = strlen($str);
                }
                else {
                    $spacePos = strpos($str, ' ', $maxLength);
                    if (!$spacePos) {
                        $spacePos = strlen($str);
                    }
                }

                $str = substr($str, 0, $spacePos);
            }
            else {
                $str = substr($str, 0, $maxLength);
            }
        }

        return $str;
    }

    public static function transcript($str = '')
    {
        $str = trim($str);
        $str = htmlentities($str);
        $str = nl2br($str, false);
        return $str;
    }

    public static function fileSize($str = '')
    {
        $str = number_format($str, 0, ',', '.') . ' KB';
        return $str;
    }

    public static function article($article)
    {
        $search = ['<h2', '<h3'];
        $replace = ['<h2 class="fs-3 mb-3"', '<h3 class="fs-4 mb-3"'];
        return str_replace($search, $replace, $article);
    }
}
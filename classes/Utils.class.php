<?php

class Utils {

    public static function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    /**
     * Clermond-ferrant, France%è\42 => clermond-ferrant-france-42
     * @param type $raw
     * @return type
     */
    public static function slugify($raw, $tag = false) {
        //cyrylic transcription
        $cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
        $cyrylicTo__ = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia');


        $from = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
        $to__ = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");


        $from = array_merge($from, $cyrylicFrom);
        $to__ = array_merge($to__, $cyrylicTo__);

        $slug = str_replace($from, $to__, $raw);
        $slug = strtolower($slug);
        if ($tag == true) {
            $slug = preg_replace("/[^A-Za-z0-9 '-]/", '', $slug);
        } else {
            $slug = preg_replace("/[^A-Za-z0-9]/", '-', $slug);
        }
        $slug = preg_replace("/[-]+/", '-', $slug);
        return $slug;
    }

    public static function globRequire($pattern) {
        foreach (glob(_PATH_ . $pattern) as $filename) {
            require_once $filename;
        }
    }

    public static function cutAfter($string, $len) {
        $string = (strlen($string) > $len) ? substr($string, 0, $len - 3) . '...' : $string;
        return $string;
    }

    /**
     *
     * @see http://stackoverflow.com/a/8891890/311744
     */
    public static function getCurrentUrl() {
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
        return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
    }

    public static function isIe($majorVer = null) {
        if ($majorVer == null) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE "))
                return true;
        }
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE $majorVer")) {
            return true;
        }
        return false;
    }

    /**
     * Example: hasOneMatch(array('hello', 'baz', 'qux'), 'baz') => true
     *          hasOneMatch(array('hello', 'toto', 'qux'), 'tata') => false
     * @param type $haystack
     * @param type $needles
     * @return boolean
     */
    public static function hasOneMatch($haystacks, $needle) {
        // recherche vide (sous ie, le texte gris d'aide est soumis)
        if ($needle == '' || Utils::startsWith($needle, 'rechercher par centre')) {
            return true;
        }
        foreach ($haystacks as $haystack) {
            if ($haystack == '') {
                continue;
            }
            if (strpos(strtolower($haystack), strtolower($needle)) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Difference between two timestamps in days. $date2 - $date1
     * @see http://stackoverflow.com/questions/2040560/how-to-find-number-of-days-between-two-dates-using-php
     */
    public static function dateDiffInDays($date1, $date2) {
        $datediff = $date2 - $date1;
        return floor($datediff / (60 * 60 * 24));
    }

    /**
     * Difference between two timestamps
     * @see http://stackoverflow.com/a/3923228/311744
     * @param DateTime $date1
     * @param DateTime $date2
     * @param unit. y for year, m for month, d for day.
     * @return $date2 - $date 1
     */
    public static function dateDiff($date1, $date2, $unit = 'y') {
        $interval = $date1->diff($date2);
        return $interval->$unit;
    }

    public static function callback($match) {
        // Prepend http:// if no protocol specified
        $completeUrl = $match[1] ? $match[0] : "http://{$match[0]}";

        return '<a href="' . $completeUrl . '" rel="nofollow">'
                . $match[2] . $match[3] . $match[4] . '</a>';
    }

    /**
     * @see http://stackoverflow.com/questions/1188129/replace-urls-in-text-with-html-links
     */
    public static function recognizeLinks($input) {
        $rexProtocol = '(https?://)?';
        $rexDomain = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
        $rexPort = '(:[0-9]{1,5})?';
        $rexPath = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
        $rexQuery = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        $rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
        // added |< at the end of regexp, because some links are folowwed by <br/> and not \s
        return preg_replace_callback("&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$|<))&", 'Utils::callback', $input);
    }

    public static function formatName($firtname, $lastname) {
        return ucfirst(strtolower($firtname)) . " " . ucfirst(strtolower($lastname));
    }

}

function urlFor($name, $params = array()) {
    return App::instance()->urlFor($name, $params);
}
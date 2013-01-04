<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RL\MainBundle\Entity\Mark;

/**
 * @ORM\Entity
 */
final class BbCode extends Mark
{

    /**
     * @param $string
     * @return mixed|string
     */
    public function render($string)
    {
        $code = array();
        $lang = array();
        $codeRegExp = "#(\\[code)=?(abap|actionscript|actionscript3|ada|apache|applescript|apt_sources|asm|asp|autoit|avisynth|bash|basic4gl|bf|bibtex|blitzbasic|bnf|boo|c|c_mac|caddcl|cadlisp|cfdg|cfm|cil|cmake|cobol|cpp|cpp-qt|csharp|css|d|dcs|delphi|diff|div|dos|dot|eiffel|e-mail|erlang|fo|fortran|freebasic|genero|gettext|glsl|gml|gnuplot|groovy|haskell|hq9plus|html4strict|idl|ini|inno|intercal|io|java|java5|javascript|kixtart|klonec|latex|lisp|locobasic|lolcode|lotusformulas|lotusscript|lscript|lsl2|lua|m68k|make|matlab|mirc|modula3|mpasm|mxml|mysql|nsis|oberon2|objc|ocaml|ocaml-brief|oobas|oracle11|oracle8|pascal|per|perl|php|php-brief|pic16|pixelbender|plsql|povray|powershell|progress|prolog|providex||python|qbasic|rails|rebol|reg|robots|ruby|sas|scala|scheme|scilab|sdlbasic|smalltalk|smarty|sql|tcl|teraterm|text|thinbasic|tsql|typoscript|vb|vbnet|verilog|vhdl|vim|visualfoxpro|visualprolog|whitespace|whois|winbatch|xml|xorg_conf|xpp|z80)?(\\])(.*?[^\\[/code\\]]?)(\\[/code\\])#sim";
        $arr = preg_match_all($codeRegExp, $string, $match);
        for ($i = 0; $i < $arr; $i++) {
            $lang[$i] = $match[2][$i];
            $withBreaks = parent::highlight(html_entity_decode($match[4][$i], ENT_QUOTES), $match[2][$i]);
            $code[$i] = $withBreaks;
            $string = str_replace($match[0][$i], '⓬' . $i . '⓬', $string);
        }
        $math = array();
        $regExp = '#(\\[math\\])(.*?)(\\[/math\\])#suim';
        $vh = preg_match_all($regExp, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $withBreaks = parent::makeFormula($match[2][$i]);
            $math[$i] = $withBreaks;
            $string = str_replace($match[0][$i], 'ᴥ' . $i . 'ᴥ', $string);
        }
        $string = htmlspecialchars($string);
        $string = str_replace('\\', '&#92;', $string);
        $string = preg_replace("#(\\[b\\])(.*?[^\\[/b\\]]?)(\\[/b\\])#sim", "<b>\$2</b>", $string);
        $string = preg_replace(
            "#(\\[spoiler\\])(.*?[^\\[/spoiler\\]]?)(\\[/spoiler\\])#sim",
            "<span class=\"spoiler\">\$2</span>",
            $string
        );
        $string = preg_replace("#(\\[i\\])(.*?[^\\[/i\\]]?)(\\[/i\\])#sim", "<i>\$2</i>", $string);
        $string = preg_replace("#(\\[u\\])(.*?[^\\[/u\\]]?)(\\[/u\\])#sim", "<u>\$2</u>", $string);
        $string = preg_replace("#(\\[s\\])(.*?[^\\[/s\\]]?)(\\[/s\\])#sim", "<s>\$2</s>", $string);
        $string = preg_replace("#(\\[sub\\])(.*?[^\\[/sub\\]]?)(\\[/sub\\])#sim", "<sub>\$2</sub>", $string);
        $string = preg_replace("#(\\[sup\\])(.*?[^\\[/sup\\]]?)(\\[/sup\\])#sim", "<sup>\$2</sup>", $string);
        $string = str_replace('imgh://', 'http://', $string);
        $string = str_replace('imghs://', 'https://', $string);
        $tags = array('list' => '<ul>', 'num' => '<ol>', 'quote' => '<div class="quote"><pre>',);
        foreach ($tags as $tag => $val) {
            if ($tag == 'list') {
                $regExp = '#(\\[list\\])(.*?[^\\[/list\\]]?)(\\[/list\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</ul>", $string, 1);
                    $withBreaks = str_replace('[*]', '<li>&nbsp;', $match[2][$i]);
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'num') {
                $regExp = '#(\\[num\\])(.*?[^\\[/num\\]]?)(\\[/num\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</ol>", $string, 1);
                    $withBreaks = str_replace('[*]', '<li>&nbsp;', $match[2][$i]);
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'quote') {
                $regExp = '#(\\[quote\\])(.*?[^\\[/quote\\]]?)(\\[/quote\\])#sim';
                $vt = preg_match_all($regExp, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($regExp, "$val\$2</pre></div>", $string, 1);
                    $withBreaks = $match[2][$i];
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
        }
        $string = preg_replace(
            '#(\\[p align=)(left|right|center)(\\])(.*?[^\\[/p\\]]?)(\\[/p\\])#sim',
            "<p align=\"\$2\">\$4</p>",
            $string
        );

        $imgRegExp = '#(\\[img) ?(align=)?(left|right|middle|top|bottom)?(\\])(.*?[^\\[/img\\]]?)(\\[/img\\])#sim';
        $vt = preg_match_all($imgRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $imageInfo = getimagesize($match[5][$i]);
            if ($imageInfo[0] > 1024) {
                if (!empty($match[3][$i])) {
                    $string = preg_replace(
                        $imgRegExp,
                        "<img src=\"\$5\" align=\"$3\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $imgRegExp,
                        "<img src=\"\$5\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            } else {
                if (empty($match[3][$i])) {
                    $string = preg_replace(
                        $imgRegExp,
                        "<img src=\"\$5\" align=\"$3\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $imgRegExp,
                        "<img src=\"\$5\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            }
        }
        $userRegExp = "#(\\[user\\])(.*?[^\\[/user\\]]?)(\\[/user\\])#sim";
        $arr = preg_match_all($userRegExp, $string, $match);
        /** @var $userRepository \RL\MainBundle\Entity\UserRepository */
        $userRepository = $this->entityManager->getRepository('RLMainBundle:User');
        for ($i = 0; $i < $arr; $i++) {
            $user = $userRepository->findOneByUsername($match[2][$i]);
            if (null !== $user) {
                $string = preg_replace(
                    $userRegExp,
                    "<b><a href=\"/user/\$2\">\$2</a></b>",
                    $string,
                    1
                );
            } else {
                $string = preg_replace($userRegExp, "\$2", $string, 1);
            }
        }
        $urlRegExp = '#(\\[url\\])(.*?[^\\[/url\\]]?)(\\[/url\\])#sim';
        $vt = preg_match_all($urlRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[2][$i], FILTER_VALIDATE_URL)) {
                $string = preg_replace($urlRegExp, "<a href=\"\$2\">\$2</a>", $string);
            }
        }
        $parametrisedUrlRegExp = '#(\\[url=)(.*?[^\\]]?)(\\])(.*?[^\\[/url\\]]?)(\\[/url\\])#sim';
        $vt = preg_match_all($parametrisedUrlRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[2][$i], FILTER_VALIDATE_URL)) {
                $string = preg_replace($parametrisedUrlRegExp, "<a href=\"\$2\">\$4</a>", $string);
            }
        }
        $string = '<p>' . $string . '</p>';
        $string = str_replace("\r\n", '</p><p>', $string);
        $regExp = "#(⓬)([0-9]+)(⓬)#sim";
        $vt = preg_match_all($regExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = str_replace(
                '⓬' . $match[2][$i] . '⓬',
                    '<fieldset><legend>' . $lang[$match[2][$i]] . '</legend>' . $code[$match[2][$i]] . '</fieldset>',
                $string
            );
        }
        $regExp = "#(ᴥ)([0-9]+)(ᴥ)#suim";
        $vt = preg_match_all($regExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = str_replace('ᴥ' . $match[2][$i] . 'ᴥ', $math[$match[2][$i]], $string);
        }

        return $string;
    }
}

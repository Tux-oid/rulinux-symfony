<?php
/**
 * Copyright (c) 2008 - 2012, Peter Vasilevsky
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the RL nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL PETER VASILEVSKY BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

namespace RL\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RL\MainBundle\Entity\Mark;

/**
 * RL\MainBundle\Entity\TexMark
 *
 * @ORM\Entity
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
final class TexMark extends Mark
{

    /**
     * @param $string
     * @return mixed|string
     */
    public function render($string)
    {
        $code = array();
        $lang = array();
        $re = '#(\\\\begin)(\[)?('.implode('|', $this->geshi->getHighlightedLanguagesList()).')?(\])?({highlight})(.*?[^\\\\end{highlight}]?)(\\\\end{highlight})#sim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $lang[$i] = $match[2][$i];
            $withBreaks = $this->geshi->highlight(
                html_entity_decode($match[6][$i], ENT_QUOTES),
                $match[3][$i]
            );
            $code[$i] = $withBreaks;
            $string = str_replace($match[0][$i], '⓬' . $i . '⓬', $string);
        }
        $re = '#(\\\\begin\\{math\\})(.*?)(\\\\end\\{math\\})#suim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $withBreaks = $this->math->render($match[2][$i]);
            $math[$i] = $withBreaks;
            $string = str_replace($match[0][$i], 'ᴥ' . $i . 'ᴥ', $string);
        }
        $string = htmlspecialchars($string);
        $string = str_replace('\\\\', '&#92;', $string);
        $string = preg_replace("#\\\\}#sim", "&#125;", $string);
        $string = preg_replace("#(\\\\b{)(.*?[^}]?)(})#sim", "<b>\$2</b>", $string);
        $string = preg_replace("#(\\\\spoiler{)(.*?[^}]?)(})#sim", "<span class=\"spoiler\">\$2</span>", $string);
        $string = preg_replace("#(\\\\i{)(.*?[^}]?)(})#sim", "<i>\$2</i>", $string);
        $string = preg_replace("#(\\\\u{)(.*?[^}]?)(})#sim", "<u>\$2</u>", $string);
        $string = preg_replace("#(\\\\s{)(.*?[^}]?)(})#sim", "<s>\$2</s>", $string);
        $string = preg_replace("#(\\\\sub{)(.*?[^}]?)(})#sim", "<sub>\$2</sub>", $string);
        $string = preg_replace("#(\\\\sup{)(.*?[^}]?)(})#sim", "<sup>\$2</sup>", $string);
        $string = preg_replace("#\\\\br#sim", "<br />", $string);
        $string = str_replace('imgh://', 'http://', $string);
        $string = str_replace('imghs://', 'https://', $string);
        $tags = array('list' => '<ul>', 'num' => '<ol>', 'quote' => '<div class="quote"><pre>',);
        foreach ($tags as $tag => $val) {
            if ($tag == 'list') {
                $re = '@\\\\(list)({)(.*?)([^\\*]})@sim';
                $vt = preg_match_all($re, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($re, "$val\$3</ul>", $string, 1);
                    $withBreaks = str_replace('{*}', '<li>&nbsp;', $match[3][$i]);
                    $string = str_replace($match[3][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'num') {
                $re = '@\\\\(num)({)(.*?)([^\\*]})@sim';
                $vt = preg_match_all($re, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($re, "$val\$3</ol>", $string, 1);
                    $withBreaks = str_replace('{*}', '<li>&nbsp;', $match[3][$i]);
                    $string = str_replace($match[3][$i], $withBreaks, $string);
                }
            }
            if ($tag == 'quote') {
                $re = '@\\\\(quote)({)(.*?[^}]?)(})@sim';
                $vt = preg_match_all($re, $string, $match);
                for ($i = 0; $i < $vt; $i++) {
                    $string = preg_replace($re, "$val\$3</pre></div>", $string, 1);
                    $withBreaks = preg_replace('#^(\\r\\n)+#', '', $match[3][$i]);
                    $withBreaks = preg_replace('/(\\r\\n)+$/', '', $withBreaks);
                    $string = str_replace($match[3][$i], $withBreaks, $string);
                }
            }
        }
        $tags1 = array(
            'center' => '<p align="center">',
            'flushleft' => '<p align="left">',
            'flushright' => '<p align="right">',
        );
        foreach ($tags1 as $tag1 => $val1) {
            $re = '#(\\\\begin{' . $tag1 . '})(.*?[^\\\\end{' . $tag1 . '}]?)(\\\\end{' . $tag1 . '})#sim';
            if ($tag1 == 'center' || $tag1 == 'flushleft' || $tag1 == 'flushright') {
                $vh = preg_match_all($re, $string, $match);
                for ($i = 0; $i < $vh; $i++) {
                    $string = preg_replace($re, $val1 . '$2</p>', $string, 1);
                    $withBreaks = str_replace("\\", '<br>', $match[2][$i]);
                    $withBreaks = str_replace("\n", ' ', $withBreaks);
                    $string = str_replace($match[2][$i], $withBreaks, $string);
                }
            }
        }
        $user_re = "#(\\\\user{)(.*?[^}]?)(})#sim";
        $arr = preg_match_all($user_re, $string, $match);
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $this->entityManager->getRepository('RLMainBundle:User');
        for ($i = 0; $i < $arr; $i++) {
            $user = $userRepository->findOneByUsername($match[2][$i]);
            if (null !== $user) {
                $string = preg_replace($user_re, "<b><a href=\"/user/\$2\">\$2</a></b>", $string, 1);
            } else {
                $string = preg_replace($user_re, "\$2", $string, 1);
            }
        }
        $url_re = '#(\\\\url)(\\[)?(.*?[^\\]]?)(\\])?({)(.*?[^}]?)(})#sim';
        $vt = preg_match_all($url_re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[6][$i], FILTER_VALIDATE_URL)) {
                if (empty($match[3][$i])) {
                    $string = preg_replace($url_re, "<a href=\"\$6\">\$6</a>", $string, 1);
                } else {
                    $string = preg_replace($url_re, "<a href=\"\$6\">\$3</a>", $string, 1);
                }
            }
        }
        $img_re = '#(\\\\img)(\\[?) ?(left|right|middle|top|bottom)? ?(\\])?{(.*?[^}]?)(})#sim';
        $vt = preg_match_all($img_re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $imageinfo = getimagesize($match[5][$i]);
            if ($imageinfo[0] > 1024) {
                if (!empty($match[3][$i])) {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$5\" align=\"$3\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$5\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            } else {
                if (!empty($match[3][$i])) {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$5\" align=\"$3\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$5\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            }
        }
        $string = '<p>' . $string . '</p>';
        $string = preg_replace("#(\r\n\r\n|<p>|^)(>|&gt;)(.*?[^\n]?)(\n|$)#sim", "\$1<i>>\$3</i><br>", $string);
        $string = str_replace("\r\n\r\n", '<br><br>', $string);
        $string = str_replace("\r\n", ' ', $string);
        $re = "#(⓬)([0-9]+)(⓬)#sim";
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = str_replace(
                '⓬' . $match[2][$i] . '⓬',
                    '<fieldset><legend>' . $lang[$match[2][$i]] . '</legend>' . $code[$match[2][$i]] . '</fieldset>',
                $string
            );
        }
        $re = "#(ᴥ)([0-9]+)(ᴥ)#suim";
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = str_replace('ᴥ' . $match[2][$i] . 'ᴥ', $math[$match[2][$i]], $string);
        }

        return $string;
    }
}

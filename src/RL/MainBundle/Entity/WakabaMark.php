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
 * RL\MainBundle\Entity\WakabaMark
 *
 * @ORM\Entity
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
final class WakabaMark extends Mark
{
    /**
     * @param $string
     * @return mixed|string
     */
    public function render($string)
    {
        $code = array();
        $lang = array();
        $re = '#(``)(@(' . implode('|', $this->geshi->getHighlightedLanguagesList()) . ')@)?(.*?[^``]?)(``)#sim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $lang[$i] = $match[2][$i];
            $withBreaks = $this->geshi->highlight(
                html_entity_decode($match[4][$i], ENT_QUOTES),
                $match[3][$i]
            );
            $code[$i] = $withBreaks;
            $string = str_replace($match[0][$i], '⓬' . $i . '⓬', $string);
        }
        $re = '#(\\{\\{)(.*?)(\\}\\})#suim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $withBreaks = $this->math->render($match[2][$i]);
            $math[$i] = $withBreaks;
            $string = str_replace($match[0][$i], 'ᴥ' . $i . 'ᴥ', $string);
        }
        $string = htmlspecialchars($string);
        $string = str_replace('\\', '&#92;', $string);
        $string = '<p>' . $string . '</p>';
        $string = preg_replace("#(\\*\\*)(.*?(^\\*\\*)?)(\\*\\*)#sim", "<b>\$2</b>", $string);
        $string = preg_replace("#(%%)(.*?(^%%)?)(%%)#sim", "<span class=\"spoiler\">\$2</span>", $string);
        $string = preg_replace("#(__)(.*?(^__)?)(__)#sim", "<b>\$2</b>", $string);
        $string = preg_replace("#(\\*)([^ ].*?(^\\*)?)(\\*)#sim", "<i>\$2</i>", $string);
        $string = preg_replace("#(_)(.*?(^_)?)(_)#sim", "<i>\$2</i>", $string);
        $string = preg_replace("#(\\$)(.*?(^\\$)?)(\\$)#sim", "<u>\$2</u>", $string);
        $string = preg_replace("#( |&nbsp;|<p>)([A-zА-я0-9<&;>/]*)(\\^W)#suim", "\$1<s>\$2</s>", $string);
        $string = preg_replace("@(##)(.*?([^##])?)(##)@sim", "<sub>\$2</sub>", $string);
        $string = preg_replace("@(#)(.*?(^#)?)(#)@sim", "<sup>\$2</sup>", $string);
        $string = str_replace('imgh://', 'http://', $string);
        $string = str_replace('imghs://', 'https://', $string);
        $string = preg_replace("#(\\* |\\+ |- ){2,}+#", "$1", $string);
        $re = '#(\\* |\\+ |- )(.*?[^((\\r\\n){2,}|</p>)]?)((\\r\\n){2,}|</p>)#sim';
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($re, "<ul><li>&nbsp;$2</ul>", $string, 1);
            $withBreaks = preg_replace('/(\\r\\n)(\\* |\\+ |- )/', '<li>&nbsp;', $match[2][$i]);
            $string = str_replace($match[2][$i], $withBreaks, $string);
        }
        $string = preg_replace("#([0-9]\\. ){2,}+#", "$1", $string);
        $re = '#([0-9]\\. )(.*?[^((\\r\\n){2,}|</p>)]?)((\\r\\n){2,}|</p>)#sim';
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($re, "<ol><li>&nbsp;$2</ol>", $string, 1);
            $withBreaks = preg_replace('/(\\r\\n)([0-9]\\. )/', '<li>&nbsp;', $match[2][$i]);
            $string = str_replace($match[2][$i], $withBreaks, $string);
        }
        $string = preg_replace(
            "#(>>|&gt;&gt;)(.*?(^>>|&gt;&gt;)?)(>>|&gt;&gt;)#sim",
            "<p align=\"right\">\$2</p>",
            $string
        );
        $string = preg_replace(
            "#(<<|&lt;&lt;)(.*?(^<<|&lt;&lt;)?)(<<|&lt;&lt;)#sim",
            "<p align=\"left\">\$2</p>",
            $string
        );
        $string = preg_replace(
            "#(<>|&lt;&gt;)(.*?(^<>|&lt;&gt;)?)(<>|&lt;&gt;)#sim",
            "<p align=\"center\">\$2</p>",
            $string
        );
        $quote = array();
        $re = '#(`)([^`].*?[^`]?)(`)#sim';
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($re, "<div class=\"quote\"><pre>\$2</pre></div>", $string, 1);
            $withBreaks = preg_replace('/^(\\r\\n)+/', '', $match[2][$i]);
            $withBreaks = preg_replace('/(\\r\\n)+$/', '', $withBreaks);
            //$with_breaks = preg_replace('/\n/', '<li>&nbsp;', $with_breaks);
            $quote[$i] = $withBreaks;
            $string = str_replace($match[2][$i], '⓬⓬' . $i . '⓬⓬', $string);
        }
        $userRegexp = "#(\\^)(.*?[^\\^]?)(\\^)#sim";
        $arr = preg_match_all($userRegexp, $string, $match);
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $this->entityManager->getRepository('RLMainBundle:User');
        for ($i = 0; $i < $arr; $i++) {
            $user = $userRepository->findOneByUsername($match[2][$i]);
            if (null !== $user) {
                $userUrl = $this->router->generate('user', array("name" => $match[2][$i]));
                $string = preg_replace(
                    $userRegexp,
                        '<b><a href="' . $userUrl . '">' . $match[2][$i] . '</a></b>',
                    $string,
                    1
                );
            } else {
                $string = preg_replace($userRegexp, "\$2", $string, 1);
            }
        }
        $urlRegexp = '#(~)((@)(.*?[^@]?)(@))?(.*?[^~]?)(~)#sim';
        $vt = preg_match_all($urlRegexp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[6][$i], FILTER_VALIDATE_URL)) {
                if (empty($match[4][$i])) {
                    $string = preg_replace($urlRegexp, "<a href=\"\$6\">\$6</a>", $string, 1);
                } else {
                    $string = preg_replace($urlRegexp, "<a href=\"\$6\">\$4</a>", $string, 1);
                }
            }
        }
        $img_re = '#(~~)((@)(left|right|middle|top|bottom)(@))?(.*?[^~]{2}?)(~~)#sim';
        $vt = preg_match_all($img_re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $imageInfo = getimagesize($match[5][$i]);
            if ($imageInfo[0] > 1024) {
                if (!empty($match[3][$i])) {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$6\" align=\"$4\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$6\" width=\"1024\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            } else {
                if (!empty($match[4][$i])) {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$6\" align=\"$4\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                } else {
                    $string = preg_replace(
                        $img_re,
                        "<img src=\"\$6\" alt=\"[incorrect path to image]\" />",
                        $string,
                        1
                    );
                }
            }
        }
        $string = preg_replace("#(\r\n|<p>|^)(>|&gt;)(.*?[^\n]?)(\n|$)#sim", "\$1<i>>\$3</i><br>", $string);
        $string = preg_replace("#(\r\n)+#", '<br>', $string);
        $re = "#(⓬⓬)([0-9]+)(⓬⓬)#sim";
        $vt = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = str_replace('⓬⓬' . $match[2][$i] . '⓬⓬', $quote[$match[2][$i]], $string);
        }
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

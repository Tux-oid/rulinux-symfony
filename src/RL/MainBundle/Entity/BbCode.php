<?php
/**
 * Copyright (c) 2009 - 2012, Peter Vasilevsky
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
 * RL\MainBundle\Entity\BbCode
 *
 * @ORM\Entity
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
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
        $codeRegExp = '#(\\[code)=?(' . implode(
            '|',
            $this->geshi->getHighlightedLanguagesList()
        ) . ')?(\\])(.*?[^\\[/code\\]]?)(\\[/code\\])#sim';
        $arr = preg_match_all($codeRegExp, $string, $match);
        for ($i = 0; $i < $arr; $i++) {
            $lang[$i] = $match[2][$i];
            $withBreaks = $this->geshi->highlight(html_entity_decode($match[4][$i], ENT_QUOTES), $match[2][$i]);
            $code[$i] = $withBreaks;
            $string = str_replace($match[0][$i], '⓬' . $i . '⓬', $string);
        }
        $math = array();
        $regExp = '#(\\[math\\])(.*?)(\\[/math\\])#suim';
        $vh = preg_match_all($regExp, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $withBreaks = $this->math->render($match[2][$i]);
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
            if (!empty($match[3][$i])) {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$5\" align=\"$3\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                    );
            } else {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$5\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                    );
            }
        }
        $userRegExp = "#(\\[user\\])(.*?[^\\[/user\\]]?)(\\[/user\\])#sim";
        $arr = preg_match_all($userRegExp, $string, $match);
        /** @var $userRepository \RL\MainBundle\Entity\Repository\UserRepository */
        $userRepository = $this->entityManager->getRepository('RLMainBundle:User');
        for ($i = 0; $i < $arr; $i++) {
            $user = $userRepository->findOneByUsername($match[2][$i]);
            if (null !== $user) {
                $userUrl = $this->router->generate('user', array("name" => $match[2][$i]));
                $string = preg_replace(
                    $userRegExp,
                        '<b><a href="' . $userUrl . '">' . $match[2][$i] . '</a></b>',
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

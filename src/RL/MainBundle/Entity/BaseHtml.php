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
 * RL\MainBundle\Entity\BaseHtml
 *
 * @ORM\Entity
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
*/
class BaseHtml extends Mark
{
    /**
     * @param $string
     * @return mixed|string
     */
    public function render($string)
    {
        $code = array();
        $lang = array();
        $re = '#(<code)([ ]?lang=["]?('.implode('|', $this->geshi->getHighlightedLanguagesList()).')["]?)?(>)((?!</code>).*?)(</code>)#suim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $lang[$i] = $match[2][$i];
            $withBreaks = $this->geshi->highlight(
                html_entity_decode($match[5][$i], ENT_QUOTES),
                $match[3][$i]
            );
            $code[$i] = $withBreaks;
            $string = str_replace($match[0][$i], '⓬' . $i . '⓬', $string);
        }
        $math = array();
        $re = '#(<m>)(.*?)(</m>)#suim';
        $vh = preg_match_all($re, $string, $match);
        for ($i = 0; $i < $vh; $i++) {
            $withBreaks = $this->math->render($match[2][$i]);
            $math[$i] = $withBreaks;
            $string = str_replace($match[0][$i], 'ᴥ' . $i . 'ᴥ', $string);
        }
        $string = htmlspecialchars($string);
        $string = str_replace('\\', '&#92;', $string);
        $string = preg_replace("#(&lt;b&gt;)(.*?[^&lt;/b&gt;]?)(&lt;/b&gt;)#suim", "<b>\$2</b>", $string);
        $string = preg_replace(
            "#(&lt;span class=&quot;spoiler&quot;&gt;)((?!&lt;/span&gt).*?)(&lt;/span&gt;)#suim",
            "<span class=\"spoiler\">\$2</span>",
            $string
        );
        $string = preg_replace("#(&lt;i&gt;)(.*?[^&lt;/i&gt;]?)(&lt;/i&gt;)#suim", "<i>\$2</i>", $string);
        $string = preg_replace("#(&lt;u&gt;)(.*?[^&lt;/u&gt;]?)(&lt;/u&gt;)#suim", "<u>\$2</u>", $string);
        $string = preg_replace("#(&lt;s&gt;)(.*?[^&lt;/s&gt;]?)(&lt;/s&gt;)#suim", "<s>\$2</s>", $string);
        $string = preg_replace("#(&lt;sub&gt;)(.*?[^&lt;/sub&gt;]?)(&lt;/sub&gt;)#suim", "<sub>\$2</sub>", $string);
        $string = preg_replace("#(&lt;sup&gt;)(.*?[^&lt;/sup&gt;]?)(&lt;/sup&gt;)#suim", "<sup>\$2</sup>", $string);
        $string = str_replace('imgh://', 'http://', $string);
        $string = str_replace('imghs://', 'https://', $string);
        $string = preg_replace("#(&lt;) ?(br) ?/?(&gt;)#suim", "<br>", $string);
        $quoteRegExp = "#(&lt;q&gt;)(.*?(?!&lt;q))(&lt;/q&gt;)#suim";
        $vt = preg_match_all($quoteRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($quoteRegExp, "<div class=\"quote\"><pre>\$2</pre></div>", $string, 1);
            $withBreaks = preg_replace('/^(\\r\\n)+/', '', $match[2][$i]);
            $withBreaks = preg_replace('/(\\r\\n)+$/', '', $withBreaks);
            $string = str_replace($match[2][$i], $withBreaks, $string);
        }
        $list_re = "#(&lt;ul&gt;)(.*?(?!ul&gt;))(&lt;/ul&gt;)#suim";
        $vt = preg_match_all($list_re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($list_re, "<ul>\$2</ul>", $string, 1);
            $withBreaks = preg_replace('/^(\\r\\n)+/', '', $match[2][$i]);
            $withBreaks = preg_replace('/(\\r\\n)+$/', '', $withBreaks);
            $withBreaks = preg_replace('#&lt;li&gt;#suim', '<li>&nbsp;', $withBreaks);
            $string = str_replace($match[2][$i], $withBreaks, $string);
        }
        $num_re = "#(&lt;ol&gt;)(.*?(?!ol&gt;))(&lt;/ol&gt;)#suim";
        $vt = preg_match_all($num_re, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            $string = preg_replace($num_re, "<ol start=\"1\">\$2</ol>", $string, 1);
            $withBreaks = preg_replace('/^(\\r\\n)+/', '', $match[2][$i]);
            $withBreaks = preg_replace('/(\\r\\n)+$/', '', $withBreaks);
            $withBreaks = preg_replace('#&lt;li&gt;#suim', '<li>&nbsp;', $withBreaks);
            $string = str_replace($match[2][$i], $withBreaks, $string);
        }
        $string = preg_replace(
            '#(&lt;p align=&quot;)(left|right|center)(&quot;&gt;)(.*?(^/p&gt;)?)(&lt;/p&gt;)#suim',
            "<p align=\"\$2\">\$4</p>",
            $string
        );
        $imgRegExp = '#(&lt;img) ?(align=&quot;)?(left|right|middle|top|bottom)?(&quot;)?(src=&quot;)((?!&quot;).*?)(&quot;&gt;)#suim';
        $vt = preg_match_all($imgRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (!empty($match[3][$i])) {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$6\" align=\"$3\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                    );
            } else {
                $string = preg_replace(
                    $imgRegExp,
                    "<img src=\"\$6\" style=\"max-width: 1024px;\" alt=\"[incorrect path to image]\" />",
                    $string,
                    1
                    );
            }
        }
        $userRegExp = "#(&lt;span class=&quot;user&quot;&gt;)((?!&lt;/span&gt;).*?)(&lt;/span&gt;)#suim";
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
        $urlRegExp = '#(&lt;a href=&quot;)((?!&quot;).*?)(&quot;&gt;)((?!&lt;/a&gt;).*?)(&lt;/a&gt;)#suim';
        $vt = preg_match_all($urlRegExp, $string, $match);
        for ($i = 0; $i < $vt; $i++) {
            if (filter_var($match[2][$i], FILTER_VALIDATE_URL)) {
                $string = preg_replace($urlRegExp, "<a href=\"\$2\">\$4</a>", $string);
            }
        }
        $string = '<p>' . $string . '</p>';
        $re = "#(⓬)([0-9]+)(⓬)#suim";
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

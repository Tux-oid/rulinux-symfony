<?php
/**
 * Copyright (c) 2008 - 2013, Peter Vasilevsky
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

namespace RL\MainBundle\Helper;

/**
 * RL\MainBundle\Helper\FilterRuHelper
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FilterRuHelper
{
    /**
     * @var array
     */
    private static $src = array(
        'A',
        '6',
        'B',
        '8',
        '9',
        'E',
        'Ё',
        '3',
        'Й',
        'K',
        'M',
        'H',
        'O',
        '0',
        'P',
        'C',
        'T',
        'Y',
        'X',
        '4',
        'a',
        '6',
        'b',
        '8',
        '9',
        'e',
        'ё',
        '3',
        'й',
        'k',
        'm',
        'h',
        'o',
        '0',
        'p',
        'c',
        't',
        'y',
        'x',
        '4'
    );

    /**
     * @var array
     */
    private static $trgt = array(
        'А',
        'Б',
        'В',
        'В',
        'Д',
        'Е',
        'Е',
        'З',
        'И',
        'К',
        'М',
        'Н',
        'О',
        'О',
        'Р',
        'С',
        'Т',
        'У',
        'Х',
        'Ч',
        'а',
        'б',
        'в',
        'в',
        'д',
        'е',
        'е',
        'з',
        'и',
        'к',
        'м',
        'н',
        'о',
        'о',
        'р',
        'с',
        'т',
        'у',
        'х',
        'ч'
    );

    /**
     * @param $message
     * @return mixed
     */
    public static function prepare($message)
    {
        return str_replace(self::$src, self::$trgt, $message);
    }
}

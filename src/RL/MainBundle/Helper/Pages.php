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

namespace RL\MainBundle\Helper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;

/**
 * RL\MainBundle\Helper\Pages
 *
 * Service("rl_main.paginator")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class Pages
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;

    /**
     * Constructor
     *
     * @InjectParams({
     * "router" = @Inject("router"),
     * })
     *
     * @param $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function draw($itemsOnPage, $itemsCount, $page, $routeName, array $routeParameters)
    {
        $pagesCount = ceil(($itemsCount) / $itemsOnPage);
        $pages = '';
        if ($pagesCount > 1) {
            if ($page > 1) {
                $pg = $page - 1;
                $routeParameters['page'] = 1;
                $pages = $pages . '<a href="' . $this->router->generate(
                    $routeName,
                    $routeParameters
                ) . '" title="First page">←</a>&nbsp;';
                $routeParameters['page'] = $pg;
                $pages = $pages . '<a href="' . $this->router->generate(
                    $routeName,
                    $routeParameters
                ) . '" title="Backward">≪</a>&nbsp;';
            }
            if ($pagesCount > 10) {
                if ($page < 5) {
                    $startPage = 1;
                } else {
                    $startPage = $page - 4;
                }

                if ($page > $pagesCount - 4) {
                    $endPage = $pagesCount;
                } else {
                    $endPage = $page + 4;
                }
                for ($p = $startPage; $p <= $endPage; $p++) {
                    $routeParameters['page'] = $p;
                    if ($p == $page) {
                        $pages = $pages . '<b>' . ($p) . '</b>&nbsp;';
                    } else {
                        $pages = $pages . '<a href="' . $this->router->generate(
                            $routeName,
                            $routeParameters
                        ) . '" title="Page №' . $p . '">' . ($p) . '</a>&nbsp;';
                    }
                }
            } else {
                for ($p = 1; $p <= $pagesCount; $p++) {
                    $routeParameters['page'] = $p;
                    if ($p == $page) {
                        $pages = $pages . '<b>' . ($p) . '</b>&nbsp;';
                    } else {
                        $pages = $pages . '<a href="' . $this->router->generate(
                            $routeName,
                            $routeParameters
                        ) . '" title="Page №' . $p . '">' . ($p) . '</a>&nbsp;';
                    }
                }
            }
            if ($page < $pagesCount) {
                $pg = $page + 1;
                $routeParameters['page'] = $pg;
                $pages = $pages . '<a href="' . $this->router->generate(
                    $routeName,
                    $routeParameters
                ) . '" title="Forward">≫</a>&nbsp;';
                $routeParameters['page'] = $pagesCount;
                $pages = $pages . '<a href="' . $this->router->generate(
                    $routeName,
                    $routeParameters
                ) . '" title="End page">→</a>&nbsp;';
            }
            $pages = '<div class="pages">' . $pages . '</div>';

            return $pages;
        } else {
            return '';
        }
    }

}

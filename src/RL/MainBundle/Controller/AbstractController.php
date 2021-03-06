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

namespace RL\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use RL\MainBundle\Security\User\RLUserInterface;
use \Symfony\Component\Security\Core\SecurityContext;
use RL\MainBundle\Service\PaginatorService;
use Symfony\Component\HttpFoundation\Session\Session;
use RL\MainBundle\Service\MailerService;
use RL\MainBundle\Service\MessageFilterCheckerService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use RL\MainBundle\Service\UcaptchaDecoratorService;

/**
 * RL\MainBundle\Controller\Controller
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
abstract class AbstractController extends BaseController
{
    /**
     * @var \RL\MainBundle\Theme\ThemeProvider
     */
    public $theme;

    /**
     * @param $title
     * @param $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderMessage($title, $message)
    {
        return $this->render(
            'RLMainBundle:Abstract:fieldset.html.twig',
            array(
                'title' => $title,
                'text' => $message,
            )
        );
    }

    /**
     * @param string $view
     * @param array $parameters
     * @return string
     */
    public function renderView($view, array $parameters = array())
    {
        return parent::renderView($this->theme->getPath($view), $parameters);
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($this->theme->getPath($view), $parameters, $response);
    }

    /**
     * @return RLUserInterface
     */
    public function getCurrentUser()
    {
        return $this->getSecurityContext()->getToken()->getUser();
    }

    /**
     * @return SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->get('security.context');
    }

    /**
     * @return PaginatorService
     */
    public function getPaginator()
    {
        return $this->get('rl_main.paginator');
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * @return MailerService
     */
    public function getMailer()
    {
        return $this->get('rl_main.mailer');
    }

    /**
     * @return MessageFilterCheckerService
     */
    public function  getMessageFilterChecker()
    {
        return $this->get('rl_main.message_filter_checker');
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->get('router');
    }

    /**
     * @return UcaptchaDecoratorService
     */
    public function getUcaptcha()
    {
        return $this->get('rl_main.ucaptcha');
    }
}

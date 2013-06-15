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

namespace RL\MainBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Doctrine\Bundle\DoctrineBundle\Registry;
use RL\MainBundle\Entity\Repository\MessageRepository;
use RL\MainBundle\Entity\Repository\WordRepository;
use RL\MainBundle\Entity\Repository\FilterRepository;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\FilteredMessage;
use RL\MainBundle\Helper\FilterRuHelper;

/**
 * RL\MainBundle\Service\MessageFilterService
 *
 * @Service("rl_main.message_filter_checker")
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class MessageFilterCheckerService
{
    /**
     * @var MessageRepository
     */
    protected $messageRepository;

    /**
     * @var WordRepository
     */
    protected $wordRepository;

    /**
     * @var FilterRepository
     */
    protected $filterRepository;

    /**
     *
     * @InjectParams({
     * "doctrine" = @Inject("doctrine")
     * })
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->wordRepository = $doctrine->getRepository("RLMainBundle:Word");
        $this->messageRepository = $doctrine->getRepository("RLMainBundle:Message");
        $this->filterRepository = $doctrine->getRepository("RLMainBundle:Filter");
    }

    public function filter(Message $message = null)
    {
        if (null === $message) {
            $messages = $this->messageRepository->getUnfilteredMessages();
        } else {
            /** @var $filter FilteredMessage */
            foreach($message->getFilters() as $filter) {
                $filter->getFilter()->removeMessage($filter);
                $filter->getMessage()->removeFilter($filter);
                $this->filterRepository->remove($filter, false);
            }
            $messages = array($message);
        }
        $filters = $this->filterRepository->findAll();
        /** @var $message \RL\MainBundle\Entity\Message */
        foreach ($messages as $message) {
            /** @var $filter \RL\MainBundle\Entity\Filter */
            foreach ($filters as $filter) {
                if (!$filter->isFilterByHtmlTags()) {
                    $comment = strip_tags($message->getComment());
                } else {
                    $comment = $message->getComment();
                }
                $comment .= ' ' . $message->getSubject();
                $weight = 0;
                $wordsCount = 0;
                foreach (preg_split(
                             "#[ \.,\?\\\(\)\[\]{}\"';:\!\=\+\-\#\$\%\^\&\*\\r\\n\\t]#suim",
                             $comment
                         ) as $word) {
                    /** @var $word \RL\MainBundle\Entity\Word */
                    $word = $this->wordRepository->findOneBy(
                        array("word" => FilterRuHelper::prepare($word), "filter" => $filter)
                    );
                    if (null !== $word) {
                        $weight += $word->getWeight();
                        $wordsCount++;
                    }
                }
                if ($wordsCount != 0) {
                    $weight = $weight / $wordsCount;
                }
                $message->addFilter(new FilteredMessage($filter, $weight));
            }
        }
        $this->messageRepository->flush();
    }
}

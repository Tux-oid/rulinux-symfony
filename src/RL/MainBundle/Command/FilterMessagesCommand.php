<?php
/**
 * Copyright (c) 2009 - 2013, Peter Vasilevsky
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

namespace RL\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use RL\MainBundle\Entity\Filter;
use RL\MainBundle\Entity\Word;
use RL\MainBundle\Entity\Message;
use RL\MainBundle\Entity\FilteredMessage;

/**
 * RL\MainBundle\Command\FilterMessagesCommand
 *
 * Filers messages
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
 */
class FilterMessagesCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this->setName('rl_main:messages:filter')->setDescription('Filters messages')->setHelp(
            "The <info>rl_main:messages:filter</info> command filters new posted messages.
                    \n\n<info>php app/console rl_main:messages:filter</info>"
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->getContainer()->get('doctrine');
        /** @var $messagesRepository \RL\MainBundle\Entity\Repository\MessageRepository */
        $messagesRepository = $doctrine->getRepository('RLMainBundle:Message');
        /** @var $wordsRepository \Doctrine\ORM\EntityRepository */
        $wordsRepository = $doctrine->getRepository('RLMainBundle:Word');
        $filters = $doctrine->getRepository('RLMainBundle:Filter')->findAll();
        /** @var $message \RL\MainBundle\Entity\Message */
        foreach ($messagesRepository->getUnfilteredMessages() as $message) {
            /** @var $filter \RL\MainBundle\Entity\Filter */
            foreach ($filters as $filter) {
                if($filter->isFilterByTags()) {
                    $comment = strip_tags($message->getComment());
                } else {
                    $comment = $message->getComment();
                }
                $weight = 0;
                $wordsCount = 0;
                foreach(preg_split("#[ \.,\?\\\(\)\[\]{}\"';:\!\=\+\-\#\$\%\^\&\*\\r\\n\\t]#suim", $comment) as $word) {
                    /** @var $word \RL\MainBundle\Entity\Word */
                    $word = $wordsRepository->findOneBy(array("word" => $word, "filter" => $filter));
                    if(null !== $word) {
                        $weight += $word->getWeight();
                        $wordsCount++;
                    }
                }
                if($wordsCount != 0) {
                    $weight = $weight/$wordsCount;
                }
                $message->addFilter(new FilteredMessage($filter, $weight));
            }
        }
        $doctrine->getManager()->flush();
        $output->writeln('Done.');
    }
}

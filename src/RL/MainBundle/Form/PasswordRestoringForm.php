<?php
/**
 * @author Tux-oid
 */

namespace RL\MainBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordRestoringForm
{
    /**
     * @Assert\NotBlank()
     * @Assert\Regex("#([a-zA-Z0-9\_\-\/\.]{2,})$#")
     */
    protected $username;
    /**
     * @Assert\NotBlank()
     * @Assert\Email
     */
    protected $email;
    /**
     * Assert\NotBlank()
     */
    protected $question;
    /**
     * @Assert\NotBlank()
     */
    protected $answer;
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getQuestion()
    {
        return $this->question;
    }
    public function setQuestion($question)
    {
        $this->question = $question;
    }
    public function getAnswer()
    {
        return $this->answer;
    }
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}

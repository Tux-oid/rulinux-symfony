<?php
/**
 * @author Tux-oid
 */

namespace RL\GalleryBundle\Form;
use Symfony\Component\Validator\Constraints as Assert;
use RL\ForumBundle\Form\AddThreadForm as ForumAddThreadForm;

class AddThreadForm extends ForumAddThreadForm
{
    /**
     * @Assert\NotBlank()
     * @Assert\File(maxSize="700000")
     */
    protected $file;
    public function __construct($user)
    {
        parent::__construct($user);
    }
    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
    }

}

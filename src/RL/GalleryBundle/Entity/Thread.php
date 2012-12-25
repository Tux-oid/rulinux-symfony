<?php
/**
 * @author Tux-oid
 */

namespace RL\GalleryBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use RL\ArticlesBundle\Entity\Thread as ArticlesThread;
use Gregwar\ImageBundle\Image;

/**
 * @ORM\Entity(repositoryClass="RL\ArticlesBundle\Entity\ThreadRepository")
 * @ORM\Table(name="gallery")
 * @ORM\HasLifecycleCallbacks
 */
class Thread extends ArticlesThread
{
    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    protected $filename;
    /**
     * @ORM\Column(type="integer")
     */
    protected $fileSize = 0;
    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    protected $imageSize;
    protected $file;
    private $filenameForRemove;
    public function getFilename()
    {
        return $this->filename;
    }
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }
    public function getFileSize()
    {
        return $this->fileSize;
    }
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }
    public function getImageSize()
    {
        return $this->imageSize;
    }
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;
    }
    public function getAbsolutePath()
    {
        return null === $this->filename ? null : $this->getUploadRootDir().'/'.$this->filename;
    }
    public function getWebPath()
    {
        return null === $this->filename ? null : $this->getUploadDir().'/'.$this->filename;
    }
    public function getThumbAbsolutePath()
    {
        return null === $this->filename ? null : $this->getUploadRootDir().'/thumbs/'.$this->filename;
    }
    public function getThumbWebPath()
    {
        return null === $this->filename ? null : $this->getUploadDir().'/thumbs/'.$this->filename;
    }
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../'.$this->getUploadDir();
    }
    protected function getUploadDir()
    {
        return 'web/bundles/rlgallery/images/gallery';
    }
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }
    /**
     *  @ORM\PrePersist()
     */
    public function upload()
    {
        if(null === $this->file)

            return;
        $this->file->move($this->getUploadRootDir(), \md5(time().$this->file->getClientOriginalName()).'_'.$this->file->getClientOriginalName());
        $this->filename = \md5(time().$this->file->getClientOriginalName()).'_'.$this->file->getClientOriginalName();
        $this->fileSize = $this->file->getClientSize();
        unset($this->file);
        $imgCls = new Image();
        $image = $imgCls->open($this->getAbsolutePath());
        $bigWidth = $image->width();
        $bigHeight = $image->height();
        if ($bigWidth > 2048 || $bigHeight > 2048) {//FIXME:get image size from settings
            unlink($this->getAbsolutePath());
            throw new \Exception('Image size is very big.');
        }
        $coef = $bigWidth/200;
        $width = 200;
        $heigth = $bigHeight/$coef;
        $image->resize($width, $heigth)
        ->save($this->getUploadRootDir().'/thumbs/'.$this->filename);
        $this->imageSize = $bigWidth.'x'.$bigHeight;
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

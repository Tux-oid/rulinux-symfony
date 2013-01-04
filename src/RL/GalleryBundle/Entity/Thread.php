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

namespace RL\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RL\ArticlesBundle\Entity\Thread as ArticlesThread;
use Gregwar\ImageBundle\Image;

/**
 * RL\GalleryBundle\Entity\Thread
 *
 * @ORM\Entity(repositoryClass="RL\ArticlesBundle\Entity\ThreadRepository")
 * @ORM\Table(name="gallery")
 * @ORM\HasLifecycleCallbacks
 *
 * @author Peter Vasilevsky <tuxoiduser@gmail.com> a.k.a. Tux-oid
 * @license BSDL
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

<?php
/**
 * Created by PhpStorm.
 * User: eValor
 * Date: 2019-02-21
 * Time: 02:43
 */

namespace evalor\coRequests;

class CoPostFile
{
    protected $path;
    protected $name;
    protected $filename;
    protected $mimeType;
    protected $offset;
    protected $length;

    public function __construct($path, $name, $filename = null, $mimeType = null, $offset = 0, $length = 0)
    {
        $this->setPath($path);
        $this->setName($name);
        $this->setFilename($filename);
        $this->setMimeType($mimeType);
        $this->setOffset($offset);
        $this->setLength($length);
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * 转为数组格式
     * @return array
     */
    public function toArray()
    {
        return [
            'path' => $this->getPath(),
            'name' => $this->getName(),
            'mimeType' => $this->getMimeType(),
            'filename' => $this->getFilename(),
            'offset' => $this->getOffset(),
            'length' => $this->getLength(),
        ];
    }

}
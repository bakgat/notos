<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 14:27
 */

namespace Bakgat\Notos\Exceptions;


abstract class NotosException extends \Exception
{
    /** @var string */
    protected $id;
    /** @var string */
    protected $status;
    /** @var string */
    protected $title;
    /** @var string */
    protected $detail;

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Get the status
     *
     * @return int
     */
    public function getStatus()
    {
        return (int)$this->status;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'title' => $this->title,
            'detail' => $this->detail,
        ];
    }

    /**
     * Build the exception
     *
     * @param array $args
     * @return string
     */
    protected function build(array $args) {
        $this->id = array_shift($args);

        $error= config(sprintf('errors.%s', $this->id));

        $this->title = $error['title'];
        $this->detail = vsprintf($error['detail'], $args);

        return $this->detail;
    }
}
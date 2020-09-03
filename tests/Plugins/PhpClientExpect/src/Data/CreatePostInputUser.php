<?php

namespace Acme\MyApi\Data;


final class CreatePostInputUser
{
    /** @var string */
    public $id;
    /** @var string */
    public $email;

    /**
     * @param string $id
     * @param string $email
     */
    public function __construct($id, $email)
    {
        $this->id = $id;
        $this->email = $email;
    }
}
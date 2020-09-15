<?php

namespace CodeGen\Laravel\Data;


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

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['id'],
            $data['email']
        );
    }
}
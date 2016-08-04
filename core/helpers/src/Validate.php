<?php
/**
 * Contain Mvc\helpers\Validate
 * Validate form elements
 */

namespace Mvc\helpers;


class Validate
{
    private $post;
    private $field;
    private $error = false;
    private $error_messages = '';

    public function __construct()
    {
        $this->post = $_POST;
    }

    public function check($field)
    {
        $this->field = $field;
        $this->error = false;
        $this->error_messages = '';
        return $this;
    }

    public function required()
    {
        if (empty($this->post[$this->field]))
        {
            $this->error = true;
            $this->error_messages .= 'Field is required! ';
        }
        return $this;
    }

    public function min($val)
    {
        if (strlen($this->post[$this->field])<$val)
        {
            $this->error = true;
            $this->error_messages .= 'Field must have min ' .  $val . ' chars! ';
        }
        return $this;
    }

    public function max($val)
    {
        if (strlen($this->post[$this->field])>$val)
        {
            $this->error = true;
            $this->error_messages .= 'Field must have max ' .  $val . ' chars! ';
        }
        return $this;
    }

    public function allowed()
    {
        if (preg_match('/[^A-Za-z0-9._-]/', $this->post[$this->field]))
        {
            $this->error = true;
            $this->error_messages .= 'Allowed chars are A-Z a-z 0-9 . _ - ';
        }
        return $this;
    }

    public function equal($val1, $val2)
    {
        if ($val1 != $val2)
        {
            $this->error = true;
            $this->error_messages .= 'Confirm field is not equal! ';
        }
        return $this;
    }

    public function email()
    {
        if (!filter_var($this->post[$this->field], FILTER_VALIDATE_EMAIL))
        {
            $this->error = true;
            $this->error_messages .= 'Invalid email format! ';
        }
        return $this;
    }

    public function validate()
    {
        return $this->error;
    }

    public function getErrorMessage()
    {
        return $this->error_messages;
    }
}

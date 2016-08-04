<?php
/**
 * Contain Mvc\models\User
 * Handel DB query and results
 */

namespace Mvc\models;


use Mvc\app\Model;
use Mvc\helpers\Config;
use Mvc\helpers\Hash;
use Mvc\helpers\Validate;

class User extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
        $this->existing_columns = $this->db->getColumnNames($this->table);
    }

    public function getById($id)
    {
        $this->db_setConditions('id', $id);
        $this->db_setLimit(1);
        $results = $this->db_getResults();
        return (isset($results[0])) ? $results[0] : null;
    }

    public function create($data)
    {
        $sql = "INSERT INTO users ( username, password, email, active_hash, created_at ) VALUES ( :username, :password, :email, :active_hash, now() )";
        $query = $this->db->db->prepare( $sql );
        $query->execute( array(
            ':username' => $data['username'],
            ':password' => $data['password'],
            ':email' => $data['email'],
            ':active_hash' => $data['active_hash'] ) );
    }

    public function getLoggedUser()
    {
        $sql = "SELECT * FROM users WHERE username = :user OR email = :user";
        $query = $this->db->db->prepare( $sql );
        $query->execute( array( ':user'=>$_POST['user'] ) );
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return (count($result) == 1) ? $result[0] : null;
    }

    public function checkUnique()
    {
        $sql = "SELECT count(*) FROM users WHERE username = :username OR email = :email";
        $query = $this->db->db->prepare( $sql );
        $query->execute( array( ':username'=>$_POST['username'], ':email'=>$_POST['email'] ) );
        $number_of_rows = $query->fetchColumn();
        return (bool) ($number_of_rows == 0);
    }

    public function validateRegister()
    {
        $v = new Validate();
        $errors = array();
        if ($v->check('username')->required()->allowed()->min(2)->validate()) $errors['username'] = $v->getErrorMessage();
        if ($v->check('email')->required()->email()->validate()) $errors['email'] = $v->getErrorMessage();
        if ($v->check('password')->required()->min(6)->equal($_POST['password'], $_POST['confirm_password'])->validate()) $errors['password'] = $v->getErrorMessage();
        if (empty($errors)) if (!$this->checkUnique()) $errors['username'] = ' Username or email are not unique!';
        return $errors;
    }
    
    public function validateLogin()
    {
        $v = new Validate();
        $errors = array();
        if ($v->check('user')->required()->validate()) $errors['user'] = $v->getErrorMessage();
        if ($v->check('password')->required()->validate()) $errors['password'] = $v->getErrorMessage();
        return $errors;
    }

    public function sendActivationMail($email, $subject, $message)
    {
        $mailer = new \PHPMailer();

        //Set PHPMailer to use SMTP.
        $mailer->isSMTP();
        $mailer->Host = Config::getConfig('mail.host');
        $mailer->SMTPAuth = Config::getConfig('mail.smtp_auth');
        $mailer->SMTPSecure = Config::getConfig('mail.smtp_secure');
        $mailer->Port = Config::getConfig('mail.port');
        $mailer->Username = Config::getConfig('mail.username');
        $mailer->Password = Config::getConfig('mail.password');
        $mailer->isHTML(Config::getConfig('mail.html'));


        $mailer->From = '';
        $mailer->FromName = "";
        $mailer->addAddress($email);
        $mailer->Subject = "$subject";
        $mailer->Body = "$message";

        if(!$mailer->send())
        {
            $_SESSION['flash_message'] = "Mailer Error: " . $mailer->ErrorInfo;
        }
        else
        {
            $_SESSION['flash_message'] = 'Success! User ' . $_POST['username'] . ' is added! Activation link was send on your email address. Please activate account.';
        }
    }

    public function activateUser($email, $identifier)
    {
        $results = $this->db_setConditions('email', $email)->db_setConditions('active', 0)->db_getResults();
        $hashedIdentifier = Hash::hash($identifier);
        $_SESSION['flash_message'] = 'Something wrong is with this activation system!';
        if (isset($results[0]))
        {
            if (Hash::hashCheck($results[0]['active_hash'], $hashedIdentifier))
            {
                // Update status in database
                $sql = "UPDATE users SET active = :active, active_hash = NULL WHERE id = :id";
                $query = $this->db->db->prepare( $sql );
                $query->execute(array(':active' => 1, ':id' => $results[0]['id']));
                $_SESSION['flash_message'] = 'Account is active now!';
            }
        }
    }


    public function setUserFromCookie($cookie)
    {
        $cookie = explode('___', $cookie);
        if (count($cookie) == 2)
        {
            $identifier = $cookie[0];
            $id_token = $cookie[1];
            $results = $this->db_setConditions('remember_identifier', $identifier)
                ->db_setConditions('remember_token', Hash::hash($id_token))
                ->db_setLimit(1)
                ->db_getResults();
            if (isset($results[0]))
            {
                $_SESSION['user_id'] = $results[0]['id'];
                $_SESSION['username'] = $results[0]['username'];
                $_SESSION['role'] = ($results[0]['role']) ? $results[0]['role'] : 'user';
            }
        }
    }

    public function saveRememberCond($identifier, $token)
    {
        // Update status in database
        $sql = "UPDATE users SET remember_identifier = :identifier, remember_token = :token WHERE id = :id";
        $query = $this->db->db->prepare( $sql );
        $query->execute(array(':identifier' => $identifier, ':token' =>$token, ':id' => $_SESSION['user_id']));
    }
}
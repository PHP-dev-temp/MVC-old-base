<?php
/**
 * Contain Mvc\controllers\User
 * User controller
 */

namespace Mvc\controllers;

use Mvc\helpers\Hash;
use Mvc\models\User as UserModel;
use Mvc\app\Controller;
use Mvc\helpers\Config;

class User extends Controller
{
    private $user;
    private $all_routes;

    /**
     * User constructor.
     * @param array $route
     * @param array $all_routes
     */
    public function __construct(array $route, $all_routes = array())
    {
        parent::__construct($route);
        $this->user = new UserModel;
        $this->all_routes = $all_routes;
    }

    public function register()
    {
        $this->checkAuth();

        $this->data['POST'] = array();
        $this->data["self_uri"] = Config::getConfig('path') . $this->route['uri'];
        $this->data['login link'] = Config::getConfig('path') . $this->all_routes['login'];
        $this->data['scrf'] = Hash::randStrGen(128);
        $this->data['scrf.field'] = Config::getConfig('csrf.session');

        // Handle with POST data if exist
        if (isset($_POST['create']))$this->register_POST();
        $_SESSION['csrf.session'] = Hash::hash($this->data['scrf']);

        // Generate View from parts
        $this->data['view'] = array(
            $this->template_path . 'header.php',
            $this->default_method_path,
            $this->template_path . 'footer.php',
        );
    }

    public function register_sr()
    {
        $this->register();
    }

    public function register_POST()
    {
        if (!Hash::hashCheck(Hash::hash($_POST[Config::getConfig('csrf.session')]),$_SESSION['csrf.session']))
        {
            $_SESSION['flash_message'] = 'CSRF check failed!';

            // Redirect to home
            header( 'Location: ' . Config::getConfig('path') );
            exit;
        }
        $errors =  $this->user->validateRegister();
        $this->data['errors'] = $errors;
        $this->data['POST'] = $_POST;
        if (empty($errors))
        {
            $identifier = Hash::randStrGen(128);
            $_SESSION['activation link'] =
                $_SERVER['SERVER_NAME'] . Config::getConfig('path') . $this->all_routes['activate'] .
                '/' . $_POST['email'] . '/' . $identifier;
            $this->user->create(array(
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => Hash::password($_POST['password']),
                'active_hash' => Hash::hash($identifier),
            ));

            // Send activation mail
            ob_start();
            include COREROOT . DS . 'views' . DS . 'User' . DS . 'registration_mail.php';
            $body = ob_get_clean();
            unset ($_SESSION['activation link']);
            $this->user->sendActivationMail($_POST['email'], 'Account activation!', $body);
            $this->data['POST'] = array();

            // Redirect to home
            header( 'Location: ' . Config::getConfig('path') );
            exit;
        }
    }

    public function login()
    {
        $this->checkAuth();

        $this->data['POST'] = array();
        $this->data["self_uri"] = Config::getConfig('path') . $this->route['uri'];
        $this->data['register link'] = Config::getConfig('path') . $this->all_routes['register'];
        $this->data['scrf'] = Hash::randStrGen(128);
        $this->data['scrf.field'] = Config::getConfig('csrf.session');

        // Handle with POST data if exist
        if (isset($_POST['login']))$this->login_POST();
        $_SESSION['csrf.session'] = Hash::hash($this->data['scrf']);

        // Generate View from parts
        $this->data['view'] = array(
            $this->template_path . 'header.php',
            $this->default_method_path,
            $this->template_path . 'footer.php',
        );
    }

    public function login_sr()
    {
        $this->login();
    }

    public function login_POST()
    {
        if (!Hash::hashCheck(Hash::hash($_POST[Config::getConfig('csrf.session')]),$_SESSION['csrf.session']))
        {
            $_SESSION['flash_message'] = 'CSRF check failed!';

            // Redirect to home
            header( 'Location: ' . Config::getConfig('path') );
            exit;
        }
        $errors =  $this->user->validateLogin(); // da napravim ovu metodu
        $this->data['errors'] = $errors;
        $this->data['POST'] = $_POST;
        if (empty($errors))
        {
            $user = $this->user->getLoggedUser();
            if ($user)
            {
                if ((Hash::passwordCheck($_POST['password'], $user['password'])) && $user['active'])
                {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = ($user['role']) ? $user['role'] : 'user';
                    $_SESSION['flash_message'] = 'Welcome ' . $user['username'];
                    $this->data['POST'] = array();

                    if ($_POST['remember'] === 'on')
                    {
                        $rememberIdentifier = Hash::randStrGen(128);
                        $rememberToken = Hash::randStrGen(128);
                        $this->user->saveRememberCond($rememberIdentifier, Hash::hash($rememberToken));
                        setcookie(Config::getConfig('auth.remember'),
                            $rememberIdentifier . '___' . $rememberToken,
                            time() + (86400 * 7),
                            "/");
                    }
                    else
                    {
                        // Delete cookie
                        setcookie(Config::getConfig('auth.remember'), '', time() - 3600, "/");
                    }

                    // Redirect to home page
                    header('Location: ' . Config::getConfig('path'));
                    exit;
                }
                else
                {
                    $_SESSION['flash_message'] = 'Wrong username/email or password of active account!';
                }
            }
            else
            {
                $_SESSION['flash_message'] = 'Wrong username/email or password!';
            }

        }
    }

    public function logout() //TODO delete cookie
    {
        $this->checkAuth();

        if (isset($_SESSION['user_id']))
        {
            unset ($_SESSION['user_id']);
            unset ($_SESSION['username']);
            unset ($_SESSION['role']);
            $_SESSION['flash_message'] = 'Successfully logged out!';
            // Delete cookie
            setcookie(Config::getConfig('auth.remember'), '', time() - 3600, "/");
            $this->user->saveRememberCond(null, null);
        }
        header( 'Location: ' . Config::getConfig('path') );
        exit;
    }

    public function activate()
    {
        $this->checkAuth();

        $email = $this->route['params'][0];
        $identifier = $this->route['params'][1];
        $this->user->activateUser($email, $identifier);
        // Redirect to home page
        header('Location: ' . Config::getConfig('path'));
        exit;
    }
}
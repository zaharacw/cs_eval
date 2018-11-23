<?php if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class CAS
{
    var $CI;
    var $cas_enable = false;
    var $cas_host = '';
    var $cas_context = '';
    var $cas_port = '';
    var $cas_path = '';

    private static $initialized = false;

    /**
     * Constructor
     */
    public function __construct($config = array())
    {
        $this->CI =& get_instance();
        if (count($config) > 0)
        {
            $this->initialize($config);
        }

        require_once $this->cas_path;

        if (!self::$initialized)
        {
            phpCAS::client(SAML_VERSION_1_1, $this->cas_host, $this->cas_port, $this->cas_context);
            self::$initialized = true;
        }

        // Must be set no matter what
        phpCAS::setNoCasServerValidation();

        phpCAS::handleLogoutRequests();

        phpCAS::setCacheTimesForAuthRecheck(0);
    }

    /**
     * Initialize CodeIgniter variables. This allows the library to have access to them.
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }
    }

    /**
     * Wrapper for CAS forceAuthentication (EWU SSO).
     */
    public function force_authenticate()
    {
        if ($this->cas_enable)
        {
            phpCAS::forceAuthentication();
        }
    }

    /**
     * Global user validation. Checks if user is requesting a protected page.
     * If they are, attempt to verify them with CAS.
     * If verified but invalid user, show invalid user page.
     * If not verified show login gateway.
     */
    public function authenticate()
    {
        if ($this->cas_enable)
        {
            if (phpCAS::isAuthenticated())
            {
                $class = $this->CI->router->class;

                if (!$this->userIsAllowed($class) && $class != 'default_controller')
                {
                    redirect(base_url() . 'invalid_user');
                }
            }
            else
            {
                $this->CI->session->set_userdata('url', $this->get_current_url());
                $this->CI->session->set_userdata('class', $this->CI->router->class);
                redirect(base_url() . 'auth/redirect');
            }
        }
    }

    /**
     * @param $targetedController
     * @return mixed
     */
    private function userIsAllowed($targetedController)
    {
        foreach ($this->CI->homeControllers as $userType => $controller)
        {
            if ($targetedController == $controller && $this->CI->session->userdata($userType))
            {
                return true;
            }
        }

        foreach ($this->CI->nonHomeControllers as $userType => $controllers)
        {
            foreach ($controllers as $controller)
            {

                if ($targetedController == $controller && $this->CI->session->userdata($userType))
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Wrapper for CAS system logout (EWU SSO).
     */
    public function logout()
    {
        if ($this->cas_enable)
        {
            phpCAS::logout();
        }
    }


    /**
     * Pull the user's CAS user id (EWU NetId). If not set, return NULL.
     */
    public function get_user()
    {
        return ($this->cas_enable && phpCAS::isAuthenticated()) ? phpCAS::getUser() : null;
    }

    public function get_attributes()
    {
        return ($this->cas_enable && phpCAS::isAuthenticated()) ? phpCAS::getAttributes() : null;
    }

    public function has_attribute($key)
    {
        return ($this->cas_enable && phpCAS::isAuthenticated()) ? phpCAS::hasAttribute($key) : null;
    }

    /**
     * Utility to get the current page url. Used by this library in an attempt to save the users
     * requested page. The user is then sent back to this page upon a successful login.
     */
    private function get_current_url()
    {
        $pageURL = (@$_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

        if (@$_SERVER['SERVER_PORT'] != '80')
        {
            $pageURL .= @$_SERVER['SERVER_NAME'] . ':' . @$_SERVER['SERVER_PORT'] . @$_SERVER['REQUEST_URI'];
        }
        else
        {
            $pageURL .= @$_SERVER['SERVER_NAME'] . @$_SERVER['REQUEST_URI'];
        }

        return $pageURL;
    }
}
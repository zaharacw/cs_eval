<?php
function makeNavLink($name, $link, $active)
{
    echo '<li';
    if ($active)
    {
        echo ' class="active"';
    }
    echo '><a href="' . base_url() . $link . '">' . $name . '</a></li>';
}

function makeDropdown($title, $values, $class, $method)
{
    $active = in_array($class, $values);
    $listClass = ($active ? 'dropdown active' : 'dropdown');

    echo '<li class="' . $listClass . '"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $title . ' <span class="caret"></span></a><ul class="dropdown-menu" role="menu">';

    foreach ($values as $key => $value)
    {
        $active = ($class == $value);
        $link = $value;

        if ($key == 'Required Questions')
        {
            $link = 'questions/required';
        }
        else
        {
            if ($key == 'Course Questions')
            {
                $link = 'questions/course';
            }
        }

        makeNavLink($key, $link, $active);
    }

    echo '</ul></li>';
}

$controllers = array();
$class = $this->router->class;
$method = $this->router->method;
$userType = $this->session->userdata('groups');
$userType = $userType[0];

if ($userType == 'admin')
{
    $controllers = array(
        'Questions'          => array(
            'Required Questions' => 'required_questions',
            'Course Questions'   => 'course_questions'
        ),
        'Courses'            => array(
            'Upload Courses'        => 'upload',
            'Manage Courses'        => 'manage_course',
            'Set Evaluation Period' => 'evaluation_period'
        ),
        'Evaluations' => 'sample_evaluations',
        'Reports'            => 'reports',
        'Settings'           => 'settings',
        'Help'               => 'admin_help'
    );
}
elseif ($userType == 'instructor')
{
    $controllers = array('Home' => 'instructor_home');
}
elseif ($userType == 'student')
{
    $controllers = array('Home' => 'student_home');
}

$user = $this->User_model->getAdmin($this->cas->get_user());
if ($user['super'] == 1)
{
    $controllers = array('Admins' => 'admins') + $controllers;
}

$controllers['About'] = 'about';

if (!empty($controllers)) :
    ?>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">

                    <?php
                    foreach ($controllers as $title => $value)
                    {
                        if (!is_array($value))
                        {
                            makeNavLink($title, $value, ($class == $value));
                        }
                        else
                        {
                            makeDropdown($title, $value, $class, $method);
                        }
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php makeNavLink('Log Out', 'auth/logout', false); ?>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!--/.container-fluid -->
    </nav>

<?php
endif;

$user_account_keys = $this->session->userdata('groups');

if (count($user_account_keys) > 1)
{
    echo '<div class="alert alert-warning" role="alert">Multiple Roles: ';
    foreach ($user_account_keys as $user_account)
    {
        $userAccount = $this->session->userdata($user_account);
        if ($userAccount != false && $user_account != $default_account)
        {
            echo '<a class="account-link alert-link" href="' . base_url() . $userAccount['controller'] . '">' . ucfirst($userAccount['group']) . '' . '</a>';
        }
    }
    echo '</div>';
}
?>
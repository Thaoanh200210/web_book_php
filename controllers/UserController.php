<?php

namespace Project\controllers;

require "../vendor/autoload.php";

use function Project\src\redirect;
use Project\model\UserModel;
use function Project\src\render_view_twig;


class UserController
{
    private $dbUser;
    function __construct()
    {
        $this->dbUser = new UserModel;
    }

    private function FormArray($collection)
    {
        return $collection->toArray();
    }

    public function userTable()
    {
        $users = $this->dbUser->getAllUser();
        render_view_twig("userTable.twig", ["users" => $users]);
    }

    public function signUpForm(string $message = "")
    {
        render_view_twig("signup.twig", ["message" => $message]);
    }

    public function logInForm(string $message = '')
    {
        render_view_twig("login.twig", ['message' => $message]);
    }
    public function changePasswordForm(string $message = '')
    {
        // if(isset($_REQUEST['username']))
        render_view_twig("changePass.twig", ["message" => $message]);
    }
    public function signUpProcess()
    {
        if (isset($_POST['username']) && isset($_POST["password"]) && isset($_POST['phone']) && isset($_POST['address']) && isset($_POST['idCard'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $idCard = $_POST['idCard'];
            
            if(!$this->dbUser->findByUsername($username)){
                $result = $this->dbUser->create([
                    'username' => $username,
                    'password' => $password,
                    'phone' => $phone,
                    'address' => $address,
                    'CCCD' => $idCard
                ]);
                if (isset($result)) {
                    redirect("/login");
                    exit();}
            }
            else{
                $this->signUpForm("Tài khoản đã tồn tại");
            }
        }
        else {
            $this->signUpForm("Có lỗi xảy ra. Hãy nhập đủ thông tin");
        }
    }
    public function logInProcess()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = $this->dbUser->findByUsername($username);
        if ($result?->password == $password) {
            if(!isset($_SESSION['username']))
                $_SESSION['username'] = $result->username;
            redirect("/");
        }
        else $this->logInForm("Sai tên đăng nhập hoặc mật khẩu");
    }
    public function changePassProcess()
    {
        $user = $this->dbUser->findByUsername($_SESSION['username']);
        if (isset($user)){
            if($user->password == $_POST['password']){
                $this->dbUser->updatePassword($user->username, $_POST['password_new']);
                redirect("/");
            }
            else {
                $this->changePasswordForm("Mật khẩu cũ không khớp");
            }
        }
    }

    public function logOutProcess()
    {
        unset($_SESSION['username']);
        redirect("/login");
    }
}

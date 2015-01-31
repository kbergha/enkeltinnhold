<?php

namespace Enkeltinnhold;

class Login extends Base {

    public function loginUser($user, $password) {
        $userKey = 'user:'.(string) $user;
        //debug($userKey);

        if($this->checkIfUserExists($userKey)) {
            $predisClient = $this->getPredisClient();

            //$hash = password_hash($password, PASSWORD_DEFAULT);
            //debug($hash);

            ////$predisClient->hset($this->getMasterKey().':'.$userKey, 'passwordHash', $hash);
            $storedHash = $predisClient->hget($this->getMasterKey().':'.$userKey, 'passwordHash');

            if(mb_strlen($storedHash) > 32) {
                // Sanity check
                if (password_verify($password, $storedHash)) {
                    // Yay

                    $options = ['cost' => 11];
                    if (password_needs_rehash($storedHash, PASSWORD_DEFAULT, $options)) {
                        $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
                        $predisClient->hset($this->getMasterKey().':'.$userKey, 'passwordHash', $newHash);
                        // Log?
                    }

                    $_SESSION[$this->getMasterKey()]['loggedin'] = true;
                    $_SESSION[$this->getMasterKey()]['login-user'] = $userKey;
                    $_SESSION[$this->getMasterKey()]['login-time'] = date('c');
                    //debug($_SESSION[$this->getMasterKey()]);
                    session_regenerate_id(true);

                    return true;

                } else {
                    // Fail
                    return false;
                }
            } else {
                // Tech-Fail
                return false;
            }
        } else {
            // Fail
            return false;
        }

    }

    protected function checkIfUserExists($userKey) {
        $predisClient = $this->getPredisClient();
        return $predisClient->sismember($this->getMasterKey().':allusers', $userKey);
    }

    public function destroySession() {
        unset($_SESSION[$this->getMasterKey()]);
    }

    public function isLoggedIn() {
        if(session_status() != PHP_SESSION_ACTIVE) {
            // Log?
            $this->destroySession();
            return false;
        }

        if(isset($_SESSION[$this->getMasterKey()]['loggedin']) && isset($_SESSION[$this->getMasterKey()]['login-user'])) {
            return true;
        }
        return false;
    }

    public function getLoggedInUser() {
        if($this->isLoggedIn() && isset($_SESSION[$this->getMasterKey()]['login-user'])) {
            return $_SESSION[$this->getMasterKey()]['login-user'];
        }
    }
}
<?php

namespace Auth;

use Phalcon\Mvc\User\Component;

use Circuitos\Models\Usuario;

class Autentica extends Component 
{

    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function check($credentials) {
        // Check if the user exist
        $user = Usuario::findFirst("login='{$credentials['login']}'");
        if ($user == false) {
            return false;
        }
        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->getSenha())) {
            return false;
        }
        $this->session->set('auth-identity', array(
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'role' => $user->getRolesName(),
            'nome' => $user->Pessoa->nome
        ));
        return true;
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity() {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current user id
     *
     * @return integer
     */
    public function getIdUsuario() {
        $identity = $this->session->get('auth-identity');
        return $identity['id'];
    }

    /**
     * Returns the current user login
     *
     * @return string
     */
    public function getLogin() {
        $identity = $this->session->get('auth-identity');
        return $identity['login'];
    }

    /**
     * Returns the current user roles
     *
     * @return string
     */
    public function getRoles() {
        $identity = $this->session->get('auth-identity');
        return $identity['role'];
    }

    /**
     * Returns the current user name
     *
     * @return string
     */
    public function getName() {
        $identity = $this->session->get('auth-identity');
        return $identity['nome'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove() {
        $this->session->remove('auth-identity');
    }

}

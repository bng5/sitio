<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() {
//        $record = User::model()->findByAttributes(array('username' => $this->username));
        $record = User::model()->get($this->username);
        if($record === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        else if($record->password !== $record->cryptPass($this->password, $record->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->_id = $record->id;
            $this->setState('email', $record->email);
			$this->errorCode=self::ERROR_NONE;
        }
		return !$this->errorCode;
	}
}
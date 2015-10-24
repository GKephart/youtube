<?php
/**
 * This is a small cross section of youtube for the data design project.
 *
 * This class mutates and sanitizes user input that will go into the Mysql database.
 * After the user input is sanitized and verified it uploads the user information into MySQL.
 * This class also checks to see if a new youtube account needs to be created for the information given.
 *
 * @author George Kephart <gkephart@cnm.edu>
 **/
class Account {
	/**
	 * id for this account; this is a primary key
	 * @var int $accountId
	 */
	private $accountId;
	/**
	 * This is the email associated with the youtube account
	 * @var string $email
	 */
	private $email;
	/**
	 * This is th name of the profile tied to the account, in an VARCHAR with 32 characters
	 * @var string $accountName
	 **/
	private $accountName;
	/**
	 * this is a basic description of the person who owns the account, this field is optional
	 * @var string $userInfo
	 */
	private $userInfo;
	/**
	 * this is a 64 byte salt used for password verification
	 * @var int $salt
	 */
	private $salt;
	/** this is a SHA-5-12 hash with 128 bytes
	 * @var int $hash
	 */
	private $hash;

	//Explanation of why i broke protocol and am not laying out in alphabetical order.

	/**
	 * Constructor for this Account
	 *
	 * @param mixed $newAccountId ID of this account or null if its a new account.
	 * @param string $newEmail string containing actual email ideas.
	 * @param string $newAccountName string containing actual name of youtube account.
	 * @param string $newUserInfo string containing actual information about the person who has the account.
	 * @param string $newSalt string containing 64 bytes with encrypted information about the password.
	 * @param string $newHash string containing 128 bytes with encrypted information about the password.
	 * @param string $newEmail string containing actual email ideas.
	 * @throws InvalidArgumentException if data types aren't valid
	 * @throws RangeException if data Values are out of bounds (e.g strings incorrect length, negative integers)
	 * @throws Exception if some other exception is thrown
	 */
	public function __construct($newAccountId, $newAccountName, $newUserInfo, $newSalt, $newHash, $newEmail = null) {
		try {
			$this->setAccountId($newAccountId);
			$this->setEmail($newEmail);
			$this->setAccountName($newAccountName);
			$this->setUserInfo($newUserInfo);
			$this->setSalt($newSalt);
			$this->setHash($newHash);
		} catch(InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(RangeException $range) {
			// rethrow the exception to the caller
			throw(new RangeException($range->getMessage(), 0, $range));
		} catch(Exception $exception) {
			// rethrow generic exception
			throw(new Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method accountId
	 *
	 * @return mixed value of accountId
	 */
	public function getAccountId() {
		return ($this->accountId);
	}

	/**
	 * mutator method for accountId
	 * @param mixed $newAccountId new value of account id
	 * @throws InvalidArgumentException if $newAccountId is not an integer or positive
	 * @throws RangeException if $newAccountId is not positive
	 */

	public function setAccountId($newAccountId) {
		// base case: if the account id is null, this is a new account without a mySQL assigned id
		$newAccountId = filter_var($newAccountId, FILTER_VALIDATE_INT);
		if($newAccountId === null) {
			$this->accountId = null;
			return;
		}

		if($newAccountId === false) {
			throw(new InvalidArgumentException("profile id is not a valid integer"));
		}

		//verify the accountId is positive
		if($newAccountId <= 0) {
			throw(new RangeException("profile id is not positive."));
		}

		// convert and store the profile id
		$this->accountId = intval($newAccountId);
	}

	/**
	 * Accessor method for email
	 *
	 * @return string value of email
	 */

	public function getEmail() {
		return ($this->email);
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newEmail new value of user information
	 * @throws InvalidArgumentException if $newEmail is not a string or insecure
	 * @throws RangeException if $newHash is more than 128 characters
	 */

	public function setEmail($newEmail) {
		// verify the email address is correct
		$newEmail = trim($newEmail);
		$newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL);
		if(empty($newEmail) === true) {
			throw(new InvalidArgumentException("user information is insecure or empty"));
		}
		// verify the email can fit into the database.
		if(strlen($newEmail) > 128) {
			throw(new RangeException("Email is to long"));
		}
		//store the tweet content
		$this->email = $newEmail;
	}

	/**
	 * accessor method for account name
	 *
	 * @return string value for account
	 */
	public function getAccountName() {
		return ($this->accountName);
	}

	/**
	 * mutator method for account user name
	 * @param string $newAccountName new value of of account user name
	 * @throws InvalidArgumentException if $newTweetContent is not a string or insecure
	 * @throws RangeException if $newAccountName is > 10 characters
	 */
	public function setAccountName($newAccountName) {
		// verify the account name content is secure
		$newAccountName = trim($newAccountName);
		$newAccountName = filter_var($newAccountName, FILTER_SANITIZE_STRING);
		if(empty($newAccountName) === true) {
			throw(new InvalidArgumentException("Account name is empty or insecure"));
		}

		// verify the account name will fit into this database
		if(strlen($newAccountName) > 10) {
			throw(new RangeException ("Account name is to large"));
		}
		// store the account name
		$this->accountName = $newAccountName;
	}

	/**
	 * Accessor method for user information
	 *
	 * @return string value of user information
	 */

	public function getUserInfo() {
		return ($this->userInfo);
	}

	/**
	 * Mutator method for user information
	 *
	 * @param string $newUserInfo new value of user information
	 * @throws InvalidArgumentException if $newUserInfo is not a string or insecure
	 * @throws RangeException if $newUserInfo is > 100 characters
	 */
	public function setUserInfo($newUserInfo) {
		// verify the user information is correct
		$newUserInfo = trim($newUserInfo);
		$newUserInfo = filter_var($newUserInfo, FILTER_SANITIZE_STRING);

		if(empty($newUserInfo) === true) {
			throw(new InvalidArgumentException("user information is insecure or empty"));
		}
		// verify the tweet content will fit into the database.
		if(strlen($newUserInfo) > 140) {
			throw(new RangeException("user information is to long"));
		}
		//store the tweet content
		$this->userInfo = $newUserInfo;
	}

	/**
	 * Accessor method for salt
	 *
	 * @return string value of salt
	 */

	public function getSalt() {
		return ($this->salt);
	}

	/**
	 * mutator method for user information
	 *
	 * @param string $newSalt new value of user information
	 * @throws InvalidArgumentException if $newSalt is not a string or insecure
	 * @throws RangeException if $newSalt is not 64 characters
	 */

	public function setSalt($newSalt) {
		// verify salt is correct
		$newSalt = trim($newSalt);
		$newSalt = filter_var($newSalt, FILTER_SANITIZE_STRING);
		if(empty($newSalt) === true) {
			throw(new InvalidArgumentException("user salt information is insecure or empty"));
		}
		// verify salt is the correct length.
		if(strlen($newSalt) !== 64) {
			throw(new RangeException("half of the password verification  is not the right length"));
		}
		//store the salt content
		$this->salt = $newSalt;
	}

	/**
	 * Accessor method for hash
	 *
	 * @return string value of hash
	 */

	public function getHash() {
		return ($this->hash);
	}

	/**
	 * mutator method for user information
	 *
	 * @param string $newHash new value of user information
	 * @throws InvalidArgumentException if $newHash is not a string or insecure
	 * @throws RangeException if $newHash is not 128 characters
	 */

	public function setHash($newHash) {
		// verify hash is correct
		$newHash = trim($newHash);
		$newHash = filter_var($newHash, FILTER_SANITIZE_STRING);
		if(empty($newHash) === true) {
			throw(new InvalidArgumentException("user hash information is insecure or empty"));
		}
		// verify the hash will fit into the database.
		if(strlen($newHash) !== 128) {
			throw(new RangeException("half of the password verification  is not the right length"));
		}
		//store the hash
		$this->hash = $newHash;
	}

	/**
	 * Inserts this you tube account into mySQL
	 *
	 * @param PDO $pdo PDO connection object
	 * @throws PDOException when mySQl errors occur
	 */

	public function insert(PDO $pdo) {
		//enforce if the youTubeAccount is null
		if($this->accountId !== null) {
			throw(new PDOException ("not a new account"));
		}
		// create query template
			$query
				= "INSERT INTO youtubeAccount(email, accountName, userInfo, salt, hash) VALUES(:email, :accountname, :userInfo, :salt, :hash)";
		 	$statement = $pdo->prepare($query);

		 	// bind the member variables to the place holders in the template)
		 	$parameters = array("email" => $this->email, "accountName" => $this->userInfo, "salt" => $this->salt, "hash" => $this->hash);
		 $statement->excecute($parameters);

		  // update the null accountId with with what mySQL just gave us
		  $this->accountId = intval($pdo->LastInsertId());
	}
}





<?php

namespace GinoVillalpando\ObjectOriented;

require_once ("autoload.php");
require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Cross Section of a author
 *
 * This is a cross section of what is probably stored about an author. This entity is a top level entity that
 * holds the keys to the other entities.
 *
 **/
class author implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this author; this is the primary key
	 * @var Uuid $authorId
	 **/
	private $authorId;
	/**
	 * token handed out to verify that the author is valid and not malicious.
	 * @var string $authorActivationToken
	 **/
	private $authorActivationToken;
	/**
	 * Avatar for this author
	 * @var string $AvatarUrl
	 **/
	private $authorAvatarUrl;
	/**
	 * email for this author; this is a unique index
	 * @var string $authorEmail
	 **/
	private $authorEmail;
	/**
	 * hash for author password
	 * @var string $authorHash
	 **/
	private $authorHash;
	/**
	 * at handle for this author; this is a unique index
	 * @var string $authorAtHandle
	 **/
	private $authorUsername;

	/**
	 * constructor for this author
	 * @param int $newAuthorId new user id
	 * @param string $newAuthorActivationToken new activation token
	 * @param string $newAuthorAvatarUrl new avatar
	 * @param string $newAuthorEmail new email address
	 * @param string $newAuthorHash new password
	 * @param string $newAuthorUsername new Username
	 * @throws \UnexpectedValueException if any of the parameters are invalid
	 */
	public function __construct($newAuthorId, ?string $newAuthorActivationToken, string $newAuthorAvatarUrl, string $newAuthorEmail, string $newAuthorHash, ?string $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUsername($newAuthorUsername);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 97, $exception));
		}
	}

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id (or null if new author)
	 **/
	public function getAuthorId(): Uuid {
		return ($this->authorId);
	}

	/**
	 * mutator method for author id
	 *
	 * @param Uuid| string $newAuthorId value of new author id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if the author Id is not
	 **/
	public function setAuthorId($newAuthorId): void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
// convert and store the author id
		$this->authorId = $uuid;
	}

	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 */
	public function getAuthorActivationToken(): ?string {
		return ($this->authorActivationToken);
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 */
	public function setAuthorActivationToken(?string $newAuthorActivationToken): void {
		if($newAuthorActivationToken === null) {
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false) {
			throw(new \RangeException("user activation is not valid"));
		}
//make sure user activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 8) {
			throw(new \RangeException("user activation token has to be 32"));
		}
		$this->authorActivationToken = $newAuthorActivationToken;
	}

	/**
	 * /**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getAuthorEmail(): string {
		return $this->authorEmail;
	}

	/**
	 * accessor method for avatar
	 *
	 * @return string value of avatar or null
	 **/
	public function getAuthorAvatarUrl(): ?string {
		return ($this->authorAvatarUrl);
	}

	/**
	 * mutator method for avatar
	 *
	 * @param string $newAuthorAvatarUrl new value of avatar
	 * @throws \InvalidArgumentException if $newAvatar is not a string or insecure
	 * @throws \RangeException if $newAvatar is > 32 characters
	 * @throws \TypeError if $newAvatar is not a string
	 **/
	public function setAuthorAvatarUrl(?string $newAuthorAvatarUrl): void {
		//if $authorAvatarUrl is null return it right away
		if($newAuthorAvatarUrl === null) {
			$this->authorAvatarUrl = null;
			return;
		}
// verify the avatar is secure
		$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
		$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorAvatarUrl) === true) {
			throw(new \InvalidArgumentException("Avatar URL is empty or insecure"));
		}
// verify the avatar will fit in the database
		if(strlen($newAuthorAvatarUrl) > 255) {
			throw(new \RangeException("Avatar is too large"));
		}
// store the avatar
		$this->authorAvatarUrl = $newAuthorAvatarUrl;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setAuthorEmail(string $newAuthorEmail): void {
// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("author email is empty or insecure"));
		}
// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new \RangeException("author email is too large"));
		}
// store the email
		$this->authorEmail = $newAuthorEmail;
	}

	/**
	 *accessor method for authorHash
	 *
	 * @return string for authorHash hashed password
	 */
	public function getAuthorHash(): string {
		return ($this->authorHash);
	}
	/**
	 * mutator method for author hash
	 *
	 * @param  string $newAuthorHash value of new author hashed password
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if author hash is not a string
	 **/
	public function setAuthorHash(string $newAuthorHash): void {
		//enforce that the hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		$newAuthorHash = strtolower($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("author password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newAuthorHash)) {
			throw(new \InvalidArgumentException("author password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newAuthorHash) !== 97) {
			throw(new \RangeException("author hash must be 97 characters"));
		}
		//store the hash
		$this->authorHash = $newAuthorHash;
	}

	/** accessor method for Username
	 *
	 * @return string value of Username
	 **/
	public function getAuthorUsername(): ?string {
		return ($this->authorUsername);
	}

	/**
	 * mutator method for Username
	 *
	 * @param string $newAuthorUsername
	 */
	public function setAuthorUsername(string $newAuthorUsername): void {
		// verify the at handle is secure
		$newAuthorUsername = trim($newAuthorUsername);
		$newAuthorUsername = filter_var($newAuthorUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorUsername) === true) {
			throw(new \InvalidArgumentException("Username is empty or insecure"));
		}
		// verify the at handle will fit in the database
		if(strlen($newAuthorUsername) > 32) {
			throw(new \RangeException("Username is too large"));
		}
		// store the Username
		$this->authorUsername = $newAuthorUsername;
	}
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["authorId"] = $this->authorId->toString();
		unset($fields["authorHash"]);
		return ($fields);
	}
}
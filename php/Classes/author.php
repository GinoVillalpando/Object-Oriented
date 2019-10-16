<?php

namespace GinoVillalpando\ObjectOriented;

	require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

	use Ramsey\Uuid\Uuid;
	/**
	 * Cross Section of a Author Profile
	 *
	 * This is a cross section of what is probably stored about an author. This entity is a top level entity that
	 * holds the keys to the other entities.
	 *
	 **/
	class author {
		use ValidateUuid;
		/**
		 * id for this author; this is the primary key
		 * @var Uuid $profileId
		 **/
		private $authorId;
		/**
		 * token handed out to verify that the profile is valid and not malicious.
		 *v@var $profileActivationToken
		 **/
		private $authorActivationToken;
		/**
		 * phone number for this Profile
		 * @var string $profilePhone
		 **/
		private $authorAvatarUrl;
		/**
		 * email for this Profile; this is a unique index
		 * @var string $profileEmail
		 **/
		private $authorEmail;
		/**
		 * hash for profile password
		 * @var $profileHash
		 **/
		private $authorHash;
		/**
		 * at handle for this Profile; this is a unique index
		 * @var string $profileAtHandle
		 **/
		private $authorUsername;

		/**
		 * accessor method for profile id
		 *
		 * @return Uuid value of profile id (or null if new Profile)
		 **/
		public function getAuthorId(): Uuid {
			return ($this->authorId);
		}
		/**
		 * mutator method for profile id
		 *
		 * @param  Uuid| string $newProfileId value of new profile id
		 * @throws \RangeException if $newProfileId is not positive
		 * @throws \TypeError if the profile Id is not
		 **/
		public function setAuthorId( $newAuthorId): void {
			try {
				$uuid = self::validateUuid($newAuthorId);
			} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				$exceptionType = get_class($exception);
				throw(new $exceptionType($exception->getMessage(), 0, $exception));
			}
// convert and store the profile id
			$this->authorId = $uuid;
		}
		/**
		 * accessor method for account activation token
		 *
		 * @return string value of the activation token
		 */
		public function getProfileActivationToken() : ?string {
			return ($this->profileActivationToken);
		}
		/**
		 * mutator method for account activation token
		 *
		 * @param string $newProfileActivationToken
		 * @throws \InvalidArgumentException  if the token is not a string or insecure
		 * @throws \RangeException if the token is not exactly 32 characters
		 * @throws \TypeError if the activation token is not a string
		 */
		public function setProfileActivationToken(?string $newProfileActivationToken): void {
			if($newProfileActivationToken === null) {
				$this->profileActivationToken = null;
				return;
			}
			$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
			if(ctype_xdigit($newProfileActivationToken) === false) {
				throw(new\RangeException("user activation is not valid"));
			}
//make sure user activation token is only 32 characters
			if(strlen($newProfileActivationToken) !== 32) {
				throw(new\RangeException("user activation token has to be 32"));
			}
			$this->profileActivationToken = $newProfileActivationToken;
		}
		/**
		 * accessor method for at handle
		 *
		 * @return string value of at handle
		 **/
		public function getProfileAtHandle(): string {
			return ($this->profileAtHandle);
		}
		/**
		 * mutator method for at handle
		 *
		 * @param string $newProfileAtHandle new value of at handle
		 * @throws \InvalidArgumentException if $newAtHandle is not a string or insecure
		 * @throws \RangeException if $newAtHandle is > 32 characters
		 * @throws \TypeError if $newAtHandle is not a string
		 **/
		public function setProfileAtHandle(string $newProfileAtHandle) : void {
// verify the at handle is secure
			$newProfileAtHandle = trim($newProfileAtHandle);
			$newProfileAtHandle = filter_var($newProfileAtHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($newProfileAtHandle) === true) {
				throw(new \InvalidArgumentException("profile at handle is empty or insecure"));
			}
// verify the at handle will fit in the database
			if(strlen($newProfileAtHandle) > 32) {
				throw(new \RangeException("profile at handle is too large"));
			}
// store the at handle
			$this->profileAtHandle = $newProfileAtHandle;
		}
		/**
		 * accessor method for email
		 *
		 * @return string value of email
		 **/
		public function getProfileEmail(): string {
			return $this->profileEmail;
		}
		/**
		 * mutator method for email
		 *
		 * @param string $newProfileEmail new value of email
		 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
		 * @throws \RangeException if $newEmail is > 128 characters
		 * @throws \TypeError if $newEmail is not a string
		 **/
		public function setProfileEmail(string $newProfileEmail): void {
// verify the email is secure
			$newProfileEmail = trim($newProfileEmail);
			$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
			if(empty($newProfileEmail) === true) {
				throw(new \InvalidArgumentException("profile email is empty or insecure"));
			}
// verify the email will fit in the database
			if(strlen($newProfileEmail) > 128) {
				throw(new \RangeException("profile email is too large"));
			}
// store the email
			$this->profileEmail = $newProfileEmail;
		}
		/**
		 * accessor method for profileHash
		 *
		 * @return string value of hash
		 */
		public function getProfileHash(): string {
			return $this->profileHash;
		}

		/**
		 * mutator method for profile hash password
		 *
		 * @param string $newProfileHash
		 * @throws \InvalidArgumentException if the hash is not secure
		 * @throws \RangeException if the hash is not 128 characters
		 * @throws \TypeError if profile hash is not a string
		 */
		public function setProfileHash(string $newProfileHash): void {
//enforce that the hash is properly formatted
			$newProfileHash = trim($newProfileHash);
			if(empty($newProfileHash) === true) {
				throw(new \InvalidArgumentException("profile password hash empty or insecure"));
			}
//enforce the hash is really an Argon hash
			$profileHashInfo = password_get_info($newProfileHash);
			if($profileHashInfo["algoName"] !== "argon2i") {
				throw(new \InvalidArgumentException("profile hash is not a valid hash"));
			}
//enforce that the hash is exactly 97 characters.
			if(strlen($newProfileHash) !== 97) {
				throw(new \RangeException("profile hash must be 97 characters"));
			}
//store the hash
			$this->profileHash = $newProfileHash;
		}
		/**
		 * accessor method for phone
		 *
		 * @return string value of phone or null
		 **/
		public function getProfilePhone(): ?string {
			return ($this->profilePhone);
		}
		/**
		 * mutator method for phone
		 *
		 * @param string $newProfilePhone new value of phone
		 * @throws \InvalidArgumentException if $newPhone is not a string or insecure
		 * @throws \RangeException if $newPhone is > 32 characters
		 * @throws \TypeError if $newPhone is not a string
		 **/
		public function setProfilePhone(?string $newProfilePhone): void {
//if $profilePhone is null return it right away
			if($newProfilePhone === null) {
				$this->profilePhone = null;
				return;
			}
// verify the phone is secure
			$newProfilePhone = trim($newProfilePhone);
			$newProfilePhone = filter_var($newProfilePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($newProfilePhone) === true) {
				throw(new \InvalidArgumentException("profile phone is empty or insecure"));
			}
// verify the phone will fit in the database
			if(strlen($newProfilePhone) > 32) {
				throw(new \RangeException("profile phone is too large"));
			}
// store the phone
			$this->profilePhone = $newProfilePhone;
		}
	}
}
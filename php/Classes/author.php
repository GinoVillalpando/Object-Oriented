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
		public function getAuthorActivationToken() : ?string {
			return ($this->authorActivationToken);
		}
		/**
		 * mutator method for account activation token
		 *
		 * @param string $newProfileActivationToken
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
				throw(new\RangeException("user activation is not valid"));
			}
//make sure user activation token is only 32 characters
			if(strlen($newAuthorActivationToken) !== 32) {
				throw(new\RangeException("user activation token has to be 32"));
			}
			$this->authorActivationToken = $newAuthorActivationToken;
		}
		/**
		 * accessor method for at handle
		 *
		 * @return string value of at handle
		 **/
		public function getAuthorUsername(): ?string {
			return ($this->authorUsername);
		}
		/**
		 * mutator method for at handle
		 *
		 * @param string $newProfileAtHandle new value of at handle
		 * @throws \InvalidArgumentException if $newAtHandle is not a string or insecure
		 * @throws \RangeException if $newAtHandle is > 32 characters
		 * @throws \TypeError if $newAtHandle is not a string
		 **/
		public function setAuthorUsername(string $newAuthorUsername) : void {
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
// store the at handle
			$this->authorUsernamee = $newAuthorUsername;
		}
		/**
		 * accessor method for email
		 *
		 * @return string value of email
		 **/
		public function getAuthorEmail(): string {
			return $this->authorEmail;
		}
		/**
		 * mutator method for email
		 *
		 * @param string $newProfileEmail new value of email
		 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
		 * @throws \RangeException if $newEmail is > 128 characters
		 * @throws \TypeError if $newEmail is not a string
		 **/
		public function setAuthorEmail(string $newAuthorEmail): void {
// verify the email is secure
			$newAuthorEmail = trim($newAuthorEmail);
			$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
			if(empty($newAuthorEmail) === true) {
				throw(new \InvalidArgumentException("Author email is empty or insecure"));
			}
// verify the email will fit in the database
			if(strlen($newAuthorEmail) > 128) {
				throw(new \RangeException("Author email is too large"));
			}
// store the email
			$this->profileEmail = $newAuthorEmail;
		}
		/**
		 * accessor method for profileHash
		 *
		 * @return string value of hash
		 */
		public function getAuthorHash(): string {
			return $this->authorHash;
		}

		/**
		 * mutator method for profile hash password
		 *
		 * @param string $newAuthorHash
		 * @throws \InvalidArgumentException if the hash is not secure
		 * @throws \RangeException if the hash is not 128 characters
		 * @throws \TypeError if profile hash is not a string
		 */
		public function setAuthorHash(string $newAuthorHash): void {
//enforce that the hash is properly formatted
			$newAuthorHash = trim($newAuthorHash);
			if(empty($newAuthorHash) === true) {
				throw(new \InvalidArgumentException("profile password hash empty or insecure"));
			}
//enforce the hash is really an Argon hash
			$authorHashInfo = password_get_info($newAuthorHash);
			if($authorHashInfo["algoName"] !== "argon2i") {
				throw(new \InvalidArgumentException("profile hash is not a valid hash"));
			}
//enforce that the hash is exactly 97 characters.
			if(strlen($newAuthorHash) !== 97) {
				throw(new \RangeException("profile hash must be 97 characters"));
			}
//store the hash
			$this->authorHash = $newAuthorHash;
		}
		/**
		 * accessor method for phone
		 *
		 * @return string value of phone or null
		 **/
		public function getAuthorAvatarUrl(): ?string {
			return ($this->authorAvatarUrl);
		}
		/**
		 * mutator method for phone
		 *
		 * @param string $newAuthorAvatarUrl new value of phone
		 * @throws \InvalidArgumentException if $newPhone is not a string or insecure
		 * @throws \RangeException if $newPhone is > 32 characters
		 * @throws \TypeError if $newPhone is not a string
		 **/
		public function setAuthorAvatarUrl(?string $newAuthorAvatarUrl): void {
//if $profilePhone is null return it right away
			if($newAuthorAvatarUrl === null) {
				$this->authorAvatarUrl = null;
				return;
			}
// verify the phone is secure
			$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
			$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($newAuthorAvatarUrl) === true) {
				throw(new \InvalidArgumentException("Avatar URL is empty or insecure"));
			}
// verify the phone will fit in the database
			if(strlen($newAuthorAvatarUrl) > 255) {
				throw(new \RangeException("Avatar is too large"));
			}
// store the phone
			$this->authorAvatarUrl = $newAuthorAvatarUrl;
		}
	}
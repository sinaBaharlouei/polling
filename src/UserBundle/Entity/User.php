<?php
namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\Constants\RoleConstants;
use UserBundle\Entity\Constants\UserConstants;

/**
 * UserBundle\Entity\User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \JsonSerializable
{

	/**
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="name", type="string", length=128, nullable=true)
	 */
	private $name;

	/**
	 * @ORM\Column(name="vip_username", type="string", length=128, nullable=true)
	 */
	private $vip_username;

	/**
	 * @ORM\Column(name="password", type="string", length=128, nullable=false)
	 */
	private $password;


	/**
	 * @ORM\Column(name="grade", type="string", length=128, nullable=true)
	 */
	private $grade;

	/**
	 * @ORM\Column(name="phone", type="string", length=128, nullable=true)
	 */
	private $phone;

	/**
	 * @ORM\Column(name="gender", type="boolean", nullable=true)
	 */
	private $gender;

	/**
	 * @Assert\Email()
	 * @ORM\Column(name="email", type="string", length=128, unique=true, nullable=false)
	 */
	private $email;

	/**
	 * @ORM\Column(name="type", type="integer", nullable=false)
	 */
	private $role = UserConstants::USER_TYPE_USER;

	/**
	 * @ORM\Column(name="status", type="string", columnDefinition="enum('NOT_VERIFIED', 'NOT_VALIDATED', 'ACTIVE', 'DEACTIVATED', 'DELETED', 'LOCKED', 'EXPIRED')")
	 */
	private $status = UserConstants::STATUS_NOT_VERIFIED;


	/**
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	private $createdAt = NULL;

	/**
	 * @ORM\Column(name="updated_at", type="datetime")
	 */
	private $updatedAt = NULL;


	/**
	 * Plain password. Used for model validation. Must not be persisted.
	 * @var string
	 */
	private $plainPassword;

    /**
     * Set name
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
		$this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

	/**
	 * Checks whether the user's account has expired.
	 * @return bool true if the user's account is non expired, false otherwise
	 */
	public function isAccountNonExpired()
	{
		return true;
	}

	/**
	 * @return bool true if the user is not locked, false otherwise
	 * @see LockedException
	 */
	public function isAccountNonLocked()
	{
		return ($this->status !== UserConstants::STATUS_LOCKED);
	}

	/**
	 * Checks whether the user's credentials (password) has expired.
	 * Internally, if this method returns false, the authentication system
	 * will throw a CredentialsExpiredException and prevent login.
	 * @return bool true if the user's credentials are non expired, false otherwise
	 *
	 * @see CredentialsExpiredException
	 */
	public function isCredentialsNonExpired()
	{
		return true;
	}

	/**
	 * Checks whether the user is enabled.
	 *
	 * @return bool true if the user is enabled, false otherwise
	 *
	 * @see DisabledException
	 */
	public function isEnabled()
	{
		return ($this->status == UserConstants::STATUS_ACTIVE);
	}

	/**
	 * Returns the roles granted to the user.
	 */
	public function getRoles()
	{
		return array($this->role);
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string|null The salt
	 */
	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	public function getUsername()
	{
		return $this->getEmail();
	}

	/**
	 * Removes sensitive data from the user.
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 */
	public function eraseCredentials()
	{
		$this->plainPassword = NULL;
	}

	/**
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 */
	function jsonSerialize()
	{
		return array(
			'username' => $this->getUsername()
		);
	}

    /**
     * Set salt
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

	/**
	 * Set plain Password
	 * @param $plainPassword
	 * @return $this
	 */
	public function setPlainPassword($plainPassword)
	{
		$this->plainPassword = $plainPassword;
		return $this;
	}

	/**
	 * Get plainPassword
	 * @return string
	 */
	public function getPlainPassword()
	{
		return $this->plainPassword;
	}

	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	public function getPassword()
	{
		return $this->password;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return User
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param integer $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

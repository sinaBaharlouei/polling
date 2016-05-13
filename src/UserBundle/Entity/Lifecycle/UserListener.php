<?php

namespace UserBundle\Entity\Lifecycle;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Events;
use UserBundle\Entity\User;

/**
 * Class UserListener
 * @package UserBundle\Entity\Lifecycle
 */
class UserListener implements EventSubscriber
{
	/**
	 * Returns an array of events this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		// TODO: Implement getSubscribedEvents() method.
	}

	/**
	 * @param User $user
	 * @internal param UserInterface $user
	 */
	public function updatePassword($user)
	{
		if (0 !== strlen($password = $user->getPlainPassword())) {
			$encoder = $this->getEncoder($user);

			$user->setPassword($encoder->encodePassword($user, $password));
			$user->eraseCredentials();
		}
	}

	/**
	 * @param UserInterface $user
	 * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
	 */
	protected function getEncoder(UserInterface $user)
	{
		return $this->container->get('security.password_encoder');
	}

};

<?php

namespace UserBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;
use UserBundle\Entity\Constants\UserConstants;
use UserBundle\Entity\User;

class AuthenticationController extends BaseController
{

	/**
	 * Input Parameters:
	 *  username: String,
	 *  password: String,
	 *  email: String (optional),
	 * 	phone: String
	 * Return Values:
	 *  status: Bool
	 *  data: JSON {player: Player}
	 *  error: JSON {code: Int, msg: String}
	 * @Route(path="/register", name="user_register")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @throws \Doctrine\ORM\ORMInvalidArgumentException
	 * @throws \Exception
	 */

	public function registerAction(Request $request)
	{
		// Get Parameters
		$name = $this->required("name");
		$email = $this->required("email");
		$plainPassword = $this->required("password");

		$grade = $this->optional("grade");
		$gender = $this->optional("gender");
		$phone = $this->optional("phone");

		$status = UserConstants::STATUS_ACTIVE;
		$type = UserConstants::USER_TYPE_USER;

		$created_at = new \DateTime();

		if (strlen($plainPassword) < 6) {
			$this->error("Password should at least has 6 characters.");
		}

		$em = $this->getDoctrine()->getEntityManager();
		$userModel = $em->getRepository('UserBundle:User');

		$old_user = $userModel->findOneBy(
			array('email' => $email)
		);

		if ($old_user)
			$this->error("User is already exist.", Logger::ERROR);


		$user = new User();

		$user->setName($name);
		$user->setPlainPassword($plainPassword);
		$user->setEmail($email);

		$user->setGender($gender);
		$user->setPhone($phone);

		$user->setGrade($grade);
		$user->setStatus($status);
		$user->setRole($type);

		$user->setCreatedAt($created_at);
		$user->setUpdatedAt($created_at);

		// password encoding
		$encoder = $this->container->get('security.password_encoder');

		$user_password = $encoder->encodePassword($user, $plainPassword);
		$user->setPassword($user_password);
		$user->eraseCredentials();

		// $this->validate($user);
		$em->persist($user);

		$em->flush();


		$user_id = $user->getId();
		$this->log("User registered with user_id='$user_id'");


		$to = $email;
		$from = "sinabaharlouei@yahoo.com";
		$subject = "فعالسازی حساب کاربری";
		$body = "با سلام. اکانت فعال سازی ثبت نام شما در زیر آمده است. با کلیک روی آن حساب کاربریتان فعال می شود:
				<a href='http://127.0.0.1:8000/user/account_activation?user_id=$user_id&token=$user_password'>لینک فعالسازی</a>";
		$this->sendMail($from, $to, $subject, $body);

		return $this->success(array(
			'user' => $user,
		));
	}


	/**
	 * @Route(path="/login", name="user_auth_login")
	 * @Template
	 * @param Request $request
	 * @return array
	 */
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render(
			'@User/User/login.html.twig',
			array(
				// last username entered by the user
				'last_username' => $lastUsername,
				'error'         => $error,
			)
		);
	}

	/**
	 * @Route(path="/login_check", name="user_auth_login_check")
	 */
	public function loginCheckAction() {
		return new Response('');
	}

	/**
	 * @Route(path="/logout", name="user_auth_logout")
	 */
	public function logoutAction() {
		return new Response('');
	}

}

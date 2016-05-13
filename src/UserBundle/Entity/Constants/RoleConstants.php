<?php
namespace UserBundle\Entity\Constants;

/**
 * UserBundle\Entity\Constants\RoleConstants
 */
class RoleConstants
{
	const ROLE_ADMIN = 'ROLE_ADMIN';
	const ROLE_USER = 'ROLE_USER';
	const ROLE_SELLER = 'ROLE_SELLER';

	static $label = array(
		RoleConstants::ROLE_ADMIN => 'label.role.admin',
		RoleConstants::ROLE_USER => 'label.role.user',
		RoleConstants::ROLE_SELLER => 'label.role.seller',
	);
}

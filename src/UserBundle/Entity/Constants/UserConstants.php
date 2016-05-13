<?php
namespace UserBundle\Entity\Constants;

/**
 * UserBundle\Entity\Constants\UserConstants
 */
class UserConstants
{
	const STATUS_NOT_VERIFIED = "NOT_VERIFIED";
	const STATUS_ACTIVE = "ACTIVE";
	const STATUS_DEACTIVATED = "DEACTIVATED";
	const STATUS_DELETED = "DELETED";
	const STATUS_LOCKED = "LOCKED";

	public static $user_statuses = array(
		UserConstants::STATUS_NOT_VERIFIED => "Not Verified",
		UserConstants::STATUS_ACTIVE => "Active",
		UserConstants::STATUS_DEACTIVATED => "Deactivated",
		UserConstants::STATUS_DELETED => "Deleted",
		UserConstants::STATUS_LOCKED => "Locked",
	);

	const USER_TYPE_USER = 1;
	const USER_TYPE_PUBLISHER = 2;
	const USER_TYPE_ADMINISTRATOR = 3;

	public static $user_types = array(
		UserConstants::USER_TYPE_USER => "USER",
		UserConstants::USER_TYPE_PUBLISHER => "Publisher",
		UserConstants::USER_TYPE_ADMINISTRATOR => "Administrator",
	);


}

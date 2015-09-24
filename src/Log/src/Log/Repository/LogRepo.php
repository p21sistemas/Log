<?php

namespace Log\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Util\Debug;

class LogRepo extends \Application\Repository\Repository
{
	/**
	 * @return void
	 */
	public function save($data)
	{
		$this->getEntityManager()->persist($data);
		$this->getEntityManager()->flush();
	}
}

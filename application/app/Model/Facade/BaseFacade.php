<?php declare(strict_types=1);

namespace App\Model\Facade;

use App\Model\Entity\EntityManager;

abstract class BaseFacade
{
    /** @var EntityManager */
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /** @return EntityManager */
    public function getEM(): EntityManager
    {
        return $this->em;
    }

}

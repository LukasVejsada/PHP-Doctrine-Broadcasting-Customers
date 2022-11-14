<?php declare(strict_types=1);

namespace App\Base\Components;

use App\Model\Entity\EntityManager;
use Nette\Application\UI\Control;

abstract class BaseControl extends Control
{
    /** @return EntityManager */
    public function getEM(): EntityManager
    {
        return $this->getPresenter()->getEM();
    }
}
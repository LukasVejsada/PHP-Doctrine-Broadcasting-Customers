<?php declare(strict_types=1);

namespace App\Presenters;

use App\Model\Entity\EntityManager;
use Nette\Application\UI\Presenter;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
    private EntityManager $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    public function getEM(): EntityManager
    {
        return $this->em;
    }
}

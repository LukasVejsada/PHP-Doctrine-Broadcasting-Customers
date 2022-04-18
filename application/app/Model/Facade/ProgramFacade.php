<?php declare(strict_types=1);

namespace App\Model\Facade;

use App\Model\Entity\Customer;
use App\Model\Entity\Program;
use Nette\Utils\ArrayHash;


class ProgramFacade extends BaseFacade
{
    public function add(Customer $customer, ArrayHash $values): Program
    {
        $name = $values->name;

        $program = new Program($customer, $name);
        $this->getEM()->persist($program);

        $this->_setOptionalAttributes($program, $values);

        $this->getEM()->flush();
        return $program;
    }

    public function delete(Program $program): void
    {
        $this->getEM()->remove($program);
        $this->getEM()->flush();
    }

    public function edit(Program $program, ArrayHash $values): void
    {
        $name = $values->name;
        $program->setName($name);

        $this->_setOptionalAttributes($program, $values);

        $this->getEM()->flush();
    }

    private function _setOptionalAttributes(Program $program, ArrayHash $values): void
    {
        $broadcastingDate = new \DateTime();
        $broadcastingDate->modify($values->broadcastingDate);
        $program->setBroadcastingDate($broadcastingDate);
        $program->setDescription($values->description);
    }

}
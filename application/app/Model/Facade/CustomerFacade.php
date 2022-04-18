<?php declare(strict_types=1);

namespace App\Model\Facade;

use App\Model\Entity\Customer;
use Nette\Utils\ArrayHash;

class CustomerFacade extends BaseFacade
{
    public function add(ArrayHash $values): Customer
    {
        $name = $values->name;
        $type = $values->type;

        $customer = new Customer($name, $type);
        $this->getEM()->persist($customer);

        $this->_setOptionalAttributes($customer, $values);

        $this->getEM()->flush();
        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $this->getEM()->remove($customer);
        $this->getEM()->flush();
    }

    public function edit(Customer $customer, ArrayHash $values): void
    {
        $name = $values->name;
        $type = $values->type;
        $customer->setName($name);
        $customer->setType($type);

        $this->_setOptionalAttributes($customer, $values);

        $this->getEM()->flush();
    }

    private function _setOptionalAttributes(Customer $customer, ArrayHash $values): void
    {
        $customer->setNote($values->note);
    }


}
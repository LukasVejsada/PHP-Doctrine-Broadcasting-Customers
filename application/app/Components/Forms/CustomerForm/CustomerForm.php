<?php declare(strict_types=1);

namespace App\Components\Forms\CustomerForm;

use App\Base\Components\BaseControl;
use App\Model\Entity\Customer;
use App\Model\Facade\CustomerFacade;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

interface ICustomerForm
{
    /**
     * @param Customer|null $customer
     * @return CustomerForm
     */
    public function create(?Customer $customer): CustomerForm;
}

class CustomerForm extends BaseControl
{
    /** @var Customer|null $customer */
    private ?Customer $customer;

    /** @var CustomerFacade $customerFacade */
    private CustomerFacade $customerFacade;


    public function __construct(?Customer $customer, CustomerFacade $customerFacade)
    {
        $this->customer = $customer;
        $this->customerFacade = $customerFacade;

    }

    public function render()
    {
        $this->template->render(__DIR__ . '/customerForm.latte');
    }

    public function createComponentCustomerForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Název')
            ->setRequired('Zadejte prosím název.');

        $form->addSelect('type', 'Typ zákazníka', Customer::$typeNameArr)
            ->setRequired('Zvolte prosím typ zákazníka.')
            ->setPrompt(' ---Vyberte typ zákazníka--- ');

        $form->addTextArea('note', 'Poznámka');

        $form->addSubmit('send', 'Uložit');

        $customer = $this->customer;

        if ($customer) {
            $form->getComponent('name')->setDefaultValue($customer->getName());
            $form->getComponent('type')->setDefaultValue($customer->getType());
            $form->getComponent('note')->setDefaultValue($customer->getNote());
        }

        $form->onSuccess[] = [$this, 'customerFormSucceeded'];
        return $form;
    }

    public function customerFormSucceeded(Form $form): ?Form
    {
        /** @var ArrayHash $values */
        $values = $form->getValues();
        $customer = $this->customer;

        if ($customer) {
            $this->customerFacade->edit($customer, $values);
            $this->getPresenter()->flashMessage('Zákazník byl upraven.');
        } else {
            $customer = $this->customerFacade->add($values);
            $this->getPresenter()->flashMessage('Zákazník byl vytvořen.');
        }
        $this->getPresenter()->redirect('Customer:edit', $customer->getId());
    }
}
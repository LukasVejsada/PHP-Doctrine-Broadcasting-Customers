<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\Forms\CustomerForm\CustomerForm;
use App\Components\Forms\CustomerForm\ICustomerForm;
use App\Components\Grids\CustomerGrid\CustomerGrid;
use App\Components\Grids\CustomerGrid\ICustomerGrid;
use App\Components\Grids\ProgramGrid\IProgramGrid;
use App\Components\Grids\ProgramGrid\ProgramGrid;
use App\Model\Entity\Customer;

final class CustomerPresenter extends BasePresenter
{
    /** @var Customer|null $customer */
    private ?Customer $customer = null;

    /** @var ICustomerForm $ICustomerForm @inject */
    public ICustomerForm $ICustomerForm;

    /** @var ICustomerGrid $ICustomerGrid @inject */
    public ICustomerGrid $ICustomerGrid;

    /** @var IProgramGrid $IProgramGrid @inject */
    public IProgramGrid $IProgramGrid;


    public function actionDefault(): void
    {
    }

    public function actionAdd(): void
    {
    }

    public function actionEdit(?string $id): void
    {
        /** @var Customer|null $customer */
        $customer = $this->getEM()->getRepository(Customer::class)->find($id);
        if (!$customer) {
            $this->flashMessage('Zákazník neexistuje.');
            $this->redirect('Customer:default');
        }
        $this->customer = $customer;
        $this->template->customer = $customer;
    }

    public function createComponentCustomerForm(): CustomerForm
    {
        return $this->ICustomerForm->create($this->customer);
    }

    public function createComponentCustomerGrid(): CustomerGrid
    {
        return $this->ICustomerGrid->create();
    }

    public function createComponentProgramGrid(): ProgramGrid
    {
        return $this->IProgramGrid->create($this->customer);
    }
}

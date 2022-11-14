<?php declare(strict_types=1);

namespace App\Presenters;

use App\Components\Forms\ProgramForm\IProgramForm;
use App\Components\Forms\ProgramForm\ProgramForm;
use App\Components\Grids\ProgramGrid\IProgramGrid;
use App\Components\Grids\ProgramGrid\ProgramGrid;
use App\Model\Entity\Customer;
use App\Model\Entity\Program;

final class ProgramPresenter extends BasePresenter
{
    /** @var Customer|null $customer */
    private ?Customer $customer = null;

    /** @var Program|null $program */
    private ?Program $program = null;

    /** @var IProgramForm $IProgramForm @inject */
    public IProgramForm $IProgramForm;

    /** @var IProgramGrid $IProgramGrid @inject */
    public IProgramGrid $IProgramGrid;


    public function actionDefault(): void
    {
    }

    public function actionAdd(?string $id): void
    {
        /** @var Customer|null $customer */
        $customer = $this->getEM()->getRepository(Customer::class)->find($id);
        if (!$customer) {
            $this->flashMessage('Zákazník neexistuje.');
            $this->redirect('Customer:default');
        }
        $this->customer = $customer;
    }

    public function actionEdit(?string $id): void
    {
        /** @var Program|null $program */
        $program = $this->getEM()->getRepository(Program::class)->find($id);
        if (!$program) {
            $this->flashMessage('Program neexistuje.');
            $this->redirect('Program:default');
        }
        $this->program = $program;
    }

    public function createComponentProgramForm(): ProgramForm
    {
        return $this->IProgramForm->create($this->customer, $this->program);
    }

    public function createComponentProgramGrid(): ProgramGrid
    {
        return $this->IProgramGrid->create();
    }
}

<?php declare(strict_types=1);

namespace App\Components\Forms\ProgramForm;

use App\Base\Components\BaseControl;
use App\Model\Entity\Customer;
use App\Model\Entity\Program;
use App\Model\Facade\ProgramFacade;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

interface IProgramForm
{
    /**
     * @param Customer|null $customer
     * @param Program|null $program
     * @return ProgramForm
     */
    public function create(?Customer $customer, ?Program $program): ProgramForm;
}

class ProgramForm extends BaseControl
{
    /** @var Customer|null $customer */
    private ?Customer $customer;

    /** @var Program|null $program */
    private ?Program $program;

    /** @var ProgramFacade $programFacade */
    private ProgramFacade $programFacade;


    public function __construct(?Customer $customer, ?Program $program, ProgramFacade $programFacade)
    {
        $this->customer = $customer;
        $this->program = $program;
        $this->programFacade = $programFacade;
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/programForm.latte');
    }

    public function createComponentProgramForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Název')
            ->setRequired('Zadejte prosím název.');

        $now = new \DateTime();
        $form->addText('broadcastingDate', 'Vysílací čas')
            ->setDefaultValue($now->modify('today + 3days + 12hours')->format('d.m.Y H:i'));

        $form->addText('description', 'Popis');

        $form->addSubmit('send', 'Uložit');

        $program = $this->program;

        if ($program) {
            $form->getComponent('name')->setDefaultValue($program->getName());
            $form->getComponent('broadcastingDate')->setDefaultValue($program->getBroadcastingDate()->format('d.m.Y H:i'));
            $form->getComponent('description')->setDefaultValue($program->getDescription());
        }

        $form->onSuccess[] = [$this, 'programFormSucceeded'];
        return $form;
    }

    public function programFormSucceeded(Form $form): ?Form
    {
        /** @var ArrayHash $values */
        $values = $form->getValues();

        if (!$this->_validateDate($values->broadcastingDate)) {
            $form->addError('Zadejte prosím vysílací čas ve formátu ´dd.mm.rr hh:mm´.');
            return $form;
        }

        $program = $this->program;
        $customer = $this->customer;

        if ($program) {
            $this->programFacade->edit($program, $values);
            $this->getPresenter()->flashMessage('Program byl upraven.');
        } else {
            $program = $this->programFacade->add($customer, $values);
            $this->getPresenter()->flashMessage('Program byl vytvořen.');
        }
        $this->getPresenter()->redirect('Customer:edit', $customer->getId());
    }

    private function _validateDate($date, $format = 'd.m.Y H:i'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
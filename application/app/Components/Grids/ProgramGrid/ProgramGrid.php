<?php declare(strict_types=1);

namespace App\Components\Grids\ProgramGrid;

use App\Base\Components\BaseControl;
use App\Model\Entity\Customer;
use App\Model\Entity\Program;
use App\Model\Facade\ProgramFacade;
use Nette\Utils\Html;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\DataSource\DoctrineDataSource;
use Ublaboo\DataGrid\Localization\SimpleTranslator;

interface IProgramGrid
{
    /**
     * @param Customer|null $customer
     * @return ProgramGrid
     */
    public function create(?Customer $customer = null): ProgramGrid;
}

class ProgramGrid extends BaseControl
{
    /** @var Customer|null $customer */
    private ?Customer $customer;

    /** @var ProgramFacade */
    private ProgramFacade $programFacade;

    public function render()
    {
        $this->template->render(__DIR__ . '/programGrid.latte');
    }

    public function __construct(?Customer $customer, ProgramFacade $programFacade)
    {
        $this->customer = $customer;
        $this->programFacade = $programFacade;
    }

    public function createComponentGrid(): DataGrid
    {
        $grid = new DataGrid();

        $queryBuilder = $this->getEM()->createQueryBuilder();
        $programQuery = $queryBuilder->select('p')
            ->from(Program::class, 'p');

        $customer = $this->customer;

        if ($customer) {
            $programQuery->where('p.customer = :customer')
                ->setParameter('customer', $customer);
        }

        $datasource = new DoctrineDataSource($programQuery, 'p.id');
        $grid->setDefaultPerPage(50);
        $grid->setDataSource($datasource);

        $grid->setStrictSessionFilterValues(false);
        $grid->setDefaultPerPage(50);
        $grid->setRememberState(false);

        $grid->addColumnText('id', 'id');

        $grid->addColumnText('name', 'Název');

        if (!$customer) {
            $grid->addColumnText('customer', 'Zákazník')
                ->setRenderer(function (Program $program) {
                    return $program->getCustomer()->getName();
                });
        }

        $grid->addColumnDateTime('broadcastingDate', 'Vysílací čas')
            ->setFormat('d.m.Y H:i:s');

        $grid->addColumnText('description', 'Popis');

        $grid->addColumnDateTime('createdDate', 'Datum vytvoření')
            ->setFormat('d.m.Y H:i:s');

        $grid->addAction('edit', 'Editovat', 'Program:edit')
            ->setIcon('edit')
            ->setTitle('Editovat');

        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setRenderer(function (Program $program) {
                $aLink = Html::el('a');
                $aLink->setAttribute('href', $this->link('delete!', [
                    'id' => $program->getCustomer()->getId(),
                    'programId' => $program->getId(),
                ]));
                $aLink->setText('Smazat');
                $aLink->setAttribute('class', 'btn btn-xs btn-default btn-secondary');
                return $aLink;
            })
            ->setConfirmation(
                new StringConfirmation('Opravdu chcete tohoto zákazníka smazat?'));

        $translator = new SimpleTranslator([
            'ublaboo_datagrid.no_item_found_reset' => 'Žádné položky nenalezeny. Filtr můžete vynulovat',
            'ublaboo_datagrid.no_item_found' => 'Žádné položky nenalezeny.',
            'ublaboo_datagrid.here' => 'zde',
            'ublaboo_datagrid.items' => 'Položky',
            'ublaboo_datagrid.all' => 'všechny',
            'ublaboo_datagrid.from' => 'z',
            'ublaboo_datagrid.reset_filter' => 'Resetovat filtr',
            'ublaboo_datagrid.group_actions' => 'Hromadné akce',
            'ublaboo_datagrid.show_all_columns' => 'Zobrazit všechny sloupce',
            'ublaboo_datagrid.hide_column' => 'Skrýt sloupec',
            'ublaboo_datagrid.action' => 'Akce',
            'ublaboo_datagrid.previous' => 'Předchozí',
            'ublaboo_datagrid.next' => 'Další',
            'ublaboo_datagrid.choose' => 'Vyberte',
            'ublaboo_datagrid.execute' => 'Provést',
            'ublaboo_datagrid.per_page_submit' => 'Změnit',
        ]);

        $grid->setTranslator($translator);

        return $grid;
    }

    public function handleDelete(?string $programId)
    {
        /** @var Program $program */
        $program = $this->getEM()->getRepository(Program::class)->find($programId);
        $customer = $this->customer;

        if ($program) {
            $this->programFacade->delete($program);
            $this->getPresenter()->flashMessage('Program byl smazán.');
        } else {
            $this->getPresenter()->flashMessage('Program nelze smazat.');
        }
        if ($customer) {
            $this->getPresenter()->redirect('Customer:edit', $customer->getId());
        }
        $this->getPresenter()->redirect('Program:default');
    }
}


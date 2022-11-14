<?php declare(strict_types=1);

namespace App\Components\Grids\CustomerGrid;

use App\Base\Components\BaseControl;
use App\Model\Entity\Customer;
use App\Model\Facade\CustomerFacade;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\DataSource\DoctrineDataSource;
use Ublaboo\DataGrid\Localization\SimpleTranslator;

interface ICustomerGrid
{
    /**
     * @return CustomerGrid
     */
    public function create(): CustomerGrid;
}

class CustomerGrid extends BaseControl
{
    /** @var CustomerFacade */
    private CustomerFacade $customerFacade;

    public function render()
    {
        $this->template->render(__DIR__ . '/customerGrid.latte');
    }

    public function __construct(CustomerFacade $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    public function createComponentGrid(): DataGrid
    {
        $grid = new DataGrid();

        $queryBuilder = $this->getEM()->createQueryBuilder();
        $customerQuery = $queryBuilder->select('c')
            ->from(Customer::class, 'c');

        $datasource = new DoctrineDataSource($customerQuery, 'c.id');
        $grid->setDataSource($datasource);

        $grid->setStrictSessionFilterValues(false);
        $grid->setDefaultPerPage(50);
        $grid->setRememberState(false);


        $grid->addColumnText('id', 'id');

        $grid->addColumnText('name', 'Název');

        $grid->addColumnText('note', 'Poznámka');

        $grid->addColumnDateTime('createdDate', 'Datum vytvoření')
            ->setFormat('d.m.Y H:i:s');

        $grid->addAction('edit', 'Editovat', 'Customer:edit')
            ->setIcon('edit')
            ->setTitle('Editovat');

        $grid->addAction('delete', 'Smazat', 'delete!')
            ->setIcon('delete')
            ->setTitle('Smazat')
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

    public function handleDelete(?string $id)
    {
        /** @var Customer $customer */
        $customer = $this->getEM()->getRepository(Customer::class)->find($id);

        if ($customer) {
            $this->customerFacade->delete($customer);
            $this->getPresenter()->flashMessage('Zákazník byl smazán.');
        } else {
            $this->getPresenter()->flashMessage('Zákazník nelze smazat.');
        }
        $this->getPresenter()->redirect('Customer:default');
    }
}


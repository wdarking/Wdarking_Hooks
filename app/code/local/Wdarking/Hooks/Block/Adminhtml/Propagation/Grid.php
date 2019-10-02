<?php
class Wdarking_Hooks_Block_Adminhtml_Propagation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("propagationGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("hooks/propagation")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn("id", array(
            "header" => Mage::helper("hooks")
                ->__("ID") ,
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "id",
        ));

        $this->addColumn("event", array(
            "header" => Mage::helper("hooks")
                ->__("Propagator Event") ,
            "index" => "event",
        ));
        $this->addColumn("data", array(
            "header" => Mage::helper("hooks")
                ->__("Propagation Data") ,
            "index" => "data",
        ));
        $this->addColumn("response_status", array(
            "header" => Mage::helper("hooks")
                ->__("Response Status") ,
            "index" => "response_status",
        ));
        $this->addColumn("response_data", array(
            "header" => Mage::helper("hooks")
                ->__("Response Data") ,
            "index" => "response_data",
        ));
        $this->addColumn('tries', array(
            'header' => Mage::helper('hooks')
                ->__('Tries') ,
            'index' => 'tries',
        ));
        $this->addColumn('propagated_at', array(
            'header' => Mage::helper('hooks')
                ->__('Propagated At') ,
            'index' => 'propagated_at',
            'type' => 'datetime',
        ));
        $this->addColumn('backoff_at', array(
            'header' => Mage::helper('hooks')
                ->__('Backoff At') ,
            'index' => 'backoff_at',
            'type' => 'datetime',
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')
            ->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')
            ->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return '#';
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()
            ->setFormFieldName('ids');
        $this->getMassactionBlock()
            ->setUseSelectAll(true);
        $this->getMassactionBlock()
            ->addItem('remove_propagation', array(
            'label' => Mage::helper('hooks')
                ->__('Remove Propagation') ,
            'url' => $this->getUrl('*/adminhtml_propagation/massRemove') ,
            'confirm' => Mage::helper('hooks')
                ->__('Are you sure?')
        ));
        return $this;
    }

}


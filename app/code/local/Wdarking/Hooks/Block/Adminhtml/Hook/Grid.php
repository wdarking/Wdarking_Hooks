<?php
class Wdarking_Hooks_Block_Adminhtml_Hook_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("hookGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("hooks/hook")->getCollection();
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
                ->__("Event") ,
            "index" => "event",
        ));
        $this->addColumn("url", array(
            "header" => Mage::helper("hooks")
                ->__("URL") ,
            "index" => "url",
        ));
        $this->addColumn("token", array(
            "header" => Mage::helper("hooks")
                ->__("Token") ,
            "index" => "token",
        ));
        $this->addColumn("enabled", array(
            "header" => Mage::helper("hooks")
                ->__("Enabled") ,
            "index" => "enabled",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')
            ->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')
            ->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl("*/*/edit", array(
            "id" => $row->getId()
        ));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()
            ->setFormFieldName('ids');
        $this->getMassactionBlock()
            ->setUseSelectAll(true);
        $this->getMassactionBlock()
            ->addItem('remove_hook', array(
            'label' => Mage::helper('hooks')
                ->__('Remove Hook') ,
            'url' => $this->getUrl('*/adminhtml_hook/massRemove') ,
            'confirm' => Mage::helper('hooks')
                ->__('Are you sure?')
        ));
        return $this;
    }

}


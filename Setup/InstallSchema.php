<?php
 
namespace Logic\CustomMenu\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        
        $installer = $setup;
        $installer->startSetup();
        //
        $tableName = $installer->getTable('logic_menu_blocks');
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'menu_id',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Menu ID'
            )
            ->addColumn(
                'menu_identifier',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Menu Identifier'
            )
            ->addColumn(
                'menu_title',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Menu Title'
            )
            ->addColumn(
                'menu_order',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Menu Order'
            )
            ->addColumn(
                'menu_col',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Menu Order'
            )
            ->addColumn(
                'menu_parent',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Menu Order'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Active'
            )
            ->addColumn(
                'creation_time',
                Table::TYPE_TIMESTAMP,
                null,
                array(),//['nullable' => false]
                'Creation Time'
            )
            ->addColumn(
                'update_time',
                Table::TYPE_TIMESTAMP,
                null,
                array(),//['nullable' => false]
                'Modification Time'
            )
            ->setComment('CustomMenu Blocks Table');
        $installer->getConnection()->createTable($table);
        //
        $tableName = $installer->getTable('logic_menu_style');
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Style ID'
            )
            ->addColumn(
                'background_color',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'background_color'
            )
            ->addColumn(
                'background_color_hover',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Background Color Hover'
            )
            ->addColumn(
                'text_color',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Text Color'
            )
            ->addColumn(
                'text_color_hover',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false, 'default' => ''],
                'Text Color Hover'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Active'
            )
            ->addColumn(
                'creation_time',
                Table::TYPE_TIMESTAMP,
                null,
                array(),//['nullable' => false]
                'Creation Time'
            )
            ->addColumn(
                'update_time',
                Table::TYPE_TIMESTAMP,
                null,
                array(),//['nullable' => false]
                'Modification Time'
            )
            ->setComment('CustomMenu Style Table');
        $installer->getConnection()->createTable($table);
        //
        $installer->endSetup();
    }
}
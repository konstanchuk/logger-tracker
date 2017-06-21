<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace Konstanchuk\LoggerTracker\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
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
        $this->createLoggerTrackerTable($installer);
        $installer->endSetup();
    }

    protected function createLoggerTrackerTable(SchemaSetupInterface $installer)
    {
        $tableName = $installer->getTable('konstanchuk_logger_tracker');
        if ($installer->getConnection()->isTableExists($tableName) == true) {
            return false;
        }
        $table = $installer->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_BIGINT,
                50,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'ID'
            )
            ->addColumn(
                'level',
                Table::TYPE_INTEGER,
                4,
                ['nullable' => false],
                'Level'
            )
            ->addColumn(
                'text',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Text'
            )
            ->addColumn(
                'notification',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => false],
                'Text'
            )
            ->addColumn(
                'read_mark',
                Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => false],
                'Read Mark'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created At'
            )
            ->setComment('Konstanchuk Logger Tracker Table')
            ->setOption('type', 'InnoDB')
            ->setOption('charset', 'utf8');
        $installer->getConnection()->createTable($table);
    }
}
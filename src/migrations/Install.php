<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/transformer/license
 * @link       https://www.flipboxfactory.com/software/transformer/
 */

namespace flipbox\transformer\migrations;

use craft\db\Migration;
use craft\records\Site as SiteRecord;
use flipbox\transformer\records\Transformer as TransformerRecord;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Install extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->createIndexes();
        $this->addForeignKeys();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        // Delete tables
        $this->dropTableIfExists(TransformerRecord::tableName());

        return true;

    }

    /**
     * Creates the tables.
     *
     * @return void
     */
    protected function createTables()
    {

        $this->createTable(TransformerRecord::tableName(), [
            'id' => $this->primaryKey(),
            'handle' => $this->string()->notNull(),
            'class' => $this->string()->notNull(),
            'type' => $this->string()->notNull(),
            'scope' => $this->string()->notNull(),
            'config' => $this->text(),
            'siteId' => $this->integer(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid()
        ]);

    }

    /**
     * Creates the indexes.
     *
     * @return void
     */
    protected function createIndexes()
    {

        $this->createIndex(
            $this->db->getIndexName(TransformerRecord::tableName(), 'handle', false, true),
            TransformerRecord::tableName(), 'handle', false
        );
        $this->createIndex(
            $this->db->getIndexName(TransformerRecord::tableName(), 'handle', false),
            TransformerRecord::tableName(), 'handle', false
        );
        $this->createIndex(
            $this->db->getIndexName(TransformerRecord::tableName(), 'handle,type,scope,siteId', true),
            TransformerRecord::tableName(), 'handle,type,scope,siteId', true
        );
        $this->createIndex(
            $this->db->getIndexName(TransformerRecord::tableName(), 'siteId', false, true),
            TransformerRecord::tableName(), 'siteId', false
        );

    }

    /**
     * Adds the foreign keys.
     *
     * @return void
     */
    protected function addForeignKeys()
    {

        $this->addForeignKey(
            $this->db->getForeignKeyName(TransformerRecord::tableName(), 'siteId'),
            TransformerRecord::tableName(), 'siteId', SiteRecord::tableName(), 'id', 'CASCADE', 'CASCADE'
        );

    }

}

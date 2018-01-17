<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Mindy\Bundle\SocialBundle\Model\SocialUser;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171013130701 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $socialUserTable = $schema->createTable(SocialUser::tableName());
        $socialUserTable->addColumn('id', 'integer', ['length' => 11, 'unsigned' => true, 'autoincrement' => true]);
        $socialUserTable->setPrimaryKey(['id']);
        $socialUserTable->addColumn('params', 'text', ['notnull' => false]);
        $socialUserTable->addColumn('provider', 'string', ['length' => 255]);
        $socialUserTable->addColumn('owner_id', 'string', ['length' => 255]);
        $socialUserTable->addColumn('user_id', 'integer', ['length' => 11, 'notnull' => true]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(SocialUser::tableName());
    }
}

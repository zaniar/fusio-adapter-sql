<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\Sql\Tests\Connection;

use Doctrine\DBAL\Connection;
use Fusio\Adapter\Sql\Connection\SqlAdvanced;
use Fusio\Engine\Connection\PingableInterface;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Form\Element\Input;
use Fusio\Engine\Parameters;
use Fusio\Engine\Test\EngineTestCaseTrait;

/**
 * SqlAdvancedTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SqlAdvancedTest extends \PHPUnit_Framework_TestCase
{
    use EngineTestCaseTrait;

    public function testGetConnection()
    {
        /** @var SqlAdvanced $connectionFactory */
        $connectionFactory = $this->getConnectionFactory()->factory(SqlAdvanced::class);

        $config = new Parameters([
            'url' => 'sqlite:///:memory:',
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertInstanceOf(Connection::class, $connection);
    }

    public function testConfigure()
    {
        $connection = $this->getConnectionFactory()->factory(SqlAdvanced::class);
        $builder    = new Builder();
        $factory    = $this->getFormElementFactory();

        $connection->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());

        $elements = $builder->getForm()->getProperty('element');
        $this->assertEquals(1, count($elements));
        $this->assertInstanceOf(Input::class, $elements[0]);
    }

    public function testPing()
    {
        /** @var SqlAdvanced $connectionFactory */
        $connectionFactory = $this->getConnectionFactory()->factory(SqlAdvanced::class);

        $config = new Parameters([
            'url' => 'sqlite:///:memory:',
        ]);

        $connection = $connectionFactory->getConnection($config);

        $this->assertInstanceOf(PingableInterface::class, $connection);
        $this->assertTrue($connectionFactory->ping($connection));
    }
}

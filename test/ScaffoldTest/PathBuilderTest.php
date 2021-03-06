<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace ScaffoldTest;

use Scaffold\Config;
use Scaffold\PathBuilder;

class PathBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testAddPart()
    {
        $builder = new PathBuilder(new Config());
        $this->assertSame($builder, $builder->addPart('user'));
    }

    public function testGetModuleBase()
    {
        $builder = new PathBuilder(new Config());
        $builder->setModule('user');

        $this->assertSame('module/User/', $builder->getModuleBase());
    }

    public function testGetModuleBaseOnBare()
    {
        $config = new Config();
        $config->setBare(true);

        $builder = new PathBuilder($config);
        $builder->setModule('user');

        $this->assertSame('', $builder->getModuleBase());
    }

    public function testGetSourcePath()
    {
        $builder = new PathBuilder(new Config());
        $builder->setModule('User');
        $builder->addPart('Group');
        $builder->addPart('Member');

        $this->assertSame('module/User/src/User/Group/Member.php', $builder->getSourcePath());
    }

    public function testGetSourcePathWithComplexClass()
    {
        $builder = new PathBuilder(new Config());
        $builder->setModule('User');
        $builder->addPart('Group');
        $builder->addPart('Masson\Member');

        $this->assertSame('module/User/src/User/Group/Masson/Member.php', $builder->getSourcePath());
    }

    public function testGetRawPath()
    {
        $builder = new PathBuilder(new Config());
        $builder->setModule('User');
        $builder->addPart('Module');

        $this->assertSame('module/User/Module.php.dist', $builder->getRawPath('php.dist'));
    }

    public function testGetTestPath()
    {
        $builder = new PathBuilder(new Config());
        $builder->setModule('User');
        $builder->addPart('Group');
        $builder->addPart('Member');

        $this->assertSame('module/User/test/UserTest/Group/MemberTest.php', $builder->getTestPath());
    }
}

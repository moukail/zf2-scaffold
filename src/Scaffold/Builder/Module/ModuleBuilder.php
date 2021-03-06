<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Module;

use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\RawGenerator;
use Scaffold\Config;
use Scaffold\Model;
use Scaffold\State;

class ModuleBuilder extends AbstractBuilder
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * Prepare models
     *
     * @param State|State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart('Module')
            ->getNamespace();

        $pathBuilder = $this->buildPath();

        if ($this->config->getBare()) {
            $pathBuilder->addPart('src');
            $pathBuilder->addPart($this->config->getModule());
        }

        $path = $pathBuilder->setModule($this->config->getModule())
            ->addPart('Module')
            ->getRawPath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'module');
    }

    /**
     * Build generators
     *
     * @param State|State $state
     */
    public function build(State $state)
    {
        $model = $state->getModel('module');
        if ($this->config->getBare()) {
            $data = file_get_contents(SCAFFOLD_ROOT . "/data/template/src/Module.bare.php");
        }
        else {
            $data = file_get_contents(SCAFFOLD_ROOT . "/data/template/src/Module.php");
        }
        $data = str_replace('__NAMESPACE_PLACEHOLDER__', ucfirst($this->config->getModule()), $data);
        $data = substr($data, 7);
        $model->setGenerator(new RawGenerator($data));
    }

}

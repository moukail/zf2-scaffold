<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Builder\Options;


use Scaffold\Builder\AbstractBuilder;
use Scaffold\Code\Generator\TraitGenerator;
use Scaffold\Model;
use Scaffold\State;
use Zend\Code\Generator\DocBlock\Tag;

class OptionsTraitBuilder extends AbstractBuilder
{

    /**
     * Prepare models
     *
     * @param \Scaffold\State $state
     */
    public function prepare(State $state)
    {
        $model = new Model();
        $name = $this->buildNamespace()
            ->addPart($this->config->getModule())
            ->addPart($this->config->getName() . 'OptionsTrait')
            ->getNamespace();

        $path = $this->buildPath()
            ->setModule($this->config->getModule())
            ->addPart($this->config->getName() . 'OptionsTrait')
            ->getSourcePath();

        $model->setName($name);
        $model->setPath($path);
        $state->addModel($model, 'options-trait');
    }

    /**
     * Build generators
     *
     * @param  State|\Scaffold\State $state
     * @return \Scaffold\State|void
     */
    public function build(State $state)
    {
        $model = $state->getModel('options-trait');
        $options = $state->getModel('options');

        $generator = new TraitGenerator($model->getName());
        $generator->addUse($options->getName());
        $generator->addUse($state->getModel('RuntimeException')->getName());
        $generator->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $generator->addUse('Zend\ServiceManager\ServiceLocatorInterface');

        $property = lcfirst($options->getClassName());
        $class = $options->getClassName();
        $alias = $options->getServiceName();

        $code
            = <<<EOF
if (null === \$this->$property) {
    if (\$this instanceof ServiceLocatorAwareInterface || method_exists(\$this, 'getServiceLocator')) {
        \$this->$property = \$this->getServiceLocator()->get('$alias');
    } else {
        if (property_exists(\$this, 'serviceLocator')
            && \$this->serviceLocator instanceof ServiceLocatorInterface
        ) {
            \$this->$property = \$this->serviceLocator->get('$alias');
        } else {
            throw new RuntimeException('Service locator not found');
        }
    }
}
return \$this->$property;
EOF;

        $this->addProperty($generator, $property, $class);
        $this->addSetter($generator, $property, $class);

        $getter = $this->getGetter($property, $class);
        $getter->setBody($code);
        $getter->getDocBlock()->setTag(
            new Tag(['name' => 'throws', 'description' => $state->getModel('RuntimeException')->getClassName()])
        );
        $generator->addMethodFromGenerator($getter);
//
        $model->setGenerator($generator);
    }
} 
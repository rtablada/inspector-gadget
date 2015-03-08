<?php  namespace Rtablada\InspectorGadget;

use Illuminate\Foundation\Application;

class GadgetFactory
{
    /**
     * @var \Illuminate\Contracts\Console\Application
     */
    private $app;

    protected $aliases = [];

    protected $namespace = '';

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function make($class)
    {
        $gadget = $this->app->make($this->getClass($class));

        $args = func_get_args();
        array_shift($args);

        return call_user_func_array([$gadget, 'render'], $args);
    }

    protected function getClass($class)
    {
        if (isset($this->aliases[$class])) {
            return $this->aliases[$class];
        }

        $inNamespace = $this->namespace . '\\' . $class;

        if (class_exists($inNamespace)) {
            return $inNamespace;
        }

        return $class;
    }

    public function registerAliases(array $aliases)
    {
        $this->aliases = array_merge($this->aliases, $aliases);
    }
}

<?php  namespace Rtablada\InspectorGadget;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class GadgetServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('gadget', function ($app) {
            return new GadgetFactory($app);
        });
    }

    public function boot()
    {
        $app = $this->app;
        $view =$this->app['view'];
        $gadgetFactory = $this->app['gadget'];

        $view->share('gadgetFactory', $gadgetFactory);
        $blade = $view->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->extend(function($view, BladeCompiler $compiler) {
            $pattern = $compiler->createMatcher('gadget');

            return preg_replace($pattern, '<?php echo app(\'gadget\')->make$2; ?>', $view);
        });

        $aliases = $this->app['config']->get('inspector-gadget.aliases', []);
        $namespace = $this->app['config']->get('inspector-gadget.namespace');

        $gadgetFactory->registerAliases($aliases);
        $gadgetFactory->setNamespace($namespace);
    }

    public function provides()
    {
        return ['gadget'];
    }
}

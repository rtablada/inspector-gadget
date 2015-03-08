<?php  namespace Rtablada\InspectorGadget\Test;

use Illuminate\Foundation\Application;
use Rtablada\InspectorGadget\GadgetFactory;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class GadgetFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $application;
    protected $factory;
    /**
     * @before
     */
    public function setupClass()
    {
        $this->application = m::mock('Illuminate\Foundation\Application');
        $this->factory = new GadgetFactory($this->application);
    }

    public function test_can_render_basic_gadget()
    {
        $this->application->shouldReceive('make')->andReturn(new BasicTestGadget);
        $result = $this->factory->make('BasicTestGadget');

        $this->assertEquals('basic test', $result);
    }

    public function test_can_render_gadget_with_one_argument()
    {
        $this->application->shouldReceive('make')->andReturn(new OneArgumentTestGadget);
        $result = $this->factory->make('OneArgumentTestGadget', 'one');

        $this->assertEquals('one', $result);
    }

    public function test_can_render_gadget_with_multiple_argument()
    {
        $this->application->shouldReceive('make')->andReturn(new MultipleArgumentTestGadget);
        $result = $this->factory->make('MultipleArgumentTestGadget', 'one', 'two');

        $this->assertEquals('one two', $result);
    }

    public function tearDown()
    {
        m::close();
    }
}

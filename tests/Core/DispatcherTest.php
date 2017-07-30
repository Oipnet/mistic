<?php
use App\Middleware\Exceptions\MiddlewareNotFound;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /**
     * @var \Core\Dispatcher
     */
    private $dispatcher;

    /**
     * @var \Prophecy\Prophet
     */
    private $prophet;

    public function setUp()
    {
        $this->dispatcher = new \Core\Dispatcher();
        $this->prophet = new \Prophecy\Prophet();
    }

    /**
     * @test
     */
    public function add_a_middleware_interface_class_to_the_middleware_list()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)
            ->getMock();

        $this->dispatcher->pipe($middleware);

        $this->assertCount(1, $this->dispatcher->getMiddlewares());
        $this->assertInstanceOf(MiddlewareInterface::class, $this->dispatcher->getMiddlewares()[0]);
    }

    /**
     * @test
     */
    public function add_a_middleware_with_a_string_to_the_middleware_list()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)
            ->getMock();

        $middlewareClass = get_class($middleware);

        $this->dispatcher->pipe($middlewareClass);

        $this->assertCount(1, $this->dispatcher->getMiddlewares());
        $this->assertInstanceOf(MiddlewareInterface::class, $this->dispatcher->getMiddlewares()[0]);
    }

    /**
     * @test
     * @expectedException App\Middleware\Exceptions\MiddlewareNotFound
     */
    public function add_not_middleware_interface_class_to_the_middleware_list()
    {
        $middleware = $this->getMockBuilder(\DateTime::class)
            ->getMock();

        $this->dispatcher->pipe($middleware);
    }

    /**
     * @test
     * @expectedException App\Middleware\Exceptions\MiddlewareNotFound
     */
    public function add_not_middleware_interface_with_a_string_to_the_middleware_list()
    {
        $middleware = $this->getMockBuilder(\DateTime::class)
            ->getMock();

        $middlewareClass = get_class($middleware);

        $this->dispatcher->pipe($middlewareClass);
    }

}
<?php
use App\Middleware\Exceptions\MiddlewareNotFound;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherTest extends TestCase
{
    /**
     * @var \Core\Dispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $container = \DI\ContainerBuilder::buildDevContainer();
        $container->set(ResponseInterface::class, new \GuzzleHttp\Psr7\Response());

        $this->dispatcher = new \Core\Dispatcher($container->get(ResponseInterface::class), $container);
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

    /**
     * @test
     */
    public function process_should_return_a_response_when_there_is_no_middleware()
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();

        $this->assertInstanceOf(ResponseInterface::class, $this->dispatcher->process($request));
    }

    /**
     * @test
     */
    public function procces_should_return_a_response_modified_by_middleware()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)
            ->getMock();

        $middleware->method('process')
            ->willReturn(new \GuzzleHttp\Psr7\Response(999));

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();

        $this->dispatcher->pipe($middleware);

        $response = $this->dispatcher->process($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(999, $response->getStatusCode());
    }

}
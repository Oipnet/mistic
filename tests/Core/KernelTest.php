<?php


/**
 * Class KernelTest
 */
class KernelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Core\Kernel
     */
    private $kernel;

    public function setUp()
    {
        $this->kernel = new \Core\Kernel();
    }

    /**
     * @test
     */
    public function initialisation_is_instance_of_kernel()
    {
        $this->assertInstanceOf(\Core\Kernel::class, $this->kernel);
        $this->assertInstanceOf(\Psr\Container\ContainerInterface::class, $this->kernel->getContainer());
    }

    /**
     * @test
     */
    public function run_kernell_should_return_a_response()
    {
        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $this->kernel->run());
    }
}

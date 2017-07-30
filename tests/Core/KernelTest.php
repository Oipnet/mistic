<?php


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
    }

    /**
     * @test
     */
    public function run_kernell_should_call_http_response_send()
    {
        $stub = $this
            ->getMockBuilder(\Core\Kernel::class)
            ->setMethods(['sendResponse'])
            ->getMock()
        ;

        $stub->expects($this->once())->method('sendResponse');

        $stub->run();
    }
}

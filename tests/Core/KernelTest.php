<?php


class KernelTest extends \PHPUnit\Framework\TestCase
{
    public function testKernelInitialisationIsInstanceOfKernel()
    {
        $kernel = new \Core\Kernel();

        $this->assertInstanceOf(\Core\Kernel::class, $kernel);
    }
}

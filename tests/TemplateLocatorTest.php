<?php
namespace Qiq;

class TemplateLocatorTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        $this->templateLocator = $this->newTemplateLocator();
        $this->templateLocator->clear();
    }

    protected function newTemplateLocator(array $paths = [])
    {
        return new TemplateLocator($paths, '.php', new Compiler\FakeCompiler());
    }

    public function testHasGet()
    {
        $this->templateLocator->setPaths([__DIR__ . '/templates']);

        $this->assertTrue($this->templateLocator->has('index'));
        $actual = $this->templateLocator->get('index');

        $expect = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/templates/index.php');
        $this->assertSame($expect, $actual);

        $this->assertFalse($this->templateLocator->has('no-such-template'));
        $this->expectException(Exception\TemplateNotFound::CLASS);
        $this->templateLocator->get('no-such-template');
    }

    public function testDoubleDots()
    {
        $this->expectException(Exception\TemplateNotFound::CLASS);
        $this->expectExceptionMessage("Double-dots not allowed in template specifications");
        $this->templateLocator->get('foo/../bar');
    }

    public function testSetAndGetPaths()
    {
        // should be no paths yet
        $expect = [];
        $actual = $this->templateLocator->getPaths();
        $this->assertSame($expect, $actual);

        // set the paths
        $expect = ['__DEFAULT__' => [
            DIRECTORY_SEPARATOR . 'foo',
            DIRECTORY_SEPARATOR . 'bar',
            DIRECTORY_SEPARATOR . 'baz',
        ]];
        $this->templateLocator->setPaths(['/foo', '/bar', '/baz']);
        $actual = $this->templateLocator->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testPrependPath()
    {
        $this->templateLocator->prependPath('/foo');
        $this->templateLocator->prependPath('/bar');
        $this->templateLocator->prependPath('/baz');

        $expect = ['__DEFAULT__' => [
            DIRECTORY_SEPARATOR . 'baz',
            DIRECTORY_SEPARATOR . 'bar',
            DIRECTORY_SEPARATOR . 'foo',
        ]];
        $actual = $this->templateLocator->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testAppendPath()
    {
        $this->templateLocator->appendPath('/foo');
        $this->templateLocator->appendPath('/bar');
        $this->templateLocator->appendPath('/baz');

        $expect = ['__DEFAULT__' => [
            DIRECTORY_SEPARATOR . 'foo',
            DIRECTORY_SEPARATOR . 'bar',
            DIRECTORY_SEPARATOR . 'baz',
        ]];
        $actual = $this->templateLocator->getPaths();
        $this->assertSame($expect, $actual);
    }

    public function testFindFallbacks()
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR
            . 'templates' . DIRECTORY_SEPARATOR;

        $templateLocator = $this->newTemplateLocator([
            $dir . 'foo',
        ]);

        $this->assertOutput('foo', $templateLocator->get('test'));

        $templateLocator = $this->newTemplateLocator([
            $dir . 'bar',
            $dir . 'foo',
        ]);
        $this->assertOutput('bar', $templateLocator->get('test'));

        $templateLocator = $this->newTemplateLocator([
            $dir . 'baz',
            $dir . 'bar',
            $dir . 'foo',
        ]);
        $this->assertOutput('baz', $templateLocator->get('test'));

        // get it again for code coverage
        $this->assertOutput('baz', $templateLocator->get('test'));

        // look for a file that doesn't exist
        $templateLocator->setExtension('.phtml');
        $this->expectException(Exception\TemplateNotFound::CLASS);
        $templateLocator->get('test');
    }

    public function testCollections()
    {
        $dir = __DIR__ . '/templates';

        $this->templateLocator->setPaths([
            "foo:{$dir}/foo",
            "bar:{$dir}/bar",
            "baz:{$dir}/baz",
        ]);

        $this->assertOutput('foo', $this->templateLocator->get('foo:test'));
        $this->assertOutput('bar', $this->templateLocator->get('bar:test'));
        $this->assertOutput('baz', $this->templateLocator->get('baz:test'));
    }

    protected function assertOutput(string $expect, string $file) : void
    {
        ob_start();
        require $file;
        $actual = ob_get_clean();
        $this->assertSame($expect, $actual);
    }
}

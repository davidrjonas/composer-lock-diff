<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class CliTest extends TestCase
{
    public function testDefaultOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );
    }

    public function testHelpOutput()
    {
        $output = $this->runComposerLockDiff(
            '--help'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/helpOutput.txt',
            $output
        );
    }

    public function testMarkdownOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--md'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/markdownOutput.txt',
            $output
        );
    }

    public function testMarkdownNoLinksOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--md',
            '--no-links'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/markdownNoLinksOutput.txt',
            $output
        );
    }

    public function testNoLinksOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--no-links'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/noLinksOutput.txt',
            $output
        );
    }

    public function testJsonPrettyOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--json',
            '--pretty'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/jsonPrettyOutput.txt',
            $output
        );
    }

    public function testOnlyDevOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--only-dev'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/onlyDevOutput.txt',
            $output
        );
    }

    public function testOnlyProdOutput()
    {
        $output = $this->runComposerLockDiff(
            '--from=tests/locks/base/composer.from.lock',
            '--to=tests/locks/base/composer.to.lock',
            '--only-prod'
        );
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/onlyProdOutput.txt',
            $output
        );
    }


    public function testVcsUnknown()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(1);

        $this->runComposerLockDiff('--vcs UNKNOWN');
    }

    protected function runComposerLockDiff(string ...$args): string
    {
        chdir(dirname(__DIR__));
        $escapedArgs = [];
        foreach ($args as $arg) {
            $escapedArgs[] = escapeshellarg($arg);
        }
        $command = './composer-lock-diff '
            . implode(' ', $escapedArgs)
            . ' 2>&1';

        return $this->execAndReturn($command);
    }

    protected function execAndReturn($command): string
    {
        $lastLine = exec($command, $lines, $exitCode);
        if ($exitCode !== 0) {
            throw new \Exception(
                'Error running with exit code ' . $exitCode . ': ' . $command
                    . "\n" . $lastLine,
                $exitCode
            );
        }
        return implode("\n", $lines) . "\n";
    }
}

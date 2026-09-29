<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class GitTest extends TestCase
{
    public function setUp(): void
    {
        exec('git --help 2>&1', $dummy, $exitCode);
        if ($exitCode !== 0) {
            $this->markTestSkipped('"git" executable not found');
        }

        $this->cleanUp();
    }

    public function tearDown(): void
    {
        $this->cleanUp();
    }

    protected function cleanUp(): void
    {
        if (!is_dir(__DIR__ . '/tmp')) {
            return;
        }
        $this->assertTrue(chdir(__DIR__ . '/tmp/'));
        exec('rm -rf .git *');
    }

    public function testGitDirect()
    {
        $tmpDir = __DIR__ . '/tmp';
        copy(__DIR__ . '/locks/base/composer.from.json', $tmpDir . '/composer.json');
        copy(__DIR__ . '/locks/base/composer.from.lock', $tmpDir . '/composer.lock');

        chdir($tmpDir);
        $this->exec('git init --initial-branch=main');
        $this->exec('git add composer.*');
        $this->exec('git commit -m "Initial commit"');

        copy(__DIR__ . '/locks/base/composer.to.json', $tmpDir . '/composer.json');
        copy(__DIR__ . '/locks/base/composer.to.lock', $tmpDir . '/composer.lock');

        $output = $this->execAndReturn('../../composer-lock-diff');
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );
    }

    public function testGitSubdir()
    {
        $tmpDir = __DIR__ . '/tmp';
        mkdir($tmpDir . '/subdir', 0777, true);
        copy(__DIR__ . '/locks/base/composer.from.json', $tmpDir . '/subdir/composer.json');
        copy(__DIR__ . '/locks/base/composer.from.lock', $tmpDir . '/subdir/composer.lock');

        chdir($tmpDir);
        $this->exec('git init --initial-branch=main');
        $this->exec('git add subdir/composer.*');
        $this->exec('git commit -m "Initial commit"');

        copy(__DIR__ . '/locks/base/composer.to.json', $tmpDir . '/subdir/composer.json');
        copy(__DIR__ . '/locks/base/composer.to.lock', $tmpDir . '/subdir/composer.lock');

        $output = $this->execAndReturn('../../composer-lock-diff --path subdir/');
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );
    }

    public function testGitSubdirInSubdir()
    {
        $tmpDir = __DIR__ . '/tmp';
        mkdir($tmpDir . '/subdir', 0777, true);
        copy(__DIR__ . '/locks/base/composer.from.json', $tmpDir . '/subdir/composer.json');
        copy(__DIR__ . '/locks/base/composer.from.lock', $tmpDir . '/subdir/composer.lock');

        chdir($tmpDir);
        $this->exec('git init --initial-branch=main');
        $this->exec('git add subdir/composer.*');
        $this->exec('git commit -m "Initial commit"');

        copy(__DIR__ . '/locks/base/composer.to.json', $tmpDir . '/subdir/composer.json');
        copy(__DIR__ . '/locks/base/composer.to.lock', $tmpDir . '/subdir/composer.lock');

        chdir($tmpDir . '/subdir');
        $output = $this->execAndReturn('../../../composer-lock-diff');
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );
    }

    protected function execAndReturn($command): string
    {
        $lastLine = exec($command . ' 2>&1', $lines, $exitCode);
        if ($exitCode !== 0) {
            throw new \Exception(
                'Error running with exit code ' . $exitCode . ': ' . $command
                    . "\n" . $lastLine,
                $exitCode
            );
        }
        return implode("\n", $lines) . "\n";
    }

    protected function exec($command): void
    {
        $lastLine = exec($command . ' 2>&1', $lines, $exitCode);
        if ($exitCode !== 0) {
            throw new \Exception(
                'Error running with exit code ' . $exitCode . ': ' . $command
                    . "\n" . $lastLine,
                $exitCode
            );
        }
    }
}

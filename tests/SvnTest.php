<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

class SvnTest extends TestCase
{
    public function setUp(): void
    {
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
        exec('rm -rf *');
    }

    public function testSvn()
    {
        exec('svn --help 2>&1', $dummy, $exitCode);
        if ($exitCode !== 0) {
            $this->markTestSkipped('"svn" executable not found');
        }

        exec('svnadmin --help 2>&1', $dummy, $exitCode);
        if ($exitCode !== 0) {
            $this->markTestSkipped('"svnadmin" executable not found');
        }

        $tmpDir = __DIR__ . '/tmp';
        mkdir($tmpDir . '/proj/trunk', 0777, true);
        copy(__DIR__ . '/locks/base/composer.from.json', $tmpDir . '/proj/trunk/composer.json');
        copy(__DIR__ . '/locks/base/composer.from.lock', $tmpDir . '/proj/trunk/composer.lock');

        chdir($tmpDir);
        exec('svnadmin create svnrepo', $dummy, $exitCode);
        $this->assertEquals(0, $exitCode);

        exec(
            'svn import ./proj file://' . $tmpDir . '/svnrepo -m "Initial commit"',
            $dummy, $exitCode
        );
        $this->assertEquals(0, $exitCode);

        exec('svn checkout file://' . $tmpDir . '/svnrepo proj-working', $dummy, $exitCode);
        $this->assertEquals(0, $exitCode);

        copy(__DIR__ . '/locks/base/composer.to.json', $tmpDir . '/proj-working/trunk/composer.json');
        copy(__DIR__ . '/locks/base/composer.to.lock', $tmpDir . '/proj-working/trunk/composer.lock');

        chdir($tmpDir . '/proj-working/trunk');
        $output = $this->execAndReturn('../../../../composer-lock-diff');
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );

        chdir($tmpDir . '/proj-working');
        $output = $this->execAndReturn('../../../composer-lock-diff -p trunk');
        $this->assertStringEqualsFile(
            __DIR__ . '/expected/defaultOutput.txt',
            $output
        );
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

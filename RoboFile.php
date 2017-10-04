<?php

use AmaTeam\Image\Projection\Test\Support\Fixture\Projection\Manager;

class RoboFile extends \Robo\Tasks // NOSONAR
{
    const COVERAGE_OPTION = 'coverage';
    const DEFAULT_TEST_OPTIONS = ['coverage' => false];

    private function runTests($suite = null, array $options = self::DEFAULT_TEST_OPTIONS)
    {
        $suite = $suite ? self::normalizeTestSuite($suite) : null;
        $directory = $suite ? ['Suite', $suite] : [];
        $coverageDir = $directory;
        $coverageDir[] = 'Coverage';
        $this
            ->taskFilesystemStack()
            ->mkdir(self::testsMetadataDir($coverageDir))
            ->run()
            ->stopOnFail();
        $task = $this
            ->taskCodecept()
            ->suite($suite)
            ->xml(self::testsMetadataDir(array_merge($directory, ['junit.xml'])))
            ->debug();
        if ($options[self::COVERAGE_OPTION]) {
            $task
                ->coverage(self::testsMetadataDir(array_merge($coverageDir, ['coverage.cov'])))
                ->coverageXml(self::testsMetadataDir(array_merge($coverageDir, ['coverage.xml'])));
        }
        return $task->run();
    }

    public function testUnit($options = self::DEFAULT_TEST_OPTIONS)
    {
        return $this->runTests('Unit', $options);
    }

    public function testFunctional($options = self::DEFAULT_TEST_OPTIONS)
    {
        return $this->runTests('Functional', $options);
    }

    public function testIntegration($options = self::DEFAULT_TEST_OPTIONS)
    {
        return $this->runTests('Integration', $options);
    }

    public function testAcceptance($options = self::DEFAULT_TEST_OPTIONS)
    {
        return $this->runTests('Acceptance', $options);
    }

    public function testCoverage()
    {
        $command = [
            self::binary('phpcov'),
            'merge',
            '--html',
            self::testsReportDir(['Coverage']),
            '--clover',
            self::testsReportDir(['coverage.clover.xml']),
            '--',
            self::testsMetadataDir()
        ];
        return $this
            ->taskExecStack()
            ->exec($command)
            ->run();
    }

    public function test()
    {
        $this->testClean()->stopOnFail();
        $this->runTests()->stopOnFail();
        return $this->testCoverage();
    }

    public function testWatch()
    {
        $entries = array_map(function ($entry) {
            return implode('/', [__DIR__, $entry]);
        }, ['src', 'tests/Suite']);
        return $this
            ->taskWatch()
            ->monitor($entries, function () {
                $this->test();
            })
            ->run();
    }

    public function testClean()
    {
        return $this
            ->taskCleanDir([
                self::testsReportDir(),
                self::testsMetadataDir()
            ])
            ->run();
    }

    public function testReport()
    {
        $this->testCoverage()->stopOnFail();
        return $this->testAllure();
    }

    public function testAllure()
    {
        $command = [
            'allure',
            'generate',
            '--clean',
            '-o',
            self::testsReportDir(['Allure']),
            '--',
            self::testsMetadataDir(['Allure'])
        ];
        return $this
            ->taskExecStack()
            ->exec($command)
            ->run();
    }

    public function testSetup()
    {
        $this
            ->taskFilesystemStack()
            ->mkdir(self::testsMetadataDir())
            ->run()
            ->stopOnFail();
        $this
            ->taskExecStack()
            ->exec([self::binary('codecept'), 'build'])
            ->run()
            ->stopOnFail();
        $manager = new Manager();
        $manager->install();
    }

    public function lint()
    {
        $rules = [
            'cleancode',
            'codesize',
            'controversial',
            'design',
            'naming',
            'unusedcode'
        ];
        $commands = [
            [
                self::binary('phpcs'),
                '--standard=PSR2',
                self::sourcesDir()
            ],
            [
                self::binary('phpmd'),
                self::sourcesDir(),
                'html',
                implode(',', $rules),
                '--reportfile',
                self::testsReportDir(['Lint'. 'phpmd.html'])
            ]
        ];
        $this
            ->taskFilesystemStack()
            ->mkdir(self::testsReportDir(['Lint']))
            ->run();
        $executor = $this
            ->taskParallelExec()
            ->printed();
        foreach ($commands as $command) {
            $command = array_map('escapeshellarg', $command);
            $executor->process(implode(' ', $command));
        }
        return $executor->run();
    }

    public function testReportPublish()
    {
        // noop for now
    }

    private static function path(array $path = [])
    {
        array_unshift($path, __DIR__);
        return implode(DIRECTORY_SEPARATOR, $path);
    }

    private static function sourcesDir(array $path = [])
    {
        array_unshift($path, 'src');
        return self::path($path);
    }

    private static function testsDir(array $path = [])
    {
        array_unshift($path, 'tests');
        return self::path($path);
    }

    private static function testsMetadataDir(array $path = [])
    {
        array_unshift($path, 'Metadata');
        return self::testsDir($path);
    }

    private static function testsReportDir(array $path = [])
    {
        array_unshift($path, 'Report');
        return self::testsDir($path);
    }

    private static function binary($name)
    {
        return self::path(['bin', $name]);
    }

    private static function normalizeTestSuite($suite)
    {
        return strtoupper($suite[0]) . strtolower(substr($suite, 1));
    }
}

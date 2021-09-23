<?php
/*
MIT License

Copyright (c) 2019 XIMDEX

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
declare(strict_types=1);

namespace PhiSYS\CodingStandard\Sniffs;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use function count;
use function explode;
use function strpos;
use const T_NAMESPACE;
use const T_STRING;
use const T_USE;

final class ContextCouplingSniff implements Sniff
{
    /** @var string */
    public $projectNamespacePrefix = '';

    /** @var array */
    public $relationshipOfContexts = [];

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_NAMESPACE,
        ];
    }

    /**
     * @param File $phpcsFile
     * @param int $stackPtr
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $startNamespace = $phpcsFile->findNext(
            [T_STRING],
            $phpcsFile->findStartOfStatement($stackPtr)
        );
        $endPosition = $phpcsFile->findEndOfStatement($startNamespace);

        $namespace = '';
        for ($i = $startNamespace; $i < $endPosition; $i++) {
            $namespace .= $tokens[$i]['content'];
        }

        $allowedNamespaces = [];
        foreach ($this->relationshipOfContexts as $context => $namespaces) {
            if (false !== strpos($namespace, $context)) {

                $allowedNamespaces = explode(',', $namespaces);
                break;
            }
        }

        if (0 === count($allowedNamespaces)) {
            return;
        }

        $uses = [];
        while (false !== $phpcsFile->findNext([T_USE], $endPosition)) {
            $startPosition = $phpcsFile->findNext(
                [T_STRING],
                $phpcsFile->findNext([T_USE], $endPosition)
            );

            $endPosition = $phpcsFile->findEndOfStatement($startPosition);

            $value = '';
            for ($i = $startPosition; $i < $endPosition; $i++) {
                $value .= $tokens[$i]['content'];
            }

            $uses[] = [
                'value' => $value,
                'stack_ptr' => $startPosition,
            ];
        }

        if (0 === count($uses)) {
            return;
        }

        foreach ($uses as $use) {
            if (false === strpos($use['value'], $this->projectNamespacePrefix)) {
                continue;
            }

            $found = false;
            foreach ($allowedNamespaces as $namespace) {
                if (false !== strpos($use['value'], $namespace)) {
                    $found = true;
                }
            }

            if (false === $found) {
                $error = 'The coupling between contexts is not allowed';
                $phpcsFile->addError($error, $use['stack_ptr'], 'BlankLineAfter');
            }
        }
    }
}

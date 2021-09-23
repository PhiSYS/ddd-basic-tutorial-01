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

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Fixer;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function sprintf;
use const T_CATCH;
use const T_COMMA;
use const T_CLOSE_CURLY_BRACKET;
use const T_DOC_COMMENT_OPEN_TAG;
use const T_SEMICOLON;
use const T_WHILE;

final class LineBreakBetweenFunctionsSniff implements Sniff
{
    public const CODE_LINE_BREAK_BETWEEN_FUNCTION = 'LineBreakBetweenFunctions';

    private const CODE_EXCEPTIONS = [
        T_COMMA,
        T_CATCH,
        T_ELSE,
        T_WHILE,
    ];

    /**
     * @return array
     */
    public function register()
    {
        return [
            T_CLOSE_CURLY_BRACKET,
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
        $line = $tokens[$stackPtr]['line'];

        $nextPointer = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);
        if (null === $nextPointer) {
            return;
        }

        $previousLinePointer = TokenHelper::findPrevious($phpcsFile, T_DOC_COMMENT_OPEN_TAG, $nextPointer);

        $next = $tokens[$nextPointer];
        $previous = null;
        if (null !== $previousLinePointer) {
            $previous = $tokens[$previousLinePointer];
        }

        if (true === in_array($next['code'], self::CODE_EXCEPTIONS)) {
            return;
        }

        if ($next['code'] !== T_CLOSE_CURLY_BRACKET && $next['line'] === ($line + 2)) {
            return;
        }

        if ($next['code'] === T_CLOSE_CURLY_BRACKET && $next['line'] === ($line + 1)) {
            return;
        }

        if (null !== $previous && T_DOC_COMMENT_OPEN_TAG === $previous['code'] && $previous['line'] === ($line + 2)) {
            return;
        }

        if (T_SEMICOLON === ($next['code'] ?? null) && T_CLOSE_CURLY_BRACKET === ($tokens[$nextPointer+2]['code'] ?? null)) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            sprintf('There must be exactly %d line break after a function.', 1),
            $stackPtr,
            self::CODE_LINE_BREAK_BETWEEN_FUNCTION
        );

        if (!$fix) {
            return;
        }

        $lineBreaks = $next['line'] - $line;

        if ($next['code'] === T_CLOSE_CURLY_BRACKET) {
            $this->setNumberOfLineBreaks($phpcsFile->fixer, $stackPtr, $lineBreaks, 1);
        } else {
            $this->setNumberOfLineBreaks($phpcsFile->fixer, $stackPtr, $lineBreaks, 2);
        }
    }

    private function setNumberOfLineBreaks(Fixer $fixer, int $stackPtr, int $currentLines, int $expectedLines): void
    {
        $lineBreaksToDelete = $currentLines - $expectedLines;

        $fixer->beginChangeset();
        for ($i = 1; $i <= $lineBreaksToDelete; $i++) {
            $fixer->replaceToken($stackPtr + $i, '');
        }
        for ($i = 0; $i > $lineBreaksToDelete; $i--) {
            $fixer->addNewline($stackPtr);
        }
        $fixer->endChangeset();
    }
}

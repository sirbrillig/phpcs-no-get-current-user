<?php
declare(strict_types=1);

namespace NoGetCurrentUser\Sniffs\Disallowed;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;
use NoGetCurrentUser\Lib\Helpers;

class NoGetCurrentUserSniff implements Sniff {
	private $importedSymbolsByFile = [];

	public function register() {
		return [T_STRING, T_USE];
	}

	public function process(File $phpcsFile, $stackPtr) {
		$tokens = $phpcsFile->getTokens();
		$tokenType = $tokens[$stackPtr]['type'] ?? '';
		$importedSymbols = $this->importedSymbolsByFile[$phpcsFile->path] ?? [];
		if ($tokenType === 'T_USE') {
			$importedSymbols = array_merge($importedSymbols, Helpers::getImportedSymbols($phpcsFile, $stackPtr));
			$this->importedSymbolsByFile[$phpcsFile->path] = $importedSymbols;
			return;
		}
		$tokenContent = $tokens[$stackPtr]['content'] ?? '';
		if ($tokenContent !== 'get_current_user') {
			return;
		}
		if (! Helpers::isFunctionCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isMethodCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isStaticCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isNamespacedCall($phpcsFile, $stackPtr)) {
			return;
		}
		if (Helpers::isImportedCall($phpcsFile, $stackPtr, $importedSymbols)) {
			return;
		}
		if (Helpers::isFunctionDefined($phpcsFile, $stackPtr)) {
			return;
		}
		$phpcsFile->addError('get_current_user is a PHP function; did you mean to use it here?', $stackPtr, 'Found');
	}
}

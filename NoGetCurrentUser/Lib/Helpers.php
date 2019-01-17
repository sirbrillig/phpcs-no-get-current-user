<?php
declare(strict_types=1);

namespace NoGetCurrentUser\Lib;

class Helpers {
	public static function isFunctionCall($phpcsFile, $stackPtr): bool {
		$prevFunctionPtr = $phpcsFile->findPrevious([T_FUNCTION], $stackPtr - 1, $stackPtr - 3);
		if ($prevFunctionPtr) {
			return false;
		}
		$tokens = $phpcsFile->getTokens();
		$nextParenPtr = $phpcsFile->findNext([T_OPEN_PARENTHESIS], $stackPtr + 1, $stackPtr + 2);
		return ($nextParenPtr && isset($tokens[$nextParenPtr]));
	}

	public static function isMethodCall($phpcsFile, $stackPtr): bool {
		$prevSymbolPtr = $phpcsFile->findPrevious([T_OBJECT_OPERATOR], $stackPtr - 1, $stackPtr - 2);
		return (bool)$prevSymbolPtr;
	}

	public static function isFunctionAMethod($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$currentToken = $tokens[$stackPtr];
		return ! empty($currentToken['conditions']);
	}

	public static function isStaticCall($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_DOUBLE_COLON], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public static function isNamespacedCall($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$prevPtr = $phpcsFile->findPrevious([T_NS_SEPARATOR], $stackPtr - 1, $stackPtr - 2);
		return ($prevPtr && isset($tokens[$prevPtr]));
	}

	public static function isImportedCall($phpcsFile, $stackPtr, array $importedSymbols): bool {
		// Does the string in this token match an import (use) statement in the file?
		$tokens = $phpcsFile->getTokens();
		$symbolName = $tokens[$stackPtr]['content'] ?? '';
		return in_array($symbolName, $importedSymbols);
	}

	public static function getImportedSymbols($phpcsFile, $stackPtr): array {
		$tokens = $phpcsFile->getTokens();
		$endOfStatementPtr = $phpcsFile->findNext([T_SEMICOLON], $stackPtr + 1);
		if (! $endOfStatementPtr) {
			return [];
		}
		// Process grouped imports differently
		$nextBracketPtr = $phpcsFile->findNext([T_OPEN_USE_GROUP], $stackPtr + 1, $endOfStatementPtr);
		if ($nextBracketPtr) {
			return self::getImportedSymbolsFromGroupStatement($phpcsFile, $stackPtr);
		}
		// Get the last string before the last semicolon, comma, or closing curly bracket
		$endOfImportPtr = $phpcsFile->findPrevious(
			[T_COMMA, T_CLOSE_USE_GROUP],
			$stackPtr + 1,
			$endOfStatementPtr
		);
		if (! $endOfImportPtr) {
			$endOfImportPtr = $endOfStatementPtr;
		}
		$lastStringPtr = $phpcsFile->findPrevious([T_STRING], $endOfImportPtr - 1, $stackPtr);
		if (! $lastStringPtr || ! isset($tokens[$lastStringPtr])) {
			return [];
		}
		return [$tokens[$lastStringPtr]['content']];
	}

	public static function getImportedSymbolsFromGroupStatement($phpcsFile, $stackPtr): array {
		$tokens = $phpcsFile->getTokens();
		$endBracketPtr = $phpcsFile->findNext([T_CLOSE_USE_GROUP], $stackPtr + 1);
		$startBracketPtr = $phpcsFile->findNext([T_OPEN_USE_GROUP], $stackPtr + 1);
		if (! $endBracketPtr || ! $startBracketPtr) {
			throw new \Exception('Invalid group import statement starting at token ' . $stackPtr . ': ' . $tokens[$stackPtr]['content']);
		}
		$collectedSymbols = [];
		$lastStringPtr = $startBracketPtr;
		while ($lastStringPtr < $endBracketPtr) {
			$nextStringPtr = $phpcsFile->findNext([T_STRING], $lastStringPtr + 1, $endBracketPtr);
			if (! $nextStringPtr || ! isset($tokens[$nextStringPtr])) {
				break;
			}
			$nextCommaPtr = $phpcsFile->findNext([T_COMMA], $nextStringPtr + 1, $endBracketPtr) ?: $endBracketPtr;
			$nextAliasPtr = $phpcsFile->findNext([T_AS], $nextStringPtr + 1, $nextCommaPtr);
			if (! $nextAliasPtr) {
				$collectedSymbols[] = $tokens[$nextStringPtr]['content'];
			}
			$lastStringPtr = $nextStringPtr;
		}
		return $collectedSymbols;
	}

	public static function isFunctionDefined($phpcsFile, $stackPtr): bool {
		$tokens = $phpcsFile->getTokens();
		$functionName = $tokens[$stackPtr]['content'] ?? '';
		$functionPtr = $phpcsFile->findNext([T_FUNCTION], 0);
		while ($functionPtr) {
			$thisFunctionName = $phpcsFile->getDeclarationName($functionPtr);
			if ($functionName === $thisFunctionName && ! self::isFunctionAMethod($phpcsFile, $functionPtr)) {
				return true;
			}
			$functionPtr = $phpcsFile->findNext([T_FUNCTION], $functionPtr + 1);
		}
		return false;
	}
}

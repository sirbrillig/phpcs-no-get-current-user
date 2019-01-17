<?php
declare(strict_types=1);

namespace NoGetCurrentUserTest;

use PHPUnit\Framework\TestCase;
use NoGetCurrentUserTest\SniffTestHelper;

class NoGetCurrentUserSniffTest extends TestCase {
	public function testSniffRegistersErrorWhenUsingGetCurrentUser() {
		$fixtureFile = __DIR__ . '/fixtures/FileUsingGetCurrentUserFixture.php';
		$sniffFile = __DIR__ . '/../../../NoGetCurrentUser/Sniffs/Disallowed/NoGetCurrentUserSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [
			4,
		];
		$this->assertEquals($expectedLines, $lines);
	}

	public function testSniffRegistersNoErrorForImportedVersions() {
		$fixtureFile = __DIR__ . '/fixtures/FileUsingImportedVersionFixture.php';
		$sniffFile = __DIR__ . '/../../../NoGetCurrentUser/Sniffs/Disallowed/NoGetCurrentUserSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [];
		$this->assertEquals($expectedLines, $lines);
	}

	public function testSniffRegistersNoErrorForMultipleImports() {
		$fixtureFile = __DIR__ . '/fixtures/FileUsingMultipleImportsFixture.php';
		$sniffFile = __DIR__ . '/../../../NoGetCurrentUser/Sniffs/Disallowed/NoGetCurrentUserSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [];
		$this->assertEquals($expectedLines, $lines);
	}

	public function testSniffRegistersNoErrorForDeclaredVersion() {
		$fixtureFile = __DIR__ . '/fixtures/FileUsingDeclaredVersionFixture.php';
		$sniffFile = __DIR__ . '/../../../NoGetCurrentUser/Sniffs/Disallowed/NoGetCurrentUserSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [];
		$this->assertEquals($expectedLines, $lines);
	}

	public function testSniffRegistersNoErrorMethodNames() {
		$fixtureFile = __DIR__ . '/fixtures/FileWithClassMethodFixture.php';
		$sniffFile = __DIR__ . '/../../../NoGetCurrentUser/Sniffs/Disallowed/NoGetCurrentUserSniff.php';
		$helper = new SniffTestHelper();
		$phpcsFile = $helper->prepareLocalFileForSniffs($sniffFile, $fixtureFile);
		$phpcsFile->process();
		$messages = $helper->getMessageRecords($phpcsFile->getErrors());
		$messages = array_values(array_filter($messages, function ($message) {
			return $message->source === 'NoGetCurrentUser.Disallowed.NoGetCurrentUser.Found';
		}));
		$lines = array_map(function ($message) {
			return $message->rowNumber;
		}, $messages);
		$expectedLines = [];
		$this->assertEquals($expectedLines, $lines);
	}
}

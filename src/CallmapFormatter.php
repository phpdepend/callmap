<?php declare(strict_types = 1);

namespace StellaMaris\Callmap;

use PHPStan\Command\AnalysisResult;
use PHPStan\Command\ErrorFormatter\ErrorFormatter;
use PHPStan\Command\ErrorFormatter\TableErrorFormatter;
use PHPStan\Command\Output;
use PHPStan\File\RelativePathHelper;

class CallmapFormatter implements ErrorFormatter
{

	public function __construct(
		private TableErrorFormatter $tableErrorFormatter
	){}

	public function formatErrors(AnalysisResult $analysisResult, Output $output): int
	{
		if ($analysisResult->hasInternalErrors()) {
			return $this->tableErrorFormatter->formatErrors($analysisResult, $output);
		}

		$json = [];
		foreach ($analysisResult->getFileSpecificErrors() as $error) {
			if ($error->getIdentifier() === 'ignore.unmatchedLine' || $error->getIdentifier() === 'ignore.unmatchedIdentifier') {
				continue;
			}
			if ($error->getIdentifier() !== 'stellamarisCallmapFormatter.data') {
				return $this->tableErrorFormatter->formatErrors($analysisResult, $output);
			}

			$metadata = $error->getMetadata();
			$json[] = [
				'callingClass' => $metadata['callingClass'],
				'callingMethod' => $metadata['callingMethod'],
				'calledClass' => $metadata['calledClass'],
				'calledMethod' => $metadata['calledMethod'],
			];
		}

		$output->writeRaw((string) json_encode([
			'data' => $json,
		]));

		return 0;
	}

}
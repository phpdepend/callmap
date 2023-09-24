<?php declare(strict_types = 1);

namespace StellaMaris\Callmap;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\RuleErrorBuilder;
use function array_pop;
use function array_unique;
use function array_values;
use function in_array;
use function json_encode;
use function var_dump;

/**
 * @implements \PHPStan\Rules\Rule<CollectedDataNode>
 */
class Rule implements \PHPStan\Rules\Rule
{

	public function __construct()
	{
	}

	public function getNodeType(): string
	{
		return CollectedDataNode::class;
	}

	public function processNode(Node $node, Scope $scope): array
	{
		$errors = [];

		foreach ($node->get(MethodCallCollector::class) as $rows) {
			foreach ($rows as $row) {
				$errors[] = RuleErrorBuilder::message('Metadata')
					->identifier('stellamarisCallmapFormatter.data')
					->metadata($row)->build();
			}
		}

		return $errors;
	}
}

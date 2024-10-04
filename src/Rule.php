<?php declare(strict_types = 1);

namespace PHPDepend\Callmap;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\RuleErrorBuilder;

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
					->identifier('phpdependCallmapFormatter.data')
					->metadata($row)->build();
			}
		}

		return $errors;
	}
}

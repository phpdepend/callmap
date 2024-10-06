<?php declare(strict_types=1);

/**
 * Copyright (C) 2023 Andreas Heigl <andreas@stella-maris.solutions>
 *
 * Licensed under the GPLv3. For the full License Notice see the LICENSE.md file in the root directory
 */

namespace PHPDepend\Callmap;

use LogicException;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Reflection\ExtendedMethodReflection;

/**
 * @implements Collector<MethodCall, array{callingClass?: string, callingMethod: string, calledClass: string, calledMethod: string}>
 */
class MethodCallCollector implements Collector
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope)
    {
	    $methodName = $node->name;
	    if (! $methodName instanceof Node\Identifier) {
		    return [];
	    }
	    $type = $scope->getType($node->var);
		$type = (string) $scope->getMethodReflection($type, $methodName->name)?->getDeclaringClass()->getName();

	    $function = $scope->getFunction();
		if ($function === null) {
			throw new LogicException('Nope');
		}
		$functionName = $function->getName();
		$class = '';
		if ($function instanceof ExtendedMethodReflection) {
			$class = $function->getDeclaringClass()->getName();
		}
		return [
			'callingClass' => $class,
			'callingMethod' => $functionName,
			'calledClass' => $type,
			'calledMethod' => $methodName->name,
		];
    }

}

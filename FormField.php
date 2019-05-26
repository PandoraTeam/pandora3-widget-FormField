<?php
namespace Pandora3\Widgets\FormField;

use Pandora3\Core\Container\Container;
use Pandora3\Core\Interfaces\RendererInterface;
use Pandora3\Libs\Renderer\PhpRenderer;
use Pandora3\Libs\Widget\Widget;
use Pandora3\Widgets\Form\Form;

/**
 * Class FormField
 * @package Pandora3\Widgets\FormField
 *
 * @property-read mixed $value
 * @property-read string $label
 */
abstract class FormField extends Widget {

	/** @var string $name */
	protected $name;

	/** @var mixed $value */
	protected $value;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 */
	public function __construct(string $name, $value, array $context = []) {
		parent::__construct($context);
		$this->name = $name;
		$this->setValue($value);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function dependencies(Container $container): void {
		$container->setShared(RendererInterface::class, PhpRenderer::class);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $context
	 * @return static
	 */
	public static function create(string $name, $value, array $context = []) {
		return new static($name, $value, $context);
	}

	/**
 	 * @ignore
	 * @param string $property
	 * @return mixed
	 */
	public function __get(string $property) {
		$methods = [
			'value' => 'getValue',
			'label' => 'getLabel',
		];
		$methodName = $methods[$property] ?? '';
		if ($methodName && method_exists($this, $methodName)) {
			return $this->{$methodName}();
		}
		return parent::__get($property);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getContext(): array {
		return array_replace( $this->context, [
			'name' => $this->name,
			'value' => $this->getValue(),
		]);
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value ?? $this->context['default'] ?? null;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void {
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->context['label'] ?? '';
	}

}
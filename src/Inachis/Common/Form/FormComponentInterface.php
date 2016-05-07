<?php

namespace Inachis\Component\Common\Form;

interface FormComponentInterface
{
	public static function getType();

	public static function setId($value);
	public static function getId();

	public static function setName($value);
	public static function getName();

	public static function setLabel($value);
	public static function getLabel();

	public static function setValue($value);
	public static function getValue();

	public static function setReadOnly($value);
	public static function getReadOnly();

	public static function setRequired($value);
	public static function getRequired();

	public static function build();
}
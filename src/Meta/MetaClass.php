<?php

/**
 * @file MetaClass.php
 * @brief This file contains the MetaClass abstract class.
 * @details
 * @author Filippo F. Fadda
 */


//! Meta classes
namespace Meta;


use Meta\Extension\TProperty;

use ToolBag\Helper\ArrayHelper;
use ToolBag\Exception\JSONErrorException;

use InvalidArgumentException;


/**
 * @brief This abstract class provides a set of methods to store and access metadata.
 * @details Metadata are accessible through properties defined by the subclass that extends the `MetaClass` itself.
 * In fact, the class is using the `TProperty` extension.
 * @nosubgrouping
 */
abstract class MetaClass {
  use TProperty;

  protected $meta = []; //!< Metadata array.


  /**
   * @brief Resets the metadata.
   */
  public function resetMetadata() {
    unset($this->meta);
    $this->meta = [];
  }


  /**
   * @brief Returns the metadata.
   * @return mixed
   */
  public function getMetadata($name) {
    return @$this->meta[$name];
  }


  /**
   * @brief Checks the document for the given attribute.
   * @param string $name The attribute name.
   * @return bool
   */
  public function isMetadataPresent($name) {
    return (array_key_exists($name, $this->meta)) ? TRUE : FALSE;
  }


  /**
   * @brief Sets the metadata to the provided value.
   * @param string $name The metadata name.
   * @param mixed $value The metadata value.
   * @param bool $override When `true` overrides the metadata value.
   * @param bool $allowNull When `true` allows a `null` value.
   */
  public function setMetadata($name, $value, $override = TRUE, $allowNull = TRUE) {
    if (is_null($value) && !$allowNull)
      return;

    if ($this->isMetadataPresent($name) && !$override)
      return;

    $this->meta[$name] = $value;
  }


  /**
   * @brief Removes an metadata previously set.
   * @param string $name The metadata name.
   */
  public function unsetMetadata($name) {
    if (array_key_exists($name, $this->meta))
      unset($this->meta[$name]);
  }


  /**
   * @brief Given a JSON object, this function assigns every single object's property to the `$meta` array, the array
   * that stores the document's metadata.
   * @param string $json A JSON object.
   */
  public function assignJson($json) {
    $this->meta = array_merge($this->meta, ArrayHelper::fromJson($json, TRUE));
  }


  /**
   * @brief Assigns the given associative array to the `$meta` array, the array that stores the document's metadata.
   * @param array $array An associative array.
   * @throws InvalidArgumentException
   */
  public function assignArray(array $array) {
    if (ArrayHelper::isAssociative($array)) {
      $this->meta = array_merge($this->meta, $array);
    }
    else
      throw new InvalidArgumentException("\$array must be an associative array.");
  }


  /**
   * @brief Given an instance of a standard class, this function assigns every single object's property to the `$meta`
   * array, the array that stores the document's metadata.
   */
  public function assignObject(\stdClass $object) {
    $this->meta = array_merge($this->meta, get_object_vars($object));
  }


  /**
   * @brief Returns the document representation as a JSON object.
   * @return string A JSON object.
   * @throws JSONErrorException
   */
  public function asJson() {
    $json = json_encode($this->meta,
      JSON_UNESCAPED_UNICODE |
      JSON_PARTIAL_OUTPUT_ON_ERROR |
      JSON_PRESERVE_ZERO_FRACTION
    );

    if ($json === FALSE)
      throw new JSONErrorException(json_last_error_msg());

    return $json;
  }


  /**
   * @brief Returns the document representation as an associative array.
   * @return array An associative array.
   */
  public function asArray() {
    return $this->meta;
  }

}
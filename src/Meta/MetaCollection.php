<?php

/**
 * @file MetaCollection.php
 * @brief This file contains the MetaCollection class.
 * @details
 * @author Filippo F. Fadda
 */


namespace Meta;


use Meta\Extension\TProperty;

use ArrayIterator;


/**
 * @brief This class is used to represent a generic collection.
 * @details This class implements `IteratorAggregate`, `Countable`, and `ArrayAccess`.
 * @nosubgrouping
 */
abstract class MetaCollection implements \IteratorAggregate, \Countable, \ArrayAccess {
  use TProperty;

  /**
   * @var array $meta
   */
  protected $meta;


  /**
   * @var string $name
   */
  protected $name;


  /**
   * @brief Creates a new collection of items.
   * @param string $name Collection's name.
   * @param array &$meta Array of metadata.
   */
  public function __construct($name, array &$meta) {
    $this->name = $name;
    $this->meta = &$meta;
    $this->meta[$name] = [];
  }


  /**
   * @brief Removes all items from the collection.
   */
  public function reset() {
    unset($this->meta[$this->name]);
    $this->meta[$this->name] = [];
  }


  /**
   * @brief Returns the collection as a real array.
   * @return array An associative array using as keys the e-mail addresses, and as values if the address are verified or
   * not.
   */
  public function asArray() {
    return $this->meta[$this->name];
  }


  /**
   * @brief Returns an external iterator.
   * @return ArrayIterator
   */
  public function getIterator() {
    return new ArrayIterator($this->meta[$this->name]);
  }


  /**
   * @brief Returns the number of documents found.
   * @return integer Number of documents.
   */
  public function count() {
    return count($this->meta[$this->name]);
  }


  /**
   * @brief Returns `true` in case there aren't items inside the collection, `false` otherwise.
   * @details Since the PHP core developers are noobs, `empty()` cannot be used on any class that implements ArrayAccess.
   * @attention This method must be used in place of `empty()`.
   * @return bool
   */
  public function isEmpty() {
    return empty($this->meta[$this->name]) ? TRUE : FALSE;
  }


  /**
   * @brief Whether or not an offset exists.
   * @details This method is executed when using `isset()` or `empty()` on objects implementing ArrayAccess.
   * @param integer $offset An offset to check for.
   * @return bool Returns `true` on success or `false` on failure.
   */
  public function offsetExists($offset) {
    return isset($this->meta[$this->name][$offset]);
  }


  /**
   * @brief Returns the value at specified offset.
   * @details This method is executed when checking if offset is `empty()`.
   * @param integer $offset The offset to retrieve.
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)  {
    return $this->meta[$this->name][$offset];
  }


  //! @cond HIDDEN_SYMBOLS

  public function offsetSet($offset, $value) {
    throw new \BadMethodCallException("Collection is immutable and cannot be changed.");
  }


  public function offsetUnset($offset) {
    throw new \BadMethodCallException("Collection is immutable and cannot be changed.");
  }

  //! @endcond

}
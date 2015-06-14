<?php

interface ICache {

  function initialize();
  function add($data, $key = null, $ttl = null);
  function get($key);

}
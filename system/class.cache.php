<?php
final class Cache {
	private $expire;
	private $memcache;
	private $ismemcache = false;
	private $cachedriver;

  	public function __construct($exp = 3600) {
  		$this->expire = $exp;
  		
  		if( defined('CACHE_DRIVER') ) $this->cachedriver = CACHE_DRIVER;

  		if ( $this->cachedriver == 'memcached')
  		{
		    $mc = new Memcache;
		    if ($mc->pconnect(MEMCACHE_HOSTNAME, MEMCACHE_PORT))
		    {
			$this->memcache = $mc;
			$this->ismemcache = true;
		    };
		};

		if (!$this->ismemcache)
		{
		    $files = glob(DIR_CACHE . 'cache.*');

		    if ($files) {
			    foreach ($files as $file) {
				    $time = substr(strrchr($file, '.'), 1);

			    if ($time < time()) {
					    if (file_exists($file)) {
						    @touch($file);
						    @unlink($file);
					    }
				}
			    }
		    }
		}
  	}

	public function get($key) {
	    if (($this->cachedriver == 'memcached') && $this->ismemcache)
	    {
		return($this->memcache->get(MEMCACHE_NAMESPACE . $key, 0));
	    }
	    else
	    {
		$files = glob(DIR_CACHE . 'cache.' . $key . '.*');

		if ($files) {
		    $cache = file_get_contents($files[0]);
		    return unserialize($cache);
		}
	    }
  	}

  	public function set($key, $value) {
	    if (($this->cachedriver == 'memcached') && $this->ismemcache)
	    {
		$this->memcache->set(MEMCACHE_NAMESPACE . $key, $value, 0, $this->expire);
	    }
	    else
	    {

    		    $this->delete($key);

		    $file = DIR_CACHE . 'cache.' . $key . '.' . (time() + $this->expire);

		    $handle = fopen($file, 'w');

    		    fwrite($handle, serialize($value));

    		    fclose($handle);
    	    };
  	}

  	public function delete($key) {
	    if (($this->cachedriver == 'memcached') && $this->ismemcache)
	    {
		$this->memcache->delete(MEMCACHE_NAMESPACE . $key);
	    }
	    else
	    {
		$files = glob(DIR_CACHE . 'cache.' . $key . '.*');

		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					@unlink($file);
					clearstatcache();
				}
    		}
		}
	    }
  	}
}
?>
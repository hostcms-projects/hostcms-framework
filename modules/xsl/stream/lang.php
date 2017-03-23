<?php

class Xsl_Stream_Lang
{
	protected $_position = 0;
	protected $_xslName = NULL;

	protected $_oXsl = NULL;

	protected static $_aDTD = array();

	public function stream_open($path, $mode, $options, &$opened_path)
	{
		$this->_xslName = substr($path, 7);

		if (!is_numeric($this->_xslName))
		{
			// Search XSL by name
			$oXsl = Core_Entity::factory('Xsl')->getByName($this->_xslName);

			if (!is_null($oXsl))
			{
				$this->_oXsl = $oXsl;
			}
			else
			{
				throw new Core_Exception("Xsl_Stream_Lang: Undefined XSL '%name'", array('%name' => $this->_xslName));
			}
		}
		else
		{
			$this->_oXsl = Core_Entity::factory('Xsl', intval($this->_xslName));
		}

		if (!isset(self::$_aDTD[$this->_xslName][SITE_LNG]))
		{
			$filePath = $this->_oXsl->getLngDtdPath(SITE_LNG);

			self::$_aDTD[$this->_xslName][SITE_LNG] = '<?xml version="1.0" encoding="UTF-8"?>'
				. (is_file($filePath)
					 ? Core_File::read($filePath)
					 : '');
		}

		return TRUE;
	}

	public function stream_read($count)
	{
		$ret = substr(self::$_aDTD[$this->_xslName][SITE_LNG], $this->_position, $count);
		$this->_position += strlen($ret);

		return $ret;
	}

	public function stream_write($data)
	{
	   return FALSE;
	}

	public function stream_tell()
	{
		return $this->_position;
	}

	public function stream_eof()
	{
		return $this->_position >= strlen(self::$_aDTD[$this->_xslName][SITE_LNG]);
	}

	public function stream_seek($offset, $whence)
	{
		 switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen(self::$_aDTD[$this->_xslName][SITE_LNG]) && $offset >= 0) {
                     $this->position = $offset;
                     return TRUE;
                } else {
                     return FALSE;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                     $this->position += $offset;
                     return TRUE;
                } else {
                     return FALSE;
                }
                break;

            case SEEK_END:
                if (strlen(self::$_aDTD[$this->_xslName][SITE_LNG]) + $offset >= 0) {
                     $this->position = strlen(self::$_aDTD[$this->_xslName][SITE_LNG]) + $offset;
                     return TRUE;
                } else {
                     return FALSE;
                }
                break;

            default:
                return FALSE;
        }
	}

	public function url_stat($path, $flags)
	{
		return array();
	}
}
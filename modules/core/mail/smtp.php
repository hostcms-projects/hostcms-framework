<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * SMTP driver
 *
 * @package HostCMS 6\Core\Mail
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2013 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Core_Mail_Smtp extends Core_Mail
{
	/**
	 * Send mail
	 * @param string $to recipient
	 * @param string $subject subject
	 * @param string $message message
	 * @param string $additional_headers additional_headers
	 * @return self
	 */
	protected function _send($to, $subject, $message, $additional_headers)
	{
		$sSingleSeparator = $this->_separator;

		$header = "Date: " . date("D, d M Y H:i:s") . " UT{$sSingleSeparator}";
		$header .= "Subject: {$subject}{$sSingleSeparator}";
		$header .= "To: <{$to}>{$sSingleSeparator}";
		$header .= $additional_headers . $sSingleSeparator . $sSingleSeparator;

		$header .=  $message . $sSingleSeparator;

		$timeout = 30;
		if ($fp = fsockopen($this->_config['host'], $this->_config['port'], $errno, $errstr, $timeout))
		{
			if (!$this->_serverParse($fp, "220"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, "HELO {$this->_config['host']}\r\n");

			if (!$this->_serverParse($fp, "250"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, "AUTH LOGIN\r\n");
			if (!$this->_serverParse($fp, "334"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, base64_encode($this->_config['username']) . "\r\n");
			if (!$this->_serverParse($fp, "334"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, base64_encode($this->_config['password']) . "\r\n");
			if (!$this->_serverParse($fp, "235"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, "MAIL FROM: <{$this->_config['username']}>\r\n");
			if (!$this->_serverParse($fp, "250")) {
				fclose($fp);
				return FALSE;
			}
			
			$aRecipients = explode(',', $to);
			foreach ($aRecipients as $sTo)
			{
				fputs($fp, "RCPT TO: <{$sTo}>\r\n");
				if (!$this->_serverParse($fp, "250"))
				{
					fclose($fp);
					return FALSE;
				}
			}

			fputs($fp, "DATA\r\n");
			if (!$this->_serverParse($fp, "354"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, $header."\r\n.\r\n");
			if (!$this->_serverParse($fp, "250"))
			{
				fclose($fp);
				return FALSE;
			}

			fputs($fp, "QUIT\r\n");
			fclose($fp);

			$this->_status = TRUE;
		}
		else
		{
			$this->_status = FALSE;
		}

		return $this;
	}

	/**
	 * Parse server answer
	 * @param pointer $socket socket pointer
	 * @param string $response response
	 * @return string
	 */
	protected function _serverParse($socket, $response)
	{
		$server_response = fgets($socket, 256);
		$result = substr($server_response, 0, 3) == $response;

		if (!$result)
		{
			//throw new Core_Exception('SMTP error: "%error"', array('%error' => $server_response));
			Core_Log::instance()->clear()
				->notify(FALSE) // avoid recursion
				->status(Core_Log::$ERROR)
				->write(sprintf('SMTP error: "%s"', $server_response));
		}

		return $result;
	}
}
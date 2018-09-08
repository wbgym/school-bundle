<?php

/**
 * WBGym
 *
 * Copyright (C) 2018 Webteam Weinberg-Gymnasium Kleinmachnow
 *
 * @package     WGBym
 * @author      Markus Mielimonka <markus.mielimonka@t-online.de>
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

/*
 Namespace
 */
namespace WBGym;

/**
 * WBGymEmail
 */
class WBGymEmail extends \Email
{
    /**
     * whether to send Mails or not.
     *
     * @var bool
     */
    protected static $blnSendMails = false;
    /**
     * whether to log a eMail has been send notification.
     * @var bool
     */
    protected static $blnLog = true;

    public function __construct(string $content, string $strSubject, array $arrConf=[])
    {
        parent::__construct();
        $this->html = $content;

        $this->from = $arrConf['from'] ?? 'webteam@wbgym.de';
        $this->fromName = $arrConf['fromName'] ?? 'Webteam Weinberg-Gymnasium';
        $this->subject = $strSubject;

        if (static::$blnLog) $this->strLogFile = 'TL_GENERAL';
    }

     /**
     * sends a single Mail to a user if @var blnSendMails is true
     * @param string $strTemplate 	Email Template to user
     * @param string $strSubject 		Email Subject
     * @param string $strMail			Email to send the mail to
     * @param array $arrSearch		Insert Tags to replace
     * @param array $arrReplace		Values to replace
     *
     * @return bool if true, mail delivery was successfull
     *
     * @author Johannes Cram <craj.me@gmail.com>
     * @deprecated backward compatibility
     */
    public static function sendMail(string $strTemplate, string $strSubject, string $strAddress, array $arrSearch, array $arrReplace) {
        if (static::$blnSendMails) {
            $objMail = new \Email();
            $objMail->from = 'webteam@wbgym.de';
            $objMail->fromName = 'Webteam Weinberg-Gymnasium';
            $objMail->subject = $strSubject;
            if (static::$blnLog) $objMail->strLogFile = 'TL_GENERAL';

            $html = file_get_contents($strTemplate);
            /*
            Replace own insert tags
            */
            $objMail->html = str_replace($arrSearch, $arrReplace, $html);

            return $objMail->sendTo($strAddress);
        }
        return true;
    }
    /**
     * creates a new mail object from a template at @param $strTemplate
     *
     * @param  string $strTemplate the path to the template
     * @param  array  $arrConfig   an array of configurations
     * @return self                constructor
     */
    public static function fromTemplate(string $strTemplate, string $subject, array $arrConfig=[])
    {
        return new static(file_get_contents($strTemplate), $subject, $arrConf);
    }
    /**
     * chainable constructor
     *
     * @param  string $strContent the E-Mail content
     * @param  array  $arrConf    an array of configurations
     * @return self               constructor
     */
    public static function create(string $strContent, string $strSubject, array $arrConf=[])
    {
        return new static($strContent, $strSubject, $arrConf);
    }
    /**
     * replaces the keys of @param $arrSearchAndReplace with the values of it.
     * @param  array  $arrSearchAndReplace Array of the elements to replace (old => new)
     * @return void
     */
    public function contentReplace(array $arrSearchAndReplace):void
    {
        $this->html = strtr($arrSearchAndReplace, $this->html);
    }
    /**
     * replace the named-tags of @param $arrSearchAndReplace with their associative values.
     * @param  array  $arrSearchAndReplace array of tag => value
     * @return void
     */
    public function contentReplaceTags(array $arrSearchAndReplace)
    {
        foreach ($arrSearchAndReplace as $key => $value) {
            $arrSearchAndReplace['{{'.$key.'}}'] = $value;
            unset($arrSearchAndReplace[$key]);
        }
        $this->contentReplace($arrSearchAndReplace);
    }
    public function sendTo()
    {
        if (static::$blnSendMails) return parent::sendTo(func_get_args());
        return false;
    }
}

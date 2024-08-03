<?php

namespace PlatBox\API\Service\Extractor;

use PhpImap\Exceptions\InvalidParameterException;
use ProxyBundle\Exceptions\EmailNotFoundException;
use ProxyBundle\Services\Provider\TopolCommandMode;

/**
 * Class EmailExtractor
 * @package PlatBox\API\Service\Extractor
 */
final class EmailExtractor implements ExtractorInterface
{
    /**
     * @param string $login
     * @param string $password
     * @throws InvalidParameterException
     */
    public function extract(string $login, string $password): void
    {
        //smtp://outgoing@3ds.ru:tunod6s4BmFKRQvb@smtp.yandex.ru:465
        $imap_path = "{smtp.yandex.ru:465}INBOX";
        $email_address = $login;
        $email_password = $password;

        $mbox = imap_open ($imap_path, $email_address, $email_password);

        if ($mbox === false) {
            echo "Ошибка подключения";
        } else {
            echo "Подключено";
            imap_close ($mbox);
        }
    }
}

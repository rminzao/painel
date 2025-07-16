<?php

namespace Core\Utils;

use TelegramBot\Api\BotApi;

class Telegram
{
    /** @var BotApi */
    protected BotApi $botApi;

    /** @var int */
    protected int $chatMessageId;

    /** @var int */
    protected int $chatLogId;

    /** @var int */
    protected int $chatBackupId;

    /**
     * Telegram contructor
     */
    public function __construct()
    {
        $this->botApi = new BotApi($_ENV['TELEGRAM_API_KEY']);
        $this->chatMessageId = $_ENV['TELEGRAM_CHAT_ID'];
        $this->chatLogId = $_ENV['TELEGRAM_CHAT_ID'];
    }

    /**
     * @param string $message
     * @return void
     */
    public function sendMessage(string $message)
    {
        //send 'typing' to chat
        $this->botApi->sendChatAction($this->chatMessageId, 'typing');

        //send message
        $this->botApi->sendMessage(
            chatId: $this->chatMessageId,
            text: $message,
            parseMode: 'html'
        );
    }

    /**
     * @param string $message
     * @return void
     */
    public function sendLog(string $message)
    {
        //send 'typing' to chat
        $this->botApi->sendChatAction($this->chatLogId, 'typing');

        //send message
        $this->botApi->sendMessage(
            chatId: $this->chatLogId,
            text: $message,
            parseMode: 'html'
        );
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MessagesGenerateCommand extends Command
{

    const MESSAGES_PATH = "";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates messages.json file from Language files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $messages = $this->getMessages();
        $this->sortMessages($messages);

        File::ensureDirectoryExists(File::dirname($this->getMessagesPath()));

        if (!File::put($this->getMessagesPath(), json_encode($messages))) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function getLangPath()
    {
        return base_path("lang");
    }

    protected function getMessagesPath()
    {
        return resource_path("js" . DIRECTORY_SEPARATOR . "messages.json");
    }

    /**
     * Recursively sorts all messages by key.
     *
     * @param array $messages The messages to sort by key.
     */
    protected function sortMessages(&$messages)
    {
        if (is_array($messages)) {
            ksort($messages);

            foreach ($messages as $key => &$value) {
                $this->sortMessages($value);
            }
        }
    }


    /**
     * Return all language messages.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getMessages()
    {
        $messages = [];

        if (File::files($this->getLangPath())) {
            throw new \Exception($this->getLangPath() . " doesn't exists!");
        }

        $files = File::allFiles($this->getLangPath());
        foreach ($files as $file) {
            $pathName  = $file->getRelativePathName();
            $extension = File::extension($pathName);

            if ($extension != 'php' && $extension != 'json') {
                continue;
            }

            $keyPath = substr(
                $file->getRelativePathname(),
                0,
                strrpos($file->getRelativePathname(), ".")
            );

            $keys = explode(DIRECTORY_SEPARATOR, $keyPath);
            switch ($extension) {
                case 'php':
                    $value    = include $file->getRealPath();
                    $messages = $this->addToMessages($messages, $keys, $value);
                    break;
                case 'json':
                    $value    = json_decode($file->getContents(), true);
                    $messages = $this->addToMessages($messages, $keys, $value);
                    break;
            }
        }
        return $messages;
    }

    protected function addToMessages(array $messages, array $keys, $value)
    {
        switch (sizeof($keys)) {
            case 0:
                return $value;
            case 1:
                $key = array_shift($keys);
                if (!isset($messages[$key])) {
                    $messages[$key] = [];
                }
                $messages[$key] = $value;
                return $messages;
            default:
                $key = array_shift($keys);
                if (!isset($messages[$key])) {
                    $messages[$key] = [];
                }
                $messages[$key] = $this->addToMessages($messages[$key], $keys, $value);
                return $messages;
        }
    }

}

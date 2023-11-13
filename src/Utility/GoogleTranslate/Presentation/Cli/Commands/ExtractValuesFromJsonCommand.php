<?php

namespace Untek\Utility\GoogleTranslate\Presentation\Cli\Commands;

use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class ExtractValuesFromJsonCommand extends Command
{

    public function __construct(
        string $name = null,
        private CommandBusInterface $bus,
        private array $interacts
    )
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->addOption(
            'sourceFile',
            null,
            InputOption::VALUE_OPTIONAL,
            'File with source data'
        );
        $this->addOption(
            'targetFile',
            null,
            InputOption::VALUE_OPTIONAL,
            'File with target data'
        );
        $this->addOption(
            'sourceLang',
            null,
            InputOption::VALUE_OPTIONAL,
            'Source language'
        );
        $this->addOption(
            'targetLang',
            null,
            InputOption::VALUE_OPTIONAL,
            'Target language'
        );
    }

    private static $id = 0;
    private static $lines = [];

    public static function htmlDecode($data, &$text = [])
    {
        $d = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {

                $value = self::$lines[self::$id];

                $d[$key] = $value;
                $text[] = $value;

                self::$id++;
            } elseif (is_array($value)) {
                $d[$key] = static::htmlDecode($value, $text);
            } else {
                $d[$key] = $value;
            }
        }

//        file_put_contents('/home/server/www/forecast-kz/map-backend/config/ru.min.txt', implode(PHP_EOL, $text));

        return $d;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sourceFile = $input->getOption('sourceFile');
        $targetFile = $input->getOption('targetFile');
        $sourceLang = $input->getOption('sourceLang');
        $targetLang = $input->getOption('targetLang');

        /*dd(
            $sourceFile,
            $targetFile,
            $sourceLang,
            $targetLang
        );*/

        /*$tr = new GoogleTranslate();
        $tr->setSource($sourceLang);
        $tr->setTarget($targetLang);

        $text = file_get_contents('/home/server/www/forecast-kz/map-backend/config/ru.min.txt');
//        dd($text);
        dd( $tr->translate($text));*/

//        $kzText = file_get_contents('/home/server/www/forecast-kz/map-backend/config/kz.min.txt');
//        self::$lines = explode(PHP_EOL, $kzText);

        $json = file_get_contents('/home/server/www/forecast-kz/map-backend/config/ru.min.json');
        $data = json_decode($json, true);

        $decodedData = self::htmlDecode($data);

//        file_put_contents('/home/server/www/forecast-kz/map-backend/config/kz.min.json', json_encode($decodedData, JSON_UNESCAPED_UNICODE));


        return Command::SUCCESS;
    }
}

<?php

namespace Untek\Utility\GoogleTranslate\Presentation\Cli\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\Cloner\Data;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\GoogleTranslate\Application\DataToFlat;
use Untek\Utility\GoogleTranslate\Application\ProxyTranslate;

class TranslateJsonCommand extends Command
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

    protected $placeholders = [];
    protected $placeholderNumber = 1;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $sourceFile = $input->getOption('sourceFile');
        $targetFile = $input->getOption('targetFile');
        $sourceLang = $input->getOption('sourceLang');
        $targetLang = $input->getOption('targetLang');

        $sourceJson = file_get_contents($sourceFile);
        $sourceData = json_decode($sourceJson, true);

        $dataToFlat = new DataToFlat();
        $sourceLinesText = $dataToFlat->run($sourceData);

        file_put_contents($sourceFile . '.txt', $sourceLinesText);

//        dd($sourceLinesText);

        /*dd(
            $sourceFile,
            $targetFile,
            $sourceLang,
            $targetLang
        );*/

        $tr = new ProxyTranslate();
        $tr->setSource($sourceLang);
        $tr->setTarget($targetLang);

//        $sourceLinesText = file_get_contents('/home/server/www/forecast-kz/map-backend/config/ru.min.txt');

        $sourceLinesText = preg_replace_callback('/\{(.+)\}/i', function ($matches) {
            $hashInt = $this->placeholderNumber;
            $placeholder = '{' . $hashInt . '}';
            $this->placeholders[$hashInt] = $matches;
            $this->placeholderNumber++;
            return $placeholder;
        }, $sourceLinesText);

        $lines = explode(PHP_EOL, $sourceLinesText);
        $chunks = array_chunk($lines, 10);

        $io->progressStart(count($chunks));

        $targetLines = [];

//        $targetText = '';
        foreach ($chunks as $chunkIndex => $chunk) {
            $tt = implode(PHP_EOL, $chunk);
            $translated = $tr->translate($tt);
            $translated = preg_replace_callback('/\{(.+)\}/i', function ($matches) {
                $num = intval($matches[1]);
                $matchesssss = $this->placeholders[$num];
                return $matchesssss[0];
            }, $translated);
//            $targetText .= PHP_EOL . $translated;
            $targetLines[] = $translated;
            $io->progressAdvance(1);
        }

        $io->progressFinish();

        $targetText = implode(PHP_EOL, $targetLines);



        file_put_contents($targetFile . '.txt', $targetText);
//        dd($targetText);
//        dd($tr->translate($text));

        self::$lines = explode(PHP_EOL, $targetText);

        $json = file_get_contents($sourceFile);
        $data = json_decode($json, true);

        $decodedData = self::htmlDecode($data);

        file_put_contents($targetFile, json_encode($decodedData, JSON_UNESCAPED_UNICODE));


        return Command::SUCCESS;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

class DumperService extends Facade
{
    private array $data = [];

    public function __construct()
    {

    }

    protected static function getFacadeAccessor()
    {
        return 'dumper'; // This should match the binding in the service provider
    }


    public function add($string, $level=0)
    {
        $this->data[] = [
            'string' => $string,
            'level' => $level
        ];
    }

    public function dump()
    {
        foreach ($this->construct()as $d) {
            dump($d);
        }
    }

    public function dd()
    {
        $data = $this->construct();
        foreach ($data as $i => $d) {
            $i == count($data)-1 ? dd($d) : dump($d);
        }
    }

    public function log()
    {
        $toLog = "DumperLog:\n" . implode("\n>|\n", $this->construct());

        dlog($toLog);
    }

    private function construct()
    {
        $dumps = [];
        $prevLevel = null;

        foreach ($this->data as $d) {
            $level = $d['level'];

            if ($level) {
                $row = str_repeat('|   ', $level-1) . '|-- ';
            } else {
                $row = '';
            }


            $row .= $d['string'];

            if (!$dumps || ($level === 0 && $prevLevel > 0)) {
                $dumps[] = $row;
            } else {
                $dumps[count($dumps)-1] .= "\n$row";
            }

            $prevLevel = $level;
        }

        return $dumps;
    }
}

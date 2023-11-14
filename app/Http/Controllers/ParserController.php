<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Pop;
use Auth;
use DB;

class ParserController extends Controller
{
    private $parser_data_table = 'parser_data';
    private $parser_final_table = 'parser_final';
    private $parser_combinations_table = 'parser_combinations';

    function dump($var, $exit = false, $label = false, $echo = true)
    {

        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        $label = $label ? $label . ' ' : '';

        // Location and line-number
        $line = '';
        $separator = "<strong style='color:blue'>" . str_repeat("-", 100) . "</strong>" . PHP_EOL;
        $caller = debug_backtrace();
        if (count($caller) > 0) {
            $tmp_r = $caller[0];
            $line .= "<strong style='color:blue'>Location:</strong> => <span style='color:red'>" . $tmp_r['file'] . '</span>';
            $line .= " (" . $tmp_r['line'] . ')';
        }

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">'
            . $label
            . $line
            . PHP_EOL
            . $separator
            . $output
            . '</pre>';

        // Output
        if ($echo == true) {
            echo $output;

            if ($exit) {
                die();
            }
        } else {
            return $output;
        }
    }

    public function start()
    {
        DB::table($this->parser_final_table)->truncate();

        $data = DB::table($this->parser_data_table)->orderBy('id');
        //->where('code', '001857');

        $data->chunk(500, function ($rows) {
            if ($rows) {
                foreach ($rows as $row) {

                    //dump($row);

                    $code = $row->code;
                    $data = $row->data;
                    $data = str_replace("\r", '', $data);
                    $data = str_replace("\n", '', $data);
                    $data = explode(' ', $data);
                    $data = array_filter($data);
                    $data = array_unique($data);
                    dd($data);
                    /**
                     * Remove Duplicates
                     */
                    $data = array_unique($data);
                    //dump($data);

                    $dataToInsertEqualData = array(
                        'code'        => $code,
                        'prod1'       => $this->generateData($data, 'prod1', '=') ? $this->generateData($data, 'prod1', '=') : '-1',
                        'prod2'       => $this->generateData($data, 'prod2', '=') ? $this->generateData($data, 'prod2', '=') : '-1',
                        'prod3'       => $this->generateData($data, 'prod3', '=') ? $this->generateData($data, 'prod3', '=') : '-1',
                        'prod4'       => $this->generateData($data, 'prod4', '=') ? $this->generateData($data, 'prod4', '=') : '-1',
                        'prod5'       => $this->generateData($data, 'prod5', '=') ? $this->generateData($data, 'prod5', '=') : '-1',
                        'prod6'       => $this->generateData($data, 'prod6', '=') ? $this->generateData($data, 'prod6', '=') : '-1',
                        'prod7'       => $this->generateData($data, 'prod7', '=') ? $this->generateData($data, 'prod7', '=') : '-1',
                        'prod8'       => $this->generateData($data, 'prod8', '=') ? $this->generateData($data, 'prod8', '=') : '-1',
                        'pop_type'    => $this->generateData($data, 'poptype', '=') ? $this->generateData($data, 'poptype', '=') : '-1',
                        'geo_code'    => $this->generateData($data, 'geo_code', '=') ? $this->generateData($data, 'geo_code', '=') : '-1',
                        'town'        => $this->generateData($data, 'town', '=') ? $this->generateData($data, 'town', '=') : '-1',
                        'sku'         => $this->generateData($data, 'sku', '=') ? $this->generateData($data, 'sku', '=') : '-1',
                        'sub_element' => $this->generateData($data, 'sub_element', '=') ? $this->generateData($data, 'sub_element', '=') : '-1',
                        'rank'        => $this->generateData($data, 'rank', '=') ? $this->generateData($data, 'rank', '=') : '-1',
                        'a'           => $this->generateData($data, 'a', '=') ? $this->generateData($data, 'a', '=') : '-1',
                        'parameter3'  => $this->generateData($data, 'parameter3', '=') ? $this->generateData($data, 'parameter3', '=') : '-1',
                        'parameter4'  => $this->generateData($data, 'parameter4', '=') ? $this->generateData($data, 'parameter4', '=') : '-1',
                        'flag'        => 1 // equal data
                    );
                    //dump($dataToInsertEqualData);

                    $dataToInsertNotEqualData = array(
                        'code'        => $code,
                        'prod1'       => $this->generateData($data, 'prod1', '<>') ? $this->generateData($data, 'prod1', '<>') : '-1',
                        'prod2'       => $this->generateData($data, 'prod2', '<>') ? $this->generateData($data, 'prod2', '<>') : '-1',
                        'prod3'       => $this->generateData($data, 'prod3', '<>') ? $this->generateData($data, 'prod3', '<>') : '-1',
                        'prod4'       => $this->generateData($data, 'prod4', '<>') ? $this->generateData($data, 'prod4', '<>') : '-1',
                        'prod5'       => $this->generateData($data, 'prod5', '<>') ? $this->generateData($data, 'prod5', '<>') : '-1',
                        'prod6'       => $this->generateData($data, 'prod6', '<>') ? $this->generateData($data, 'prod6', '<>') : '-1',
                        'prod7'       => $this->generateData($data, 'prod7', '<>') ? $this->generateData($data, 'prod7', '<>') : '-1',
                        'prod8'       => $this->generateData($data, 'prod8', '<>') ? $this->generateData($data, 'prod8', '<>') : '-1',
                        'pop_type'    => $this->generateData($data, 'poptype', '<>') ? $this->generateData($data, 'poptype', '<>') : '-1',
                        'geo_code'    => $this->generateData($data, 'geo_code', '<>') ? $this->generateData($data, 'geo_code', '<>') : '-1',
                        'town'        => $this->generateData($data, 'town', '<>') ? $this->generateData($data, 'town', '<>') : '-1',
                        'sku'         => $this->generateData($data, 'sku', '<>') ? $this->generateData($data, 'sku', '<>') : '-1',
                        'sub_element' => $this->generateData($data, 'sub_element', '<>') ? $this->generateData($data, 'sub_element', '<>') : '-1',
                        'rank'        => $this->generateData($data, 'rank', '<>') ? $this->generateData($data, 'rank', '<>') : '-1',
                        'a'           => $this->generateData($data, 'a', '<>') ? $this->generateData($data, 'a', '<>') : '-1',
                        'parameter3'  => $this->generateData($data, 'parameter3', '<>') ? $this->generateData($data, 'parameter3', '<>') : '-1',
                        'parameter4'  => $this->generateData($data, 'parameter4', '<>') ? $this->generateData($data, 'parameter4', '<>') : '-1',
                        'flag'        => 0 // not equal data
                    );
                    //dump($dataToInsertNotEqualData);

                    DB::table($this->parser_final_table)->insert($dataToInsertEqualData);
                    DB::table($this->parser_final_table)->insert($dataToInsertNotEqualData);
                }
                //dd('temp-stop');
            }
        });

        dd('Finish');
    }

    private function generateData(array $data, $type = '', $flag = '<>')
    {
        $output = '';

        if (is_array($data)) {
            if ($type == 'a') {

                $typesData = ['a0', 'a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8', 'a9'];
                $output = '';
                foreach ($typesData as $t) {
                    foreach ($data as $i) {
                        if (strpos($i, $t . $flag) !== false) {
                            $x = str_replace($t . $flag, '', $i);
                            $output .= $x . ':';
                        }
                    }
                }

                $a = explode(':', $output);
                $b = array_filter($a);
                $c = array_unique($b);

                if ($c) {
                    $output = '';
                    foreach ($c as $datum) {
                        $output .= $datum . ':';
                    }
                }

                $output = rtrim($output, ":");

            } else {
                $output = '';
                foreach ($data as $i) {
                    if (strpos($i, $type . $flag) !== false) {
                        $x = str_replace($type . $flag, '', $i);
                        $output .= $x . ':';
                    }
                }

                $output = rtrim($output, ":");
            }
        }

        // Check if output is like commas separated values
        $string = explode(",", $output);
        if ($string) {
            $output = '';
            foreach ($string as $item) {
                $output .= $item . ':';
            }
            $output = rtrim($output, ":");
        }

        //Remove all whitespaces
        $output = preg_replace('/\s+/', '', $output);

        return $output;
    }


    public function generateCombinations()
    {
//        dd(
//            $this->combinationsAlgorithm(
//                array(
//                    array(['col1' => 'abc', 'col2' => 'xyz']),
//                    array(['col1' => 'wao', 'col2' => 'ok']),
//                )
//            )
//        );


        $cc = $this->combinationsAlgorithm(
            array(
                array('-1', '-1', '-1'),
                array('B1', 'B2', 'B3'),
            )
        );

        dd($cc);


        $data = DB::table($this->parser_final_table)
            ->limit(10)
            ->orderBy('id');

        foreach ($data->get() as $row) {
//            $prod1_data = $row->prod1;
//            $prod2_data = $row->prod2;
//            $prod3_data = $row->prod3;
//            $prod4_data = $row->prod4;
//            $prod5_data = $row->prod5;
//            $prod6_data = $row->prod6;
//            $prod7_data = $row->prod7;
//            $prod8_data = $row->prod8;
//            $pop_type_data = $row->pop_type;
//            $geo_code_data = $row->geo_code;
//            $town_data = $row->town;
//            $sku_data = $row->sku;
//            $sub_element_data = $row->sub_element;
//            $rank_data = $row->rank;
//            $a_data = $row->a;

//            dump($prod1_data);
//            dump($pop_type_data);

            $prod1_data = [];
            if ($row->prod1 != '-1') {
                if (count(explode(',', $row->prod1)) > 1) {
                    foreach (explode(',', $row->prod1) as $now) {
                        $prod1_data[] = $now;
                    }
                } else {
                    dump('im here');
                    $prod1_data[] = $row->prod1;
                }
            }

            $pop_type_data = [];
            if ($row->pop_type != '-1') {
                if (count(explode(',', $row->pop_type)) > 1) {
                    foreach (explode(',', $row->pop_type) as $now) {
                        $pop_type_data[] = $now;
                    }
                } else {
                    $pop_type_data[] = $row->pop_type;
                }
            }

            //dump($prod1_data);
            dump($pop_type_data);

            //$this->combinationsAlgorithm([])
        }

        dd('stop');


        $insertData = array(
            'code'        => '',
            'prod1'       => '',
            'prod2'       => '',
            'prod3'       => '',
            'prod4'       => '',
            'prod5'       => '',
            'prod6'       => '',
            'prod7'       => '',
            'prod8'       => '',
            'pop_type'    => '',
            'geo_code'    => '',
            'town'        => '',
            'sku'         => '',
            'sub_element' => '',
            'rank'        => '',
            'a'           => '',
            'flag'        => ''
        );
        //DB::table($this->parser_combinations_table)->insert($insertData);


        dd('ok');

        $data->chunk(10, function ($rows) {
            if ($rows) {
                foreach ($rows as $row) {


//                    $this->insertCombinationData($row, 'a');
                }
            }
        });

        //dd('Combination generated!');
    }

    private function insertCombinationData($data, $column = 'pop_type')
    {
        //dump($column);
        if (count(explode(',', $data->$column)) > 1) {
            foreach (explode(',', $data->$column) as $row) {
                $insertData = array(
                    'code'        => $data->code,
                    'prod1'       => $data->prod1,
                    'prod2'       => $data->prod2,
                    'prod3'       => $data->prod3,
                    'prod4'       => $data->prod4,
                    'prod5'       => $data->prod5,
                    'prod6'       => $data->prod6,
                    'prod7'       => $data->prod7,
                    'prod8'       => $data->prod8,
                    'pop_type'    => $data->pop_type,
                    'geo_code'    => $data->geo_code,
                    'town'        => $data->town,
                    'sku'         => $data->sku,
                    'sub_element' => $data->sub_element,
                    'rank'        => $data->rank,
                    'a'           => $data->a,
                    'flag'        => $data->flag
                );
                $insertData['pop_type'] = $row;
                DB::table($this->parser_combinations_table)->insert($insertData);
                //dump($insertData);
            }
        }
    }

    private function combinationsAlgorithm($arrays, $i = 0)
    {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = $this->combinationsAlgorithm($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }

    public function getDoExport()
    {
        //echo '<pre>';
        $data = DB::table('parser_final');
        //->where('flag', 1)
        //->limit(2000)
//            ->where('code', '0011085')
//            ->where('a', '!=', '-1');

        //dump($data->toSql());

        //dd($data->count());

        $data = $data->get()->toArray();

        if ($data) {

            $list = [];

            foreach ($data as $row) {

                $output = '';
                $aaa = explode(':', $row->a);

                //$aa = str_replace("\r\n",'', $aaa);;
                $aa = array_unique($aaa);
                //dd($aa);
                if ($aa) {
                    foreach ($aa as $datum) {
                        $output .= $datum . ':';
                    }
                }

                $list[] = [
                    'code'        => "\t$row->code",
                    'prod1'       => $row->prod1,
                    'prod2'       => $row->prod2,
                    'prod3'       => $row->prod3,
                    'prod4'       => $row->prod4,
                    'prod5'       => $row->prod5,
                    'prod6'       => $row->prod6,
                    'prod7'       => $row->prod7,
                    'prod8'       => $row->prod8,
                    'pop_type'    => "\t$row->pop_type",
                    'geo_code'    => $row->geo_code,
                    'town'        => $row->town,
                    'sku'         => $row->sku,
                    'sub_element' => $row->sub_element,
                    'rank'        => $row->rank,
                    'a'           => rtrim($output, ":"),
                    'parameter3'  => "\t$row->parameter3",
                    'parameter4'  => "\t$row->parameter4",
                    'flag'        => $row->flag,
                ];
            }
        }

        //dd($list);

        //*/
        // data mapping and filtering
        header('Content-Disposition: attachment; filename="export.csv"');
        header("Cache-control: private");
        header("Content-type: text/csv");
        header("Content-transfer-encoding: binary\n");
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($list[0]));
        foreach ($list as $line) {
            fputcsv($out, $line);
        }
        fclose($out);
        exit();
        //*/
    }
}

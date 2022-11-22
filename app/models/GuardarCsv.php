<?php

class GuardarCSV
{
    public function GuardarCsv($lista)
    {

        $fp = fopen('fichero.csv', 'w');

        foreach ($lista as $campos) {
            fputcsv($fp, $campos);
        }

        fclose($fp);
    }
}

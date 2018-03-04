<?php

require_once 'Console/Table.php';

$tbl = new Console_Table();

$tbl->setHeaders(array('Language', 'Year'));

$tbl->addRow(array('PHP', 1994));
$tbl->addRow(array('C',   1970));
$tbl->addRow(array('C++', 1983));

echo $tbl->getTable();

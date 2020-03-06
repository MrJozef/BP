<?php
include_once $_SERVER['DOCUMENT_ROOT']."/wcm/model/Manager.php";

const CAT_NAME_MIN_LENGTH = 3;
const CAT_NAME_MAX_LENGTH = 100;

const CAT_DESCRIPT_MIN_LENGTH = 20;
const CAT_DESCRIPT_MAX_LENGTH = 2000;

const VERIFIED = 1;
const UNVERIFIED = 0;


class ManagerCategory extends Manager
{
    public function getOnlyNames() {
        $task = 'SELECT name FROM category ORDER BY id_category DESC';

        return DBWrap::selectAll($task, []);
    }
}
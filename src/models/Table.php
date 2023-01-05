<?php

abstract Class Table
{
    public static string $TableName;
    abstract static protected function getAddForm(): string;
    abstract protected function getEditForm(): string;
    abstract protected function getDeleteForm(): string;

    abstract protected function getTableRow(): string;
    abstract protected static function getTableHeader(): string;

    /**
     * @param self[] $entities
     * @return string
     */
    abstract protected static function getTableBody(array $entities): string;

    /**
     * @param self[] $entities
     * @return string
     */
    abstract protected static function getTable(array $entities): string;


    abstract public static function getPage(): string;



}
<?php
require_once __DIR__ . '/../config/conexionDB.php';

abstract class CrudModel
{
    protected static $table;
    protected static $columns = [];
    protected static $primaryKeys = ['id'];

    public static function all()
    {
        $table = static::$table;
        return ConexionPDO::query("SELECT * FROM `$table`");
    }

    public static function find(...$ids)
    {
        $keys = static::$primaryKeys;
        if (count($ids) !== count($keys)) {
            return null;
        }

        $where = [];
        $values = [];
        foreach ($keys as $index => $key) {
            $where[] = "`$key` = :key$index";
            $values[":key$index"] = $ids[$index];
        }

        $table = static::$table;
        $rows = ConexionPDO::query("SELECT * FROM `$table` WHERE " . implode(' AND ', $where), $values);
        return isset($rows[0]) ? $rows[0] : null;
    }

    public static function update(...$arguments)
    {
        $data = array_pop($arguments);
        $data = static::filterData($data);
        if (!$data || count($arguments) !== count(static::$primaryKeys)) {
            return 0;
        }

        $sets = [];
        $values = [];
        foreach ($data as $column => $value) {
            $sets[] = "`$column` = :$column";
            $values[":$column"] = $value;
        }

        $where = static::buildWhere($arguments, $values);
        $table = static::$table;
        return ConexionPDO::execute("UPDATE `$table` SET " . implode(', ', $sets) . " WHERE $where", $values);
    }

    public static function add($data)
    {
        $data = static::filterData($data);
        if (!$data) {
            return 0;
        }

        $columns = array_keys($data);
        $placeholders = array_map(function ($column) {
            return ":$column";
        }, $columns);
        $values = [];
        foreach ($data as $column => $value) {
            $values[":$column"] = $value;
        }

        $table = static::$table;
        return ConexionPDO::execute(
            "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")",
            $values,
            true
        );
    }

    public static function delete(...$ids)
    {
        if (count($ids) !== count(static::$primaryKeys)) {
            return 0;
        }

        $values = [];
        $where = static::buildWhere($ids, $values);
        $table = static::$table;
        return ConexionPDO::execute("DELETE FROM `$table` WHERE $where", $values);
    }

    private static function filterData($data)
    {
        if (!is_array($data)) {
            return [];
        }

        $allowed = static::$columns;
        $filtered = [];
        foreach ($data as $column => $value) {
            if (in_array($column, $allowed, true)) {
                $filtered[$column] = is_string($value) ? trim($value) : $value;
            }
        }
        return $filtered;
    }

    private static function buildWhere($ids, &$values)
    {
        $where = [];
        foreach (static::$primaryKeys as $index => $key) {
            $placeholder = ":key$index";
            $where[] = "`$key` = $placeholder";
            $values[$placeholder] = $ids[$index];
        }
        return implode(' AND ', $where);
    }
}

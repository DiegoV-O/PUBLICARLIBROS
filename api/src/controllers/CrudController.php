<?php

abstract class CrudController
{
    protected static $model;
    protected static $required = [];
    protected static $label = 'registro';

    public function getAll()
    {
        echo json_encode(call_user_func([static::$model, 'all']));
    }

    public function getById(...$ids)
    {
        $record = call_user_func_array([static::$model, 'find'], $ids);
        if ($record !== null) {
            echo json_encode($record);
            return;
        }

        http_response_code(404);
        echo json_encode(['estado' => false, 'message' => ucfirst(static::$label) . ' no encontrado']);
    }

    public function getOne(...$ids)
    {
        $this->getById(...$ids);
    }

    public function add()
    {
        $data = $this->input();
        if ($data === null) {
            return;
        }

        $errors = $this->validate($data, true);
        if ($errors) {
            $this->validationError($errors);
            return;
        }

        $result = call_user_func([static::$model, 'add'], $data);
        if ($result) {
            echo json_encode(['estado' => true, 'message' => ucfirst(static::$label) . ' adicionado correctamente']);
            return;
        }
        $this->failure('No se pudo crear ' . static::$label);
    }

    public function update(...$ids)
    {
        $data = $this->input();
        if ($data === null) {
            return;
        }

        $errors = $this->validate($data, false);
        if ($errors) {
            $this->validationError($errors);
            return;
        }

        $result = call_user_func_array([static::$model, 'update'], array_merge($ids, [$data]));
        if ($result) {
            echo json_encode(['estado' => true, 'message' => ucfirst(static::$label) . ' actualizado correctamente']);
            return;
        }
        $this->failure('No se pudo actualizar ' . static::$label);
    }

    public function delete(...$ids)
    {
        $result = call_user_func_array([static::$model, 'delete'], $ids);
        if ($result) {
            echo json_encode(['estado' => true, 'message' => ucfirst(static::$label) . ' eliminado correctamente']);
            return;
        }
        $this->failure('No se pudo eliminar ' . static::$label);
    }

    private function input()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error codificacion', 'message' => json_last_error_msg() ?: 'JSON invalido']);
            return null;
        }
        return $data;
    }

    private function validate($data, $isNew)
    {
        $errors = [];
        foreach (static::$required as $field) {
            if ($isNew && (!array_key_exists($field, $data) || $data[$field] === null || trim((string) $data[$field]) === '')) {
                $errors[] = "El campo $field es obligatorio";
            }
        }

        if (!$isNew && !$data) {
            $errors[] = 'Debe enviar al menos un campo para actualizar';
        }
        return $errors;
    }

    private function validationError($errors)
    {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'message' => 'Existen errores de validacion', 'errores' => $errors]);
    }

    private function failure($message)
    {
        http_response_code(400);
        echo json_encode(['estado' => false, 'message' => $message]);
    }
}

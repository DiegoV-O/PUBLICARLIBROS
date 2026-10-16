<?php
require_once __DIR__ . "/../models/Users.php";

class UserController
{
    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $identifier = is_array($data) ? trim((string) ($data['identifier'] ?? '')) : '';
        $password = is_array($data) ? (string) ($data['password'] ?? '') : '';

        if ($identifier === '' || $password === '') {
            http_response_code(422);
            echo json_encode([
                "estado" => false,
                "message" => "Ingresa tu usuario o correo y contraseña",
            ]);
            return;
        }

        $user = Users::findForLogin($identifier);
        if (!$user || !(int) $user['activo'] || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode([
                "estado" => false,
                "message" => "Usuario o contraseña incorrectos",
            ]);
            return;
        }

        unset($user['password']);
        echo json_encode([
            "estado" => true,
            "user" => $user,
        ]);
    }

    public function getAll()
    {
        $user = Users::all();
        echo json_encode($user); 
    }

    public function getById($id)
    {
        $user = Users::find($id);
        if ($user) {
            echo json_encode($user);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Usuario no encontrado",
        ]);
    }

    public function getOne($id)
    {
        $this->getById($id);
    }

    public function update($id)
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error codificacion",
                "message" => json_last_error_msg() ?: "JSON inválido",
            ]);
            return;
        }

        $errores = $this->validarDatos($data, false);

        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores,
            ]);
            return;
        }

        $data = $this->prepararDatos($data, false);
        $user = Users::update($id, $data);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario actualizado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar el usuario",
        ]);
    }

    public function add()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error codificacion",
                "message" => json_last_error_msg() ?: "JSON inválido",
            ]);
            return;
        }

        $errores = $this->validarDatos($data, true);

        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores,
            ]);
            return;
        }

        $data = $this->prepararDatos($data, true);
        $user = Users::add($data);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo crear el usuario",
        ]);
    }

    public function delete($id)
    {
        $user = Users::delete($id);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario desactivado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el usuario",
        ]);
    }
    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['username']) || trim((string) $data['username']) === "") {
            $errores[] = "El campo username es obligatorio";
        } elseif (strlen((string) $data['username']) > 50) {
            $errores[] = "El campo username no debe superar los 50 caracteres";
        }

        if ($esNuevo && (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL))) {
            $errores[] = "El campo email es obligatorio y debe ser válido";
        }

        $password = $data['password'] ?? null;
        if ($esNuevo && (!is_scalar($password) || trim((string) $password) === "")) {
            $errores[] = "El campo password es obligatorio";
        } elseif ($password !== null && strlen((string) $password) > 255) {
            $errores[] = "El campo password no debe superar los 255 caracteres";
        }

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El campo email no es válido";
        }

        if (isset($data['rol']) && !in_array($data['rol'], ['Administrador', 'Personal', 'Lector'], true)) {
            $errores[] = "El campo rol no es válido";
        }

        if (isset($data['activo']) && !in_array((int) $data['activo'], [0, 1], true)) {
            $errores[] = "El campo activo debe ser 0 o 1";
        }

        return $errores;
    }

    private function prepararDatos($data, $esNuevo)
    {
        if (isset($data['password'])) {
            $password = trim((string) $data['password']);
            if ($password !== '') {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            } elseif (!$esNuevo) {
                unset($data['password']);
            }
        }

        if ($esNuevo) {
            $data['rol'] = $data['rol'] ?? 'Lector';
            $data['activo'] = isset($data['activo']) ? (int) $data['activo'] : 1;
        }

        return $data;
    }
}
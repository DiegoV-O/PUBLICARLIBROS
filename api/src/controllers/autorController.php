<?php
require_once __DIR__ . "/../models/autores.php";

class autorController
{
    public function getAll()
    {
        $autor = autores::all();
        echo json_encode($autor); 
    }

    public function getById($id)
    {
        $autor = autores::find($id);
        if ($autor) {
            echo json_encode($autor);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Autor no encontrado",
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
                "message" => "Existen errores de validación",
                "errores" => $errores,
            ]);
            return;
        }

        $data = $this->prepararDatos($data, false);
        // Llamada a la clase en minúsculas
        $autor = autores::update($id, $data);
        if ($autor) {
            echo json_encode([
                "estado" => true,
                "message" => "Autor actualizado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar el autor",
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
                "message" => "Existen errores de validación",
                "errores" => $errores,
            ]);
            return;
        }

        $data = $this->prepararDatos($data, true);
        // Llamada a la clase en minúsculas
        $autor = autores::add($data);
        if ($autor) {
            echo json_encode([
                "estado" => true,
                "message" => "Autor adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo crear el autor",
        ]);
    }

    public function delete($id)
    {
        // Llamada a la clase en minúsculas
        $autor = autores::delete($id);
        if ($autor) {
            echo json_encode([
                "estado" => true,
                "message" => "Autor eliminado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el autor",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son válidos";
            return $errores;
        }

        if (!isset($data['nombre']) || trim((string) $data['nombre']) === "") {
            $errores[] = "El campo nombre es obligatorio";
        } elseif (strlen((string) $data['nombre']) > 100) {
            $errores[] = "El campo nombre no debe superar los 100 caracteres";
        }

        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El campo email no es válido";
        }

        if (isset($data['activo']) && !in_array((int) $data['activo'], [0, 1], true)) {
            $errores[] = "El campo activo debe ser 0 o 1";
        }

        return $errores;
    }

    private function prepararDatos($data, $esNuevo)
    {
        if (isset($data['nombre'])) {
            $data['nombre'] = trim((string) $data['nombre']);
        }

        if ($esNuevo) {
            $data['activo'] = isset($data['activo']) ? (int) $data['activo'] : 1;
        }

        return $data;
    }
}

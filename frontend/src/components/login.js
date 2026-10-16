import { api } from "../utils/api.js";

const form = document.getElementById("loginForm");
const loginMessage = document.getElementById("loginMessage");
const submitButton = document.getElementById("submitButton");
const buttonLabel = document.getElementById("buttonLabel");
const registerForm = document.getElementById("registerForm");
const registerMessage = document.getElementById("registerMessage");
const registerButton = document.getElementById("registerButton");
const registerButtonLabel = document.getElementById("registerButtonLabel");
const loginPrompt = document.getElementById("loginPrompt");
const registerPrompt = document.getElementById("registerPrompt");
const modeEyebrow = document.getElementById("modeEyebrow");
const modeTitle = document.getElementById("modeTitle");
const modeDescription = document.getElementById("modeDescription");

const showMessage = (element, text, isError = true) => {
  element.textContent = text;
  element.className = `text-sm ${isError ? "text-red-700" : "text-emerald-700"}`;
};

const setMode = (registering) => {
  form.classList.toggle("hidden", registering);
  registerForm.classList.toggle("hidden", !registering);
  loginPrompt.classList.toggle("hidden", registering);
  registerPrompt.classList.toggle("hidden", !registering);
  modeEyebrow.textContent = registering ? "Únete al espacio de autor" : "Bienvenido de nuevo";
  modeTitle.textContent = registering ? "Crear cuenta" : "Iniciar sesión";
  modeDescription.textContent = registering
    ? "Regístrate para guardar y administrar tus publicaciones."
    : "Ingresa tus datos para acceder a tu cuenta.";
  loginMessage.classList.add("hidden");
  registerMessage.classList.add("hidden");
};

document.getElementById("showRegister").addEventListener("click", () => setMode(true));
document.getElementById("showLogin").addEventListener("click", () => setMode(false));

form.addEventListener("submit", async (event) => {
  event.preventDefault();
  loginMessage.classList.add("hidden");

  submitButton.disabled = true;
  buttonLabel.textContent = "Verificando...";

  try {
    const result = await api.post("/login", {
      identifier: form.elements.identifier.value.trim(),
      password: form.elements.password.value,
    });
    if (!result.estado) {
      throw new Error(result.message || "No se pudo iniciar sesión.");
    }

    sessionStorage.setItem("publicarlibros.user", JSON.stringify(result.user));
    window.location.assign("../../index.html");
  } catch (error) {
    showMessage(loginMessage, error.message || "No se pudo conectar con el servicio de inicio de sesión.");
  } finally {
    submitButton.disabled = false;
    buttonLabel.textContent = "Entrar al sistema";
  }
});

registerForm.addEventListener("submit", async (event) => {
  event.preventDefault();
  registerMessage.classList.add("hidden");

  const username = registerForm.elements.username.value.trim();
  const email = registerForm.elements.email.value.trim();
  const password = registerForm.elements.password.value;
  const confirmation = registerForm.elements.confirmPassword.value;
  if (password !== confirmation) {
    showMessage(registerMessage, "Las contraseñas no coinciden.");
    return;
  }

  registerButton.disabled = true;
  registerButtonLabel.textContent = "Guardando cuenta...";
  try {
    const result = await api.post("/users", {
      username,
      email,
      password,
      rol: "Lector",
      activo: 1,
    });
    if (!result.estado) {
      throw new Error(result.message || "No se pudo crear la cuenta.");
    }

    form.elements.identifier.value = username;
    registerForm.reset();
    setMode(false);
    showMessage(loginMessage, "Cuenta guardada. Ya puedes iniciar sesión.", false);
  } catch (error) {
    showMessage(registerMessage, error.message || "No se pudo guardar la cuenta.");
  } finally {
    registerButton.disabled = false;
    registerButtonLabel.textContent = "Crear cuenta";
  }
});
import {api} from '../utils/api.js';
const container = () => document.getElementById('userTableList');

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');

export const getUserList = async () => {
    const table = container();
    table.innerHTML = '<tr><td colspan="5">Cargando...</td></tr>';
    try {
        const users = await api.get('/users');
        table.innerHTML = users.map(user => `
            <tr>
                <td>${user.id}</td>
                <td>${escapeHtml(user.username)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td>${escapeHtml(user.rol)} · ${Number(user.activo) ? 'Activo' : 'Inactivo'}</td>
                <td>
                    <button type="button" data-edit="${user.id}">Editar</button>
                    <button type="button" data-toggle-active="${user.id}">${Number(user.activo) ? 'Desactivar' : 'Activar'}</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        table.innerHTML = `<tr><td colspan="5">${escapeHtml(error.message)}</td></tr>`;
    }
};

export const initUserCrud = () => {
    const form = document.getElementById('userForm');
    const table = container();
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const id = form.elements.id.value;
        const data = Object.fromEntries(new FormData(form));
        delete data.id;
        if (!data.password) delete data.password;
        data.activo = form.elements.activo.checked ? 1 : 0;
        try {
            await (id ? api.put(`/users/${id}`, data) : api.post('/users', data));
            form.reset();
            form.elements.id.value = '';
            document.getElementById('formTitle').textContent = 'Nuevo usuario';
            await getUserList();
        } catch (error) {
            document.getElementById('userMessage').textContent = error.message;
        }
    });
    table.addEventListener('click', async (event) => {
        const id = event.target.dataset.edit || event.target.dataset.toggleActive;
        if (!id) return;
        if (event.target.dataset.edit) {
            const user = (await api.get('/users')).find((item) => String(item.id) === id);
            form.elements.id.value = user.id;
            form.elements.username.value = user.username;
            form.elements.email.value = user.email;
            form.elements.rol.value = user.rol;
            form.elements.activo.checked = Number(user.activo) === 1;
            form.elements.password.value = '';
            document.getElementById('formTitle').textContent = 'Editar usuario';
            return;
        }
        const user = (await api.get('/users')).find((item) => String(item.id) === id);
        if (!user) return;
        const activating = Number(user.activo) === 0;
        if (!activating && !confirm('¿Desactivar este usuario? Podrá reactivarse después.')) return;
        await api.put(`/users/${id}`, { username: user.username, activo: activating ? 1 : 0 });
        await getUserList();
    });
    document.getElementById('cancelUser').addEventListener('click', () => {
        form.reset();
        form.elements.id.value = '';
        document.getElementById('formTitle').textContent = 'Nuevo usuario';
    });
    return getUserList();
};
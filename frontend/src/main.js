import { initUserCrud } from "./components/UserList.js";
const app = document.getElementById('app') || document.getElementById('root');
const loadUsers = async () => {
    const response = await fetch('./src/views/user.html');
    app.innerHTML = await response.text();
    await initUserCrud();
};

loadUsers();
import axios from "axios";

const BASE_URL = "https://spa.local/app/";

function authHeader() {
    const user = JSON.parse(localStorage.getItem('user'));
    return user?.authToken ? {Authorization: 'Bearer ' + user.authToken} : {};
}

const getPublicContent = () => axios.get(BASE_URL + "?r=articles/index").then(response => response.data);
const getUserBoard = () => axios.get(BASE_URL + "user", { headers: authHeader() });
const getModeratorBoard = () => axios.get(BASE_URL + "mod", { headers: authHeader() });
const getAdminBoard = () => axios.get(BASE_URL + "admin", { headers: authHeader() });
const userService = {
    getPublicContent,
    getUserBoard,
    getModeratorBoard,
    getAdminBoard,
};

export default userService
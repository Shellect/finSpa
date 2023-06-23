import axios from "axios";

const BASE_URL = "https://spa.local/app/";
const register = (username, password) => {
    return axios.post(BASE_URL + "?r=user/signup", {username, password})
        .then((response) => {
            if (response.data.authToken) {
                localStorage.setItem("user", JSON.stringify(response.data));
            }
            return response.data;
        });
};

const login = (username, password) => {
    return axios
        .post(BASE_URL + "?r=user/signin", {
            username,
            password,
        })
        .then((response) => {
            if (response.data.authToken) {
                localStorage.setItem("user", JSON.stringify(response.data));
            }
            return response.data;
        });
};

const logout = () => {
    return axios
        .get(BASE_URL + "?r=user/signout", {headers: {Authorization: `Bearer ${localStorage.getItem("authToken")}`}})
        .then(response => {
            localStorage.removeItem("user");
            return response.data
        });
};
const getCurrentUser = () => {
    return JSON.parse(localStorage.getItem("user"));
};

const AuthService = {
    register,
    login,
    logout
}

export default AuthService;
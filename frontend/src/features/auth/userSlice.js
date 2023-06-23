import {createSlice} from "@reduxjs/toolkit";
import register from "../../actions/register";
import login from "../../actions/login";


const user = JSON.parse(localStorage.getItem("user"));
const initialState = user
    ? {
        isLoggedIn: true,
        error: null,
        loading: false,
        user
    } : {
        isLoggedIn: false,
        error: null,
        loading: false,
        user: {
            username: '',
            roles: []
        }
    };

const userSlice = createSlice({
    name: "user",
    initialState,
    reducers: {
        logout: state => {
            localStorage.removeItem("authToken");
            return initialState;
        }
    },
    extraReducers: (builder) => {
        builder.addCase(register.pending, state => {
            state.loading = true;
        }).addCase(register.fulfilled, (state, {payload}) => {
            state.isLoggedIn = payload.status === "Success";
            state.user.username = payload.username;
            state.user.roles = payload.roles;
            state.error = false;
            state.loading = false;
        }).addCase(register.rejected, state => {
            state.isLoggedIn = false;
            state.error = true;
            state.loading = false;
        }).addCase(login.pending, state => {
            state.loading = true;
        }).addCase(login.fulfilled, (state, {payload}) => {
            state.user.username = payload.username;
            state.user.roles = payload.roles;
            state.error = false;
            state.loading = false;
            state.isLoggedIn = payload.status === "Success";
        }).addCase(login.rejected, state => {
            state.error = true;
            state.loading = false;
            state.isLoggedIn = false;
        });
    }
});

export const {logout} = userSlice.actions;

const {reducer,} = userSlice;
export default reducer;

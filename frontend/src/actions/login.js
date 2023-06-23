import {createAsyncThunk} from "@reduxjs/toolkit";
import AuthService from "../services/auth.service";

const login = createAsyncThunk(
    "user/login",
    async ({username, password}, {rejectWithValue}) => {
        try {
            return await AuthService.login(username, password);
        } catch (error) {
            return error.response?.data.message
                ? rejectWithValue(error.response.data.message)
                : rejectWithValue(error.message);
        }
    }
);

export default login;
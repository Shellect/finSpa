import {createAsyncThunk} from "@reduxjs/toolkit";
import AuthService from "../services/auth.service";

const register = createAsyncThunk(
    "user/register",
    async ({username, password}, {rejectWithValue, dispatch}) => {
        try {
            let data = await AuthService.register(username, password);
            if (data.status !== "Success"){
                return rejectWithValue({message: data.message });
            }
        } catch (error) {
            return error.response?.data.message
                ? rejectWithValue(error.response.data.message)
                : rejectWithValue(error.message);
        }
    }
);

export default register;
import {createAsyncThunk} from "@reduxjs/toolkit";
import AuthService from "../services/auth.service";

const logout = createAsyncThunk(
    "user/logout",
    async () => await AuthService.logout()
);

export default logout;